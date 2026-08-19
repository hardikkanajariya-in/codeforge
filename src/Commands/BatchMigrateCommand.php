<?php

namespace HkDevs\CodeForgeStudio\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * BatchMigrateCommand
 *
 * Execute each migration file in its own separate batch for granular control.
 * Each migration gets its own batch number in the migrations table, allowing
 * for precise rollback control and better migration management.
 *
 * Features:
 * - Each migration file runs in its own batch (separate batch number)
 * - Sequential execution with proper timestamp ordering
 * - Dry-run mode for safe preview
 * - Progress tracking and error handling
 * - Production safety checks
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * # Execute all pending migrations (each in separate batch)
 * php artisan codeforge:batch-migrate
 *
 * # Preview migrations without executing
 * php artisan codeforge:batch-migrate --dry-run
 *
 * # Force execution in production
 * php artisan codeforge:batch-migrate --force
 *
 * # Fresh migration with each migration in separate batch
 * php artisan codeforge:batch-migrate --fresh
 */
class BatchMigrateCommand extends Command
{
    protected $signature = 'codeforge:batch-migrate 
                            {--dry-run : Preview migrations without executing}
                            {--force : Force migrations to run in production}
                            {--fresh : Drop all tables and re-run all migrations in separate batches}
                            {--path= : Specify custom migration path}';

    protected $description = 'Run each migration file in its own separate batch';

    private array $pendingMigrations = [];

    private int $executedCount = 0;

    private array $errors = [];

    public function handle(): int
    {
        $this->info('🔧 CodeForge Database Studio - Batch Migration Runner');
        $this->info('Each migration will be executed in its own separate batch');
        $this->line('========================================================');

        if ($this->option('fresh')) {
            return $this->runFreshBatchMigrations();
        }

        return $this->runBatchMigrations();
    }

    private function runBatchMigrations(): int
    {
        $this->info('📋 Scanning for pending migrations...');

        // Get migration path
        $migrationPath = $this->option('path') ?: database_path('migrations');

        if (! File::exists($migrationPath)) {
            $this->error("Migration path does not exist: {$migrationPath}");

            return self::FAILURE;
        }

        // Get pending migrations
        $this->pendingMigrations = $this->getPendingMigrations($migrationPath);
        $totalPending = count($this->pendingMigrations);

        if ($totalPending === 0) {
            $this->info('✅ All migrations are up to date!');

            return self::SUCCESS;
        }

        $this->info("Found {$totalPending} pending migrations");

        // Show preview
        $this->showMigrationPreview();

        // Dry run mode
        if ($this->option('dry-run')) {
            $this->warn('🔍 DRY RUN MODE - No migrations will be executed');

            return self::SUCCESS;
        }

        // Production safety check
        if (app()->environment('production') && ! $this->option('force')) {
            if (! $this->confirm('You are in PRODUCTION environment. Are you sure you want to run migrations?')) {
                $this->info('Migration cancelled.');

                return self::SUCCESS;
            }
        }

        // Execute migrations one by one in separate batches
        return $this->executeBatchMigrations();
    }

    private function runFreshBatchMigrations(): int
    {
        $this->warn('🆕 Fresh Batch Migration (will drop all tables and re-run each migration in separate batch)');

        // Get migration path
        $migrationPath = $this->option('path') ?: database_path('migrations');

        if (! File::exists($migrationPath)) {
            $this->error("Migration path does not exist: {$migrationPath}");

            return self::FAILURE;
        }

        // Get all migration files (not just pending ones)
        $this->pendingMigrations = $this->getAllMigrations($migrationPath);
        $totalMigrations = count($this->pendingMigrations);

        if ($totalMigrations === 0) {
            $this->warn('No migration files found.');

            return self::SUCCESS;
        }

        $this->info("Found {$totalMigrations} migration files to execute in fresh mode");

        // Show preview
        $this->showFreshMigrationPreview();

        // Dry run mode
        if ($this->option('dry-run')) {
            $this->warn('🔍 DRY RUN MODE - No fresh migration will be executed');

            return self::SUCCESS;
        }

        // Production safety check
        if (app()->environment('production') && ! $this->option('force')) {
            if (! $this->confirm('You are in PRODUCTION environment. This will DROP ALL TABLES! Are you sure?')) {
                $this->info('Fresh migration cancelled.');

                return self::SUCCESS;
            }
        }

        // Additional confirmation for destructive operation
        if (! $this->confirm('This will DROP ALL TABLES and re-run all migrations. Continue?')) {
            $this->info('Fresh migration cancelled.');

            return self::SUCCESS;
        }

        // Execute fresh migration with separate batches
        return $this->executeFreshBatchMigrations();
    }

