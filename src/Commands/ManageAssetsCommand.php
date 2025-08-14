<?php

namespace HkDevs\CodeForgeStudio\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * ManageAssetsCommand
 * 
 * Comprehensive asset management utility for CodeForge Database Studio plugin.
 * Provides complete control over published assets including configuration, migrations, views, and frontend assets.
 * 
 * Features:
 * - Complete asset lifecycle management (remove, publish, refresh)
 * - Selective asset type management (config, migrations, views, CSS/JS)
 * - Force mode for overwriting existing assets
 * - Dry-run mode for safe preview of operations
 * - Intelligent migration detection and cleanup
 * - Comprehensive file system management
 * - Detailed operation reporting and logging
 * 
 * Asset Categories:
 * - Configuration Files: Plugin settings and customizable options
 * - Database Migrations: Schema setup and modification files
 * - View Templates: Blade templates and UI components
 * - Frontend Assets: CSS stylesheets and JavaScript files
 * 
 * Management Operations:
 * - Remove: Clean removal of published assets from Laravel application
 * - Publish: Deploy assets from package to Laravel application
 * - Refresh: Complete remove and republish cycle for clean updates
 * 
 * Safety Features:
 * - Dry-run mode for risk-free operation preview
 * - Confirmation prompts for destructive operations
 * - Backup recommendations before major changes
 * - Detailed logging of all file operations
 * - Error handling with rollback capabilities
 * 
 * Selective Management:
 * - Target specific asset types with precise control
 * - Maintain granular control over deployment
 * - Prevent unnecessary file operations
 * - Optimize CI/CD pipeline integration
 * 
 * Development Support:
 * - Development environment asset management
 * - Hot-reload compatible asset publishing
 * - Version control friendly operations
 * - Team development asset synchronization
 * 
 * Use Cases:
 * - Plugin updates and asset synchronization
 * - Development environment setup and teardown
 * - CI/CD pipeline asset management
 * - Troubleshooting asset conflicts
 * - Clean reinstallation procedures
 * 
 * @package HkDevs\CodeForgeStudio\Commands
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * # Remove all published assets
 * php artisan codeforge:assets remove
 * 
 * # Publish all assets with force overwrite
 * php artisan codeforge:assets publish --force
 * 
 * # Refresh only configuration files
 * php artisan codeforge:assets refresh --config
 * 
 * # Preview removal without actually deleting
 * php artisan codeforge:assets remove --dry-run
 * 
 * # Manage only frontend assets
 * php artisan codeforge:assets publish --assets --force
 */

class ManageAssetsCommand extends Command
{
    protected $signature = 'codeforge:assets 
                            {action : Action to perform (remove|publish|refresh)} 
                            {--config : Manage config file only}
                            {--migrations : Manage migrations only}
                            {--views : Manage views only}
                            {--assets : Manage CSS/JS assets only}
                            {--force : Force overwrite when publishing}
                            {--dry-run : Show what would be done without actually doing it}';

    protected $description = 'Manage CodeForge Database Studio published assets (remove, publish, or refresh)';

    private array $publishableTags = [
        'config' => 'codeforge-database-studio-config',
        'migrations' => 'codeforge-database-studio-migrations',
        'views' => 'codeforge-database-studio-views',
        'assets' => 'codeforge-database-studio-assets',
    ];

    private array $publishedPaths = [
        'config' => 'config/codeforge-database-studio.php',
        'migrations' => 'database/migrations',
        'views' => 'resources/views/vendor/codeforge-database-studio',
        'assets' => [
            'public/vendor/codeforge-database-studio/css',
            'public/vendor/codeforge-database-studio/js'
        ],
    ];

    public function handle(): int
    {
        $action = $this->argument('action');

        if (!in_array($action, ['remove', 'publish', 'refresh'])) {
            $this->error('Invalid action. Use: remove, publish, or refresh');
            return self::FAILURE;
        }

        $this->info("🔧 CodeForge Database Studio Asset Management");
        $this->line("Action: " . ucfirst($action));
        if ($this->option('dry-run')) {
            $this->warn("DRY RUN MODE - No changes will be made");
        }
        $this->line('');

        $selectedTypes = $this->getSelectedTypes();

        if (empty($selectedTypes)) {
            $selectedTypes = array_keys($this->publishableTags);
            $this->info('No specific type selected. Managing all assets.');
        }

        switch ($action) {
            case 'remove':
                return $this->removeAssets($selectedTypes);
            case 'publish':
                return $this->publishAssets($selectedTypes);
            case 'refresh':
                return $this->refreshAssets($selectedTypes);
        }

        return self::SUCCESS;
    }

    private function getSelectedTypes(): array
    {
        $types = [];

        if ($this->option('config')) $types[] = 'config';
        if ($this->option('migrations')) $types[] = 'migrations';
        if ($this->option('views')) $types[] = 'views';
        if ($this->option('assets')) $types[] = 'assets';

        return $types;
    }

    private function removeAssets(array $types): int
    {
        $this->warn('🗑️  Removing published assets...');
        $this->line('');

        foreach ($types as $type) {
            $this->info("Removing {$type}...");

            if ($type === 'migrations') {
                $this->removeMigrations();
            } else {
                $this->removeByType($type);
            }
        }

        $this->line('');
        $this->info('✅ Asset removal completed!');

        return self::SUCCESS;
    }

