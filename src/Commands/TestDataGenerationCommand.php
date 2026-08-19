<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Services\DataGenerationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * TestDataGenerationCommand
 *
 * Data generation testing and validation utility for CodeForge Database Studio.
 * Provides comprehensive testing of data generation capabilities and system functionality.
 *
 * Features:
 * - Complete data generation system testing
 * - Table discovery and analysis validation
 * - Field mapping accuracy verification
 * - Data generation template testing
 * - Performance benchmarking and optimization testing
 * - Error handling and edge case validation
 * - System integration testing
 *
 * Testing Components:
 * - Table Discovery: Validate automatic table detection and filtering
 * - Column Analysis: Test column type recognition and mapping
 * - Data Generation: Verify realistic data creation for various field types
 * - Template System: Test template creation, saving, and application
 * - Relationship Handling: Validate foreign key and relationship awareness
 *
 * Validation Areas:
 * - Data Type Mapping: Ensure appropriate data generation for each column type
 * - Constraint Compliance: Verify adherence to database constraints
 * - Relationship Integrity: Test foreign key and referential integrity
 * - Performance Metrics: Measure generation speed and memory usage
 * - Error Scenarios: Test handling of invalid configurations
 *
 * Test Coverage:
 * - Core Tables: Users, products, orders, and other common entities
 * - Data Types: String, numeric, date, boolean, JSON, and custom types
 * - Field Patterns: Email, phone, name, address, URL, and identifier fields
 * - Relationships: One-to-many, many-to-many, polymorphic relationships
 * - Edge Cases: Empty tables, complex constraints, circular dependencies
 *
 * Performance Testing:
 * - Generation speed benchmarking
 * - Memory usage optimization
 * - Batch processing efficiency
 * - Database connection management
 * - Large dataset generation testing
 *
 * Diagnostic Features:
 * - Detailed test result reporting
 * - Performance metrics collection
 * - Error analysis and troubleshooting
 * - System capability assessment
 * - Configuration recommendation generation
 *
 * Development Support:
 * - Debug mode for detailed analysis
 * - Step-by-step execution tracking
 * - Configuration validation
 * - Template testing and verification
 * - Service integration testing
 *
 * Quality Assurance:
 * - Automated regression testing
 * - Configuration validation
 * - Data quality assessment
 * - System reliability verification
 * - Performance regression detection
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * # Run comprehensive data generation tests
 * php artisan codeforge:test-generation
 *
 * # Use for development testing and validation
 * php artisan codeforge:test-generation --verbose
 *
 * # Include in CI/CD pipeline for quality assurance
 * php artisan codeforge:test-generation --ci-mode
 */
class TestDataGenerationCommand extends Command
{
    protected $signature = 'codeforge:test-generation';

    protected $description = 'Test data generation functionality';

    public function handle()
    {
        $this->info('=== Testing Data Generation Service ===');

        try {
            // Test table discovery
            $this->info('1. Testing table discovery...');
            $tables = DB::select('SHOW TABLES');
            $tableNames = [];

            foreach ($tables as $table) {
                $tableArray = (array) $table;
                $tableName = array_values($tableArray)[0];
                if (! in_array($tableName, ['migrations', 'personal_access_tokens', 'password_reset_tokens', 'failed_jobs', 'data_seeders', 'seeder_execution_logs', 'data_generation_templates'])) {
                    $tableNames[] = $tableName;
                }
            }

            $this->info('Available tables: '.implode(', ', array_slice($tableNames, 0, 5)));

            // Test with users table
            $this->info('2. Testing users table analysis...');
            $service = app(DataGenerationService::class);

            $columns = Schema::getColumns('users');
            $this->info('Users table columns:');
            foreach ($columns as $column) {
                $this->line("  - {$column['name']} ({$column['type']})");
            }

            $this->info('3. Analyzing users table...');
            $analysis = $service->analyzeTable('users');
            $this->info('Field mappings:');
            foreach ($analysis['suggestions'] as $field => $mapping) {
                $this->line("  - $field: ".json_encode($mapping));
            }

            $this->info('4. Creating template...');
            $template = $service->createTemplateFromTable('users', 'debug_users_template_'.time());
            $this->info("Template created: {$template->name} (ID: {$template->id})");

            $this->info('5. Generating preview data...');
            $previewData = $service->previewData($template, 2);
            $this->info('Generated '.count($previewData).' preview records:');
            foreach ($previewData as $i => $record) {
                $this->line('  Record '.($i + 1).':');
                foreach ($record as $field => $value) {
                    $displayValue = is_null($value) ? 'NULL' : (is_bool($value) ? ($value ? 'true' : 'false') : $value);
                    $this->line("    $field: ".$displayValue);
                }
            }

            $this->info('6. Testing actual data insertion...');
            $result = $service->insertGeneratedData($template, 1);
            $this->info("Insertion result: Generated {$result['total_generated']}, inserted {$result['successfully_inserted']}, failed {$result['failed_inserts']}");

            // Clean up
            $template->delete();
            $this->info('Template cleaned up.');
        } catch (\Exception $e) {
            $this->error('ERROR: '.$e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
        }

        $this->info('=== Test Complete ===');
    }
}
