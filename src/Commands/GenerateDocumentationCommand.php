<?php

namespace HkDevs\CodeForgeStudio\Commands;

use Illuminate\Console\Command;
use HkDevs\CodeForgeStudio\Services\DocumentationGenerationService;
use HkDevs\CodeForgeStudio\Models\DocumentationGeneration;

/**
 * GenerateDocumentationCommand
 * 
 * Comprehensive database documentation generation utility for CodeForge Database Studio.
 * Creates professional, multi-format documentation for database schemas, models, and relationships.
 * 
 * Features:
 * - Multi-format output support (Markdown, HTML, PDF)
 * - Flexible documentation scope (full schema, selected tables, models only)
 * - Custom titles and descriptions for branded documentation
 * - Automatic table selection and filtering
 * - Downloadable documentation files with custom naming
 * - Integration with Laravel models and Eloquent relationships
 * - Professional formatting with consistent styling
 * 
 * Output Formats:
 * - Markdown: Developer-friendly format for version control and collaboration
 * - HTML: Web-ready documentation with navigation and styling
 * - PDF: Print-ready professional documentation for stakeholders
 * 
 * Documentation Scopes:
 * - Full Schema: Complete database documentation including all tables and relationships
 * - Selected Tables: Targeted documentation for specific table subsets
 * - Single Table: Detailed documentation for individual table analysis
 * - Models Only: Laravel model-focused documentation with relationships
 * 
 * Content Generation:
 * - Table structure with column definitions and constraints
 * - Index and key information including performance implications
 * - Relationship mapping with foreign key documentation
 * - Laravel model integration and Eloquent relationship detection
 * - Data type analysis and validation rule documentation
 * - Sample data and usage examples where applicable
 * 
 * Customization Options:
 * - Custom document titles and descriptions
 * - Branded headers and footers
 * - Table filtering and selection
 * - Output filename customization
 * - Automatic file organization and naming
 * 
 * Integration Features:
 * - Compatible with CI/CD pipelines for automated documentation
 * - Version control friendly output formats
 * - Exportable for external documentation systems
 * - Scheduled generation support for up-to-date documentation
 * 
 * Use Cases:
 * - API documentation generation
 * - Database schema documentation for teams
 * - Compliance and audit documentation
 * - Onboarding materials for new developers
 * - System architecture documentation
 * 
 * @package HkDevs\CodeForgeStudio\Commands
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * # Generate full schema documentation in Markdown
 * php artisan codeforge:generate-docs --format=markdown --scope=full_schema
 * 
 * # Create PDF documentation for specific tables
 * php artisan codeforge:generate-docs --format=pdf --scope=selected_tables --tables=users,orders,products
 * 
 * # Generate HTML documentation with custom title
 * php artisan codeforge:generate-docs --format=html --title="E-commerce Database Schema" --auto-download
 * 
 * # Create model-focused documentation
 * php artisan codeforge:generate-docs --scope=models_only --output=laravel_models.md
 */
class GenerateDocumentationCommand extends Command
{
    protected $signature = 'codeforge:generate-docs 
                           {--format=markdown : Output format (markdown, html, pdf)}
                           {--scope=full_schema : Documentation scope}
                           {--title= : Custom title for the documentation}
                           {--description= : Custom description}
                           {--tables=* : Specific tables to include (for selected_tables scope)}
                           {--output= : Custom output filename}
                           {--auto-download : Automatically download the generated file}';

    protected $description = 'Generate database documentation';

    public function handle(): int
    {
        $this->info('Starting database documentation generation...');

        try {
            // Validate format
            $format = $this->option('format');
            if (!in_array($format, ['markdown', 'html', 'pdf'])) {
                $this->error('Invalid format. Use: markdown, html, or pdf');
                return self::FAILURE;
            }

            // Validate scope
            $scope = $this->option('scope');
            if (!in_array($scope, ['full_schema', 'selected_tables', 'single_table', 'models_only'])) {
                $this->error('Invalid scope. Use: full_schema, selected_tables, single_table, or models_only');
                return self::FAILURE;
            }

            // Prepare data
            $title = $this->option('title') ?: 'Database Documentation - ' . now()->format('Y-m-d H:i:s');
            $description = $this->option('description') ?: 'Auto-generated database documentation';
            $tables = $this->option('tables');

            // Validate tables for specific scopes
            if (in_array($scope, ['selected_tables', 'single_table']) && empty($tables)) {
                $this->error('Tables must be specified for selected_tables or single_table scope');
                return self::FAILURE;
            }

            $this->line('Creating documentation generation record...');

            // Create documentation generation record
            $generation = DocumentationGeneration::create([
                'title' => $title,
                'description' => $description,
                'format' => $format,
                'scope' => $scope,
                'included_tables' => $scope === 'single_table' ? [$tables[0]] : $tables,
                'version' => '1.0.0',
            ]);

            $this->info("Created generation record with ID: {$generation->id}");

            // Generate the documentation
            $this->line('Generating documentation...');
            $service = app(DocumentationGenerationService::class, ['generation' => $generation]);
            $service->generate();

            $this->info('✅ Documentation generated successfully!');
            $this->line("Title: {$generation->title}");
            $this->line("Format: {$generation->format}");
            $this->line("File Size: {$generation->formatted_file_size}");

            // Show download info
            if ($generation->status === 'completed') {
                $downloadUrl = route('admin.database-manager.documentation.download', $generation);
                $this->line("Download URL: {$downloadUrl}");

                if ($this->option('auto-download')) {
                    $this->downloadFile($generation);
                }
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Documentation generation failed: ' . $e->getMessage());

            if ($this->option('verbose')) {
                $this->line($e->getTraceAsString());
            }

            return self::FAILURE;
        }
    }

    protected function downloadFile(DocumentationGeneration $generation): void
    {
        $outputPath = $this->option('output') ?: $this->generateOutputPath($generation);

        try {
            $content = \Illuminate\Support\Facades\Storage::disk('local')->get($generation->file_path);
            file_put_contents($outputPath, $content);

            $this->info("✅ File downloaded to: {$outputPath}");
        } catch (\Exception $e) {
            $this->warn("Could not download file: {$e->getMessage()}");
        }
    }

    protected function generateOutputPath(DocumentationGeneration $generation): string
    {
        $title = \Illuminate\Support\Str::slug($generation->title);
        $timestamp = now()->format('Y-m-d_H-i-s');
        $extension = match ($generation->format) {
            'pdf' => 'pdf',
            'html' => 'html',
            default => 'md'
        };

        return "{$title}_{$timestamp}.{$extension}";
    }
}
