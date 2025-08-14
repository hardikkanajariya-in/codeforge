<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Services\DataGenerationService;
use HkDevs\CodeForgeStudio\Models\DataGenerationTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

/**
 * GenerateDataCommand
 * 
 * Intelligent test data generation utility for CodeForge Database Studio.
 * Creates realistic, contextually appropriate test data for database tables with relationship awareness.
 * 
 * Features:
 * - Smart data generation based on column names and types
 * - Relationship-aware data creation maintaining referential integrity
 * - Template-based generation for consistent and reusable data patterns
 * - Preview mode for safe data validation before insertion
 * - Configurable record count with performance optimization
 * - Custom template creation and management
 * - Support for complex data types and constraints
 * 
 * Data Generation Intelligence:
 * - Column Name Analysis: Detects fields like 'email', 'phone', 'name' for appropriate data
 * - Data Type Mapping: Generates contextually relevant data for each column type
 * - Constraint Compliance: Respects unique constraints, foreign keys, and validation rules
 * - Locale Support: Generates culturally appropriate data for different regions
 * - Pattern Recognition: Identifies common patterns (URLs, addresses, dates)
 * 
 * Template System:
 * - Save generation configurations as reusable templates
 * - Template-based consistent data creation across environments
 * - Version-controlled data generation patterns
 * - Shareable templates for team development
 * - Custom field mapping and data rules
 * 
 * Relationship Handling:
 * - Automatic foreign key population from related tables
 * - Cascade data generation across related tables
 * - Maintains referential integrity during bulk generation
 * - Smart handling of complex relationship scenarios
 * 
 * Performance Features:
 * - Batch insertion for large datasets
 * - Memory-efficient streaming for massive data generation
 * - Progress tracking for long-running operations
 * - Configurable batch sizes for optimal performance
 * 
 * Safety Features:
 * - Preview mode prevents accidental data insertion
 * - Validation of existing data before generation
 * - Rollback support for generated data
 * - Confirmation prompts for large operations
 * 
 * @package HkDevs\CodeForgeStudio\Commands
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * # Generate 10 user records
 * php artisan codeforge:generate-data users --count=10
 * 
 * # Preview data without inserting
 * php artisan codeforge:generate-data products --count=5 --preview
 * 
 * # Use existing template
 * php artisan codeforge:generate-data orders --template=ecommerce_orders --count=100
 * 
 * # Generate and save as template
 * php artisan codeforge:generate-data customers --count=50 --save-template=customer_base
 */
class GenerateDataCommand extends Command
{
    protected $signature = 'codeforge:generate-data 
                            {table : The table to generate data for}
                            {--count=10 : Number of records to generate}
                            {--template= : Use specific template by name}
                            {--preview : Only preview data without inserting}
                            {--save-template= : Save configuration as template}';

    protected $description = 'Generate test data for database tables';

    public function handle(): int
    {
        $tableName = $this->argument('table');
        $count = (int) $this->option('count');
        $templateName = $this->option('template');
        $preview = $this->option('preview');
        $saveTemplate = $this->option('save-template');

        try {
            if (!Schema::hasTable($tableName)) {
                $this->error("Table '{$tableName}' does not exist.");
                return 1;
            }

            $service = app(DataGenerationService::class);

            // Get or create template
            if ($templateName) {
                $template = DataGenerationTemplate::where('name', $templateName)->first();
                if (!$template) {
                    $this->error("Template '{$templateName}' not found.");
                    return 1;
                }
            } else {
                $this->info("Analyzing table structure...");
                $template = $service->createTemplateFromTable($tableName, 'temp_' . $tableName . '_' . time());
            }

            if ($preview) {
                return $this->previewData($service, $template, min($count, 5));
            }

            if ($saveTemplate && !$templateName) {
                $template->update(['name' => $saveTemplate]);
                $this->info("Template saved as '{$saveTemplate}'");
            }

            return $this->generateData($service, $template, $count);
        } catch (\Exception $e) {
            $this->error('Command failed: ' . $e->getMessage());
            return 1;
        }
    }

    protected function previewData(DataGenerationService $service, DataGenerationTemplate $template, int $count): int
    {
        $this->info("Generating preview data for table: {$template->table_name}");

        try {
            $data = $service->previewData($template, $count);

            if (empty($data)) {
                $this->warn('No data generated.');
                return 0;
            }

            $this->newLine();
            $this->info("Preview ({$count} records):");

            // Display as table
            $headers = array_keys($data[0]);
            $rows = [];

            foreach ($data as $record) {
                $row = [];
                foreach ($headers as $header) {
                    $value = $record[$header] ?? '';

                    // Truncate long values
                    if (is_string($value) && strlen($value) > 30) {
                        $value = substr($value, 0, 27) . '...';
                    } elseif (is_array($value) || is_object($value)) {
                        $value = json_encode($value);
                        if (strlen($value) > 30) {
                            $value = substr($value, 0, 27) . '...';
                        }
                    }

                    $row[] = $value;
                }
                $rows[] = $row;
            }

            $this->table($headers, $rows);

            // Clean up temporary template
            if (str_starts_with($template->name, 'temp_')) {
                $template->delete();
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Preview failed: {$e->getMessage()}");
            return 1;
        }
    }

    protected function generateData(DataGenerationService $service, DataGenerationTemplate $template, int $count): int
    {
        $this->info("Generating {$count} records for table: {$template->table_name}");

        if (!$this->option('template') && !$this->confirm('Proceed with data generation?')) {
            $this->info('Operation cancelled.');

            // Clean up temporary template
            if (str_starts_with($template->name, 'temp_')) {
                $template->delete();
            }

            return 0;
        }

        try {
            $progressBar = $this->output->createProgressBar($count);
            $progressBar->start();

            $result = $service->insertGeneratedData($template, $count);

            $progressBar->finish();
            $this->newLine(2);

            $this->info('Generation completed:');
            $this->line("  Total generated: {$result['total_generated']}");
            $this->line("  Successfully inserted: {$result['successfully_inserted']}");

            if ($result['failed_inserts'] > 0) {
                $this->warn("  Failed inserts: {$result['failed_inserts']}");
            }

            // Clean up temporary template
            if (str_starts_with($template->name, 'temp_')) {
                $template->delete();
            }

            return $result['failed_inserts'] > 0 ? 1 : 0;
        } catch (\Exception $e) {
            $this->error("Generation failed: {$e->getMessage()}");
            return 1;
        }
    }
}