    private function getPendingMigrations(string $migrationPath): array
    {
        // Get all migration files
        $allFiles = File::glob($migrationPath.'/*.php');

        // Sort files by name (timestamp based)
        usort($allFiles, function ($a, $b) {
            return basename($a) <=> basename($b);
        });

        $allMigrations = array_map(function ($file) {
            return pathinfo(basename($file), PATHINFO_FILENAME);
        }, $allFiles);

        // Get already executed migrations
        $executedMigrations = [];
        if (Schema::hasTable('migrations')) {
            $executedMigrations = DB::table('migrations')
                ->pluck('migration')
                ->toArray();
        }

        // Return pending migrations with full file paths
        $pendingMigrations = [];
        foreach ($allFiles as $file) {
            $migrationName = pathinfo(basename($file), PATHINFO_FILENAME);
            if (! in_array($migrationName, $executedMigrations)) {
                $pendingMigrations[] = [
                    'file' => $file,
                    'name' => $migrationName,
                    'basename' => basename($file),
                ];
            }
        }

        return $pendingMigrations;
    }

    private function getAllMigrations(string $migrationPath): array
    {
        // Get all migration files
        $allFiles = File::glob($migrationPath.'/*.php');

        // Sort files by name (timestamp based)
        usort($allFiles, function ($a, $b) {
            return basename($a) <=> basename($b);
        });

        // Return all migrations with full file paths
        $allMigrations = [];
        foreach ($allFiles as $file) {
            $migrationName = pathinfo(basename($file), PATHINFO_FILENAME);
            $allMigrations[] = [
                'file' => $file,
                'name' => $migrationName,
                'basename' => basename($file),
            ];
        }

        return $allMigrations;
    }

    private function showFreshMigrationPreview(): void
    {
        $this->line('');
        $this->info('📋 Fresh Migration - All migrations to be executed (each in separate batch):');
        $this->line('===========================================================================');

        foreach ($this->pendingMigrations as $index => $migration) {
            $batchNumber = $index + 1;
            $this->line("  Batch {$batchNumber}: {$migration['basename']}");
        }

        $this->line('');
        $this->warn('⚠️  This will DROP ALL TABLES first, then run each migration in its own batch.');
        $this->line('');
    }

    private function executeFreshBatchMigrations(): int
    {
        $this->info('🗑️  Dropping all tables...');

        try {
            // Drop all tables using Laravel's fresh command but without migrations
            Artisan::call('db:wipe', [
                '--force' => $this->option('force') ?: false,
            ]);
            $this->info('✅ All tables dropped successfully');
        } catch (\Exception $e) {
            $this->error("Failed to drop tables: {$e->getMessage()}");

            return self::FAILURE;
        }

        $this->info('🚀 Starting fresh batch migrations (each migration in separate batch)...');
        $this->line('');

        $progressBar = $this->output->createProgressBar(count($this->pendingMigrations));
        $progressBar->setFormat('detailed');
        $progressBar->start();

        foreach ($this->pendingMigrations as $migration) {
            try {
                $this->executeSingleMigrationInBatch($migration);
                $this->executedCount++;
                $progressBar->advance();

                // Small delay to ensure batch numbers are sequential
                usleep(100000); // 0.1 second

            } catch (\Exception $e) {
                $progressBar->finish();
                $this->line('');
                $this->error("Migration failed: {$migration['basename']}");
                $this->error("Error: {$e->getMessage()}");

                $this->errors[] = [
                    'migration' => $migration['basename'],
                    'error' => $e->getMessage(),
                ];

                if (! $this->confirm('Continue with remaining migrations?')) {
                    return $this->showFreshResults(self::FAILURE);
                }
            }
        }

        $progressBar->finish();
        $this->line('');

        return $this->showFreshResults();
    }