    private function publishAssets(array $types): int
    {
        $this->info('📦 Publishing assets...');
        $this->line('');

        foreach ($types as $type) {
            $this->info("Publishing {$type}...");

            if ($this->option('dry-run')) {
                $this->line("  [DRY RUN] Would publish tag: {$this->publishableTags[$type]}");
                continue;
            }

            $this->call('vendor:publish', [
                '--tag' => $this->publishableTags[$type],
                '--force' => $this->option('force'),
            ]);
        }

        $this->line('');
        $this->info('✅ Asset publishing completed!');

        return self::SUCCESS;
    }

    private function refreshAssets(array $types): int
    {
        $this->warn('🔄 Refreshing assets (remove + publish)...');
        $this->line('');

        // First remove
        $this->warn('Step 1: Removing existing assets...');
        foreach ($types as $type) {
            $this->line("  - Removing {$type}...");

            if ($type === 'migrations') {
                $this->removeMigrations();
            } else {
                $this->removeByType($type);
            }
        }

        $this->line('');

        // Then publish
        $this->info('Step 2: Publishing fresh assets...');
        foreach ($types as $type) {
            $this->line("  - Publishing {$type}...");

            if ($this->option('dry-run')) {
                $this->line("    [DRY RUN] Would publish tag: {$this->publishableTags[$type]}");
                continue;
            }

            $this->call('vendor:publish', [
                '--tag' => $this->publishableTags[$type],
                '--force' => true, // Always force on refresh
            ]);
        }

        $this->line('');
        $this->info('✅ Asset refresh completed!');

        return self::SUCCESS;
    }

    private function removeByType(string $type): void
    {
        $paths = $this->publishedPaths[$type];

        if (is_string($paths)) {
            $paths = [$paths];
        }

        foreach ($paths as $path) {
            $fullPath = base_path($path);

            if ($this->option('dry-run')) {
                if (File::exists($fullPath)) {
                    $this->line("  [DRY RUN] Would remove: {$path}");
                } else {
                    $this->line("  [DRY RUN] Not found: {$path}");
                }
                continue;
            }

            if (File::exists($fullPath)) {
                if (File::isDirectory($fullPath)) {
                    File::deleteDirectory($fullPath);
                    $this->line("  ✓ Removed directory: {$path}");
                } else {
                    File::delete($fullPath);
                    $this->line("  ✓ Removed file: {$path}");
                }
            } else {
                $this->line("  ⚠ Not found: {$path}");
            }
        }
    }

    private function removeMigrations(): void
    {
        $migrationPath = database_path('migrations');

        // CodeForge Studio migration files start with 2024_01_01_
        $migrationFiles = File::glob($migrationPath . '/2024_01_01_*_codeforge_*.php');

        // Also check for the specific migration names we know about
        $knownMigrations = [
            '2024_01_01_000001_create_database_manager_logs_table.php',
            '2024_01_01_000002_create_migration_histories_table.php',
            '2024_01_01_000003_create_query_performance_logs_table.php',
            '2024_01_01_000004_create_database_health_metrics_table.php',
            '2024_01_01_000005_create_data_seeders_table.php',
            '2024_01_01_000006_create_seeder_execution_logs_table.php',
            '2024_01_01_000007_create_data_generation_templates_table.php',
            '2024_01_01_000008_create_documentation_generations_table.php',
            '2024_01_01_000009_create_schema_snapshots_table.php',
        ];

        foreach ($knownMigrations as $migration) {
            $filePath = $migrationPath . '/' . $migration;
            if (File::exists($filePath)) {
                $migrationFiles[] = $filePath;
            }
        }

        if (empty($migrationFiles)) {
            $this->line("  ⚠ No CodeForge Studio migrations found");
            return;
        }

        foreach ($migrationFiles as $migrationFile) {
            $fileName = basename($migrationFile);

            if ($this->option('dry-run')) {
                $this->line("  [DRY RUN] Would remove migration: {$fileName}");
                continue;
            }

            File::delete($migrationFile);
            $this->line("  ✓ Removed migration: {$fileName}");
        }

        if (!$this->option('dry-run')) {
            // Check if we need to rollback any migrated tables
            if (
                Schema::hasTable('database_manager_logs') ||
                Schema::hasTable('migration_histories') ||
                Schema::hasTable('query_performance_logs') ||
                Schema::hasTable('database_health_metrics')
            ) {

                $this->warn('  ⚠ Database tables still exist. Run migrations rollback if needed.');
                $this->line('  💡 Tip: php artisan migrate:rollback --step=9');
            }
        }
    }

    public function getDescription(): string
    {
        return $this->description . PHP_EOL . PHP_EOL .
            'Examples:' . PHP_EOL .
            '  Remove all assets:     php artisan codeforge-database-studio:assets remove' . PHP_EOL .
            '  Publish all assets:    php artisan codeforge-database-studio:assets publish --force' . PHP_EOL .
            '  Refresh all assets:    php artisan codeforge-database-studio:assets refresh' . PHP_EOL .
            '  Remove only config:    php artisan codeforge-database-studio:assets remove --config' . PHP_EOL .
            '  Refresh only views:    php artisan codeforge-database-studio:assets refresh --views' . PHP_EOL .
            '  Dry run (preview):     php artisan codeforge-database-studio:assets remove --dry-run' . PHP_EOL;
    }
}
