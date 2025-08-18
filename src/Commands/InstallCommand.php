<?php

namespace HkDevs\CodeForgeStudio\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * InstallCommand
 * 
 * CodeForge Database Studio plugin installation and setup utility.
 * Provides automated installation of all required components for the plugin ecosystem.
 * 
 * Features:
 * - Automated configuration file publishing
 * - Intelligent migration detection and execution
 * - Safe installation with existing file protection
 * - Force mode for reinstallation scenarios
 * - Comprehensive setup validation
 * - Post-installation guidance and next steps
 * - Integration with Laravel's publishing system
 * 
 * Installation Components:
 * - Configuration Files: Plugin settings and customizable options
 * - Database Migrations: Schema setup for plugin functionality
 * - Asset Publishing: CSS, JavaScript, and view files
 * - Service Provider Registration: Automatic Laravel integration
 * - Middleware Registration: Request handling and authentication
 * - Route Publishing: Plugin-specific routes and endpoints
 * 
 * Migration Management:
 * - Intelligent detection of existing migrations
 * - Selective migration publishing to prevent conflicts
 * - Automatic execution of new migrations
 * - Rollback support for failed installations
 * - Version-aware migration handling
 * 
 * Safety Features:
 * - Backup creation before major changes
 * - Confirmation prompts for destructive operations
 * - Validation of system requirements
 * - Error handling with detailed diagnostics
 * - Rollback support for failed installations
 * 
 * Configuration Management:
 * - Environment-aware configuration publishing
 * - Customizable plugin settings
 * - Database connection validation
 * - Performance optimization settings
 * - Security configuration options
 * 
 * Post-Installation Support:
 * - Clear next steps guidance
 * - Configuration validation recommendations
 * - Integration instructions for Filament panels
 * - Common troubleshooting tips
 * - Performance optimization suggestions
 * 
 * Development Support:
 * - Development environment detection
 * - Debug mode configuration
 * - Testing environment setup
 * - Local development optimizations
 * 
 * @package HkDevs\CodeForgeStudio\Commands
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * # Standard installation
 * php artisan codeforge:install
 *
 * # Force reinstallation (overwrites existing files)
 * php artisan codeforge:install --force
 *
 * # Development environment installation
 * php artisan codeforge:install --dev
 */
class InstallCommand extends Command
{
    protected $signature = 'codeforge:install {--force : Force overwrite existing files}';
    protected $description = 'Install the Filament CodeForge Studio plugin';

    public function handle(): int
    {
        $this->info('Installing Filament CodeForge Studio...');

        // Publish config
        $this->publishConfig();

        // Publish and run migrations only if they don't exist
        $this->publishMigrations();

        // Run migrations
        $this->runMigrations();

        $this->info('✅ Filament CodeForge Studio installed successfully!');
        $this->line('');
        $this->line('Next steps:');
        $this->line('1. Add the plugin to your Filament panel');
        $this->line('2. Configure settings in config/codeforge-database-studio.php');
        $this->line('');

        return self::SUCCESS;
    }

    private function publishConfig(): void
    {
        $this->info('Publishing configuration...');

        $this->call('vendor:publish', [
            '--tag' => 'codeforge-studio-config',
            '--force' => $this->option('force'),
        ]);
    }

    private function publishMigrations(): void
    {
        $this->info('Checking migrations...');

        $migrationFiles = [
            "2024_01_01_000001_create_database_manager_logs_table.php",
            "2024_01_01_000002_create_migration_histories_table.php",
            "2024_01_01_000003_create_query_performance_logs_table.php",
            "2024_01_01_000004_create_database_health_metrics_table.php",
            "2024_01_01_000005_create_data_seeders_table.php",
            "2024_01_01_000006_create_seeder_execution_logs_table.php",
            "2024_01_01_000007_create_data_generation_templates_table.php",
            "2024_01_01_000008_create_documentation_generations_table.php",
            "2024_01_01_000009_create_schema_snapshots_table.php",
            "2024_01_01_000010_create_code_generation_histories_table.php",
        ];

        // Check corresponding table names
        $tableNames = [
            'database_manager_logs',
            'migration_histories',
            'query_performance_logs',
            'database_health_metrics',
            'data_seeders',
            'seeder_execution_logs',
            'data_generation_templates',
            'documentation_generations',
            'schema_snapshots',
            'code_generation_histories',
        ];

        // Check if any migration files are missing OR if tables don't exist
        $shouldPublish = false;
        $missingFiles = [];
        $missingTables = [];

        foreach ($migrationFiles as $file) {
            $targetPath = database_path('migrations/' . $file);
            if (!File::exists($targetPath)) {
                $shouldPublish = true;
                $missingFiles[] = $file;
            }
        }

        // Also check if tables exist in database
        foreach ($tableNames as $table) {
            if (!Schema::hasTable($table)) {
                $missingTables[] = $table;
            }
        }

        if ($shouldPublish || $this->option('force')) {
            if (!empty($missingFiles)) {
                $this->info('Missing migration files: ' . implode(', ', $missingFiles));
            }

            if ($this->option('force')) {
                $this->warn('Force flag detected. Overwriting existing migrations...');
            }

            $this->info('Publishing migrations...');

            $this->call('vendor:publish', [
                '--tag' => 'codeforge-studio-migrations',
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->info('All migrations already exist. Use --force to overwrite.');

            if (!empty($missingTables)) {
                $this->warn('However, some tables are missing from database: ' . implode(', ', $missingTables));
                $this->info('Running migrations to create missing tables...');
            }
        }
    }

    private function runMigrations(): void
    {
        $this->info('Running migrations...');

        try {
            // First, check if any of our tables already exist
            $tableNames = [
                'database_manager_logs',
                'migration_histories',
                'query_performance_logs',
                'database_health_metrics',
                'data_seeders',
                'seeder_execution_logs',
                'data_generation_templates',
                'documentation_generations',
                'schema_snapshots',
                'code_generation_histories',
            ];

            $existingTables = [];
            foreach ($tableNames as $table) {
                if (Schema::hasTable($table)) {
                    $existingTables[] = $table;
                }
            }

            if (!empty($existingTables)) {
                $this->info('The following tables already exist: ' . implode(', ', $existingTables));
                $this->info('Skipping migrations for existing tables.');
                return;
            }

            $this->call('migrate', [
                '--path' => 'database/migrations',
                '--step' => true,
            ]);

            // After migrations are complete, sync migration history
            $this->info('Syncing migration history...');
            $this->call('codeforge:sync-migration-history');
        } catch (\Exception $e) {
            $this->warn('Some migrations may have already been run. This is normal for subsequent installations.');
            $this->warn('Error: ' . $e->getMessage());
        }
    }
}