    private function showFreshResults(int $exitCode = self::SUCCESS): int
    {
        $this->line('');
        $this->info('📊 Fresh Batch Migration Results:');
        $this->line('==================================');
        $this->info('Total migrations found: '.count($this->pendingMigrations));
        $this->info("Migrations executed: {$this->executedCount}");

        if (! empty($this->errors)) {
            $this->error('Migrations with errors: '.count($this->errors));

            $this->line('');
            $this->error('❌ Errors encountered:');
            foreach ($this->errors as $error) {
                $this->line("  • {$error['migration']}: {$error['error']}");
            }
        }

        $this->line('');

        if ($exitCode === self::SUCCESS && empty($this->errors)) {
            $this->info('✅ Fresh batch migration completed successfully!');
            $this->info('All tables were dropped and each migration was executed in its own separate batch.');
        } elseif ($this->executedCount > 0) {
            $this->warn('⚠️  Fresh migration process completed with some issues');
        } else {
            $this->error('❌ Fresh migration process failed');
        }

        return $exitCode;
    }

    private function showMigrationPreview(): void
    {
        $this->line('');
        $this->info('📋 Migrations to be executed (each in separate batch):');
        $this->line('======================================================');

        foreach ($this->pendingMigrations as $index => $migration) {
            $batchNumber = $this->getNextBatchNumber() + $index;
            $this->line("  Batch {$batchNumber}: {$migration['basename']}");
        }

        $this->line('');
    }

    private function getNextBatchNumber(): int
    {
        if (! Schema::hasTable('migrations')) {
            return 1;
        }

        return DB::table('migrations')->max('batch') + 1;
    }

    private function executeBatchMigrations(): int
    {
        $this->info('🚀 Starting batch migrations (each migration in separate batch)...');
        $this->line('');

        $progressBar = $this->output->createProgressBar(count($this->pendingMigrations));
        $progressBar->setFormat('detailed');
        $progressBar->start();

        foreach ($this->pendingMigrations as $migration) {
            try {
                $this->executeSingleMigrationInBatch($migration);
                $this->executedCount++;
                $progressBar->advance();

                // Small delay to ensure batch numbers are sequential
                usleep(100000); // 0.1 second

            } catch (\Exception $e) {
                $progressBar->finish();
                $this->line('');
                $this->error("Migration failed: {$migration['basename']}");
                $this->error("Error: {$e->getMessage()}");

                $this->errors[] = [
                    'migration' => $migration['basename'],
                    'error' => $e->getMessage(),
                ];

                if (! $this->confirm('Continue with remaining migrations?')) {
                    return $this->showResults(self::FAILURE);
                }
            }
        }

        $progressBar->finish();
        $this->line('');

        return $this->showResults();
    }

    private function executeSingleMigrationInBatch(array $migration): void
    {
        $migrationFile = $migration['file'];
        $relativePath = 'database/migrations/'.$migration['basename'];

        // Execute the migration in its own batch using Laravel's migrate command
        Artisan::call('migrate', [
            '--path' => $relativePath,
            '--force' => $this->option('force') ?: false,
        ]);

        $output = Artisan::output();

        // Check for errors in output
        if (str_contains($output, 'ERROR') || str_contains($output, 'Exception')) {
            throw new \Exception("Migration execution failed: {$output}");
        }

        // Verify the migration was actually executed
        $migrationName = $migration['name'];
        $wasExecuted = DB::table('migrations')
            ->where('migration', $migrationName)
            ->exists();

        if (! $wasExecuted) {
            throw new \Exception('Migration was not recorded in migrations table');
        }

        $this->line("  ✅ Executed: {$migration['basename']}");
    }

    private function showResults(int $exitCode = self::SUCCESS): int
    {
        $this->line('');
        $this->info('📊 Batch Migration Results:');
        $this->line('============================');
        $this->info('Total pending migrations: '.count($this->pendingMigrations));
        $this->info("Migrations executed: {$this->executedCount}");

        if (! empty($this->errors)) {
            $this->error('Migrations with errors: '.count($this->errors));

            $this->line('');
            $this->error('❌ Errors encountered:');
            foreach ($this->errors as $error) {
                $this->line("  • {$error['migration']}: {$error['error']}");
            }
        }

        $this->line('');

        if ($exitCode === self::SUCCESS && empty($this->errors)) {
            $this->info('✅ All migrations completed successfully!');
            $this->info('Each migration was executed in its own separate batch.');
        } elseif ($this->executedCount > 0) {
            $this->warn('⚠️  Migration process completed with some issues');
        } else {
            $this->error('❌ Migration process failed');
        }

        return $exitCode;
    }
}
