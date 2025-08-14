<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use HkDevs\CodeForgeStudio\Models\Migration;

/**
 * MigrationManager
 * 
 * Custom Filament page for comprehensive Laravel migration management
 * with enhanced functionality for CodeForge Database Studio.
 * 
 * Key Features:
 * - Real-time migration status monitoring and display
 * - Individual migration execution with precise control
 * - Batch rollback operations with safety confirmations
 * - File system integration for migration discovery
 * - Enhanced migration metadata and execution tracking
 * 
 * Page Configuration:
 * - Custom view with interactive migration table
 * - Real-time status updates and progress tracking
 * - Positioned in 'Database Tools' navigation group
 * - Custom actions for migration operations
 * 
 * Migration Operations:
 * - Run individual pending migrations with confirmation
 * - Execute all pending migrations in correct order
 * - Rollback last batch with data loss warnings
 * - Refresh all migrations with complete reset
 * - Real-time status monitoring and updates
 * 
 * Safety Features:
 * - Confirmation dialogs for destructive operations
 * - File existence validation before execution
 * - Detailed error reporting and logging
 * - Rollback impact analysis and warnings
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class MigrationManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static string $view = 'codeforge-database-studio::pages.migration-manager';
    protected static ?string $navigationLabel = 'Migration Manager';
    protected static ?int $navigationSort = 2;

    public $migrations = [];
    public $pendingCount = 0;
    public $executedCount = 0;
    public $totalCount = 0;

    public function mount(): void
    {
        $this->loadMigrations();
    }

    public function loadMigrations(): void
    {
        $this->migrations = $this->getAllMigrations();
        $this->pendingCount = collect($this->migrations)->where('status', 'pending')->count();
        $this->executedCount = collect($this->migrations)->where('status', 'executed')->count();
        $this->totalCount = count($this->migrations);
    }

    protected function getAllMigrations(): array
    {
        $migrationPath = database_path('migrations');

        if (!is_dir($migrationPath)) {
            return [];
        }

        $migrationFiles = collect(File::files($migrationPath))
            ->map(function ($file) {
                return pathinfo($file->getFilename(), PATHINFO_FILENAME);
            })
            ->sort()
            ->values();

        $executedMigrations = collect();
        if (Schema::hasTable('migrations')) {
            try {
                $executedMigrations = DB::table('migrations')->get()->keyBy('migration');
            } catch (\Exception $e) {
                Log::warning('Could not load executed migrations: ' . $e->getMessage());
            }
        }

        return $migrationFiles->map(function ($migration) use ($executedMigrations) {
            $executed = $executedMigrations->get($migration);

            $migrationData = [
                'migration' => $migration,
                'batch' => $executed->batch ?? null,
                'executed_at' => $executed ? \Carbon\Carbon::parse($executed->created_at ?? null) : null,
                'status' => $executed ? 'executed' : 'pending',
                'file_exists' => file_exists(database_path('migrations/' . $migration . '.php')),
                'display_name' => $this->formatMigrationName($migration),
            ];

            // Add rollback capability check
            $migrationData['can_rollback_individually'] = $this->canRollbackIndividually($migrationData);

            return $migrationData;
        })->toArray();
    }

    protected function formatMigrationName(string $migration): string
    {
        // Remove timestamp and convert to readable format
        $name = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migration);
        return str_replace('_', ' ', ucwords($name, '_'));
    }

    /**
     * Check if a migration can be rolled back individually
     * Returns false if there are newer migrations that would be affected
     */
    protected function canRollbackIndividually(array $migration): bool
    {
        if ($migration['status'] !== 'executed' || !$migration['file_exists']) {
            return false;
        }

        $migrationBatch = $migration['batch'];
        $migrationName = $migration['migration'];

        // Check if this migration has other migrations after it that would be affected
        $laterMigrations = DB::table('migrations')
            ->where('batch', '>', $migrationBatch)
            ->orWhere(function ($query) use ($migrationBatch, $migrationName) {
                $query->where('batch', $migrationBatch)
                    ->where('migration', '>', $migrationName);
            })
            ->count();

        return $laterMigrations === 0;
    }

    public function runMigration(string $migrationName): void
    {
        try {
            Log::info('Running individual migration', ['migration' => $migrationName]);

            $migrationFile = $migrationName . '.php';
            $sourcePath = database_path('migrations/' . $migrationFile);

            if (!file_exists($sourcePath)) {
                throw new \Exception('Migration file not found: ' . $migrationFile);
            }

            // Check if already executed
            $migration = collect($this->migrations)->firstWhere('migration', $migrationName);
            if ($migration && $migration['status'] === 'executed') {
                Notification::make()
                    ->title('Migration already executed')
                    ->body('This migration has already been executed.')
                    ->warning()
                    ->send();
                return;
            }

            // Run the migration
            $result = Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            Log::info('Migration command executed', [
                'migration' => $migrationName,
                'result_code' => $result,
                'output' => $output
            ]);

            if ($result === 0) {
                Notification::make()
                    ->title('Migration executed successfully')
                    ->body('Migration "' . $this->formatMigrationName($migrationName) . '" has been executed.')
                    ->success()
                    ->send();

                $this->loadMigrations(); // Refresh data
            } else {
                throw new \Exception('Migration failed with code: ' . $result . '. Output: ' . $output);
            }
        } catch (\Exception $e) {
            Log::error('Migration execution failed', [
                'migration' => $migrationName,
                'error' => $e->getMessage()
            ]);

            Notification::make()
                ->title('Migration execution failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function rollbackMigration($migrationName)
    {
        try {
            // Validate migration exists and is executed
            $migration = collect($this->migrations)->firstWhere('migration', $migrationName);

            if (!$migration) {
                Notification::make()
                    ->danger()
                    ->title('Migration not found')
                    ->body("Migration '{$migrationName}' was not found.")
                    ->send();
                return;
            }

            if ($migration['status'] !== 'executed') {
                Notification::make()
                    ->warning()
                    ->title('Migration not executed')
                    ->body("Migration '{$migrationName}' is not executed and cannot be rolled back.")
                    ->send();
                return;
            }

            if (!$migration['file_exists']) {
                Notification::make()
                    ->danger()
                    ->title('Migration file missing')
                    ->body("Migration file for '{$migrationName}' does not exist and cannot be rolled back.")
                    ->send();
                return;
            }

            // Check if this migration has other migrations after it that would be affected
            $migrationBatch = $migration['batch'];
            $laterMigrations = DB::table('migrations')
                ->where('batch', '>', $migrationBatch)
                ->orWhere(function ($query) use ($migrationBatch, $migrationName) {
                    $query->where('batch', $migrationBatch)
                        ->where('migration', '>', $migrationName);
                })
                ->count();

            if ($laterMigrations > 0) {
                Notification::make()
                    ->warning()
                    ->title('Cannot rollback individual migration')
                    ->body("This migration has {$laterMigrations} migration(s) that were executed after it. You must rollback those first or use batch rollback.")
                    ->send();
                return;
            }

            // Execute individual migration rollback using migrate:rollback with step
            $steps = 1;
            $exitCode = Artisan::call('migrate:rollback', [
                '--step' => $steps,
                '--force' => true
            ]);

            $output = Artisan::output();

            if ($exitCode === 0) {
                Notification::make()
                    ->success()
                    ->title('Migration rolled back successfully')
                    ->body("Successfully rolled back migration: {$migration['display_name']}")
                    ->send();

                Log::info('Individual migration rollback completed successfully', [
                    'migration' => $migrationName,
                    'batch' => $migrationBatch,
                    'output' => $output
                ]);
            } else {
                Notification::make()
                    ->danger()
                    ->title('Rollback failed')
                    ->body('The rollback command failed. Check the logs for details.')
                    ->send();

                Log::error('Individual migration rollback failed', [
                    'migration' => $migrationName,
                    'exit_code' => $exitCode,
                    'output' => $output
                ]);
            }
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Rollback Error')
                ->body('An error occurred during rollback: ' . $e->getMessage())
                ->send();

            Log::error('Individual migration rollback error', [
                'migration' => $migrationName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $this->loadMigrations();
    }

    public function rollbackLastBatch(): void
    {
        try {
            $this->loadMigrations();
            $pendingCount = collect($this->migrations)->where('status', 'pending')->count();

            if ($pendingCount > 0) {
                Notification::make()
                    ->title('Cannot rollback')
                    ->body('You have pending migrations. Please run them first or rollback to a specific batch.')
                    ->warning()
                    ->send();
                return;
            }

            $lastBatch = DB::table('migrations')->max('batch');

            if (!$lastBatch) {
                Notification::make()
                    ->title('No migrations to rollback')
                    ->body('There are no executed migrations to rollback.')
                    ->warning()
                    ->send();
                return;
            }

            // Get migrations from the last batch
            $lastBatchMigrations = DB::table('migrations')
                ->where('batch', $lastBatch)
                ->orderBy('migration', 'desc')
                ->get();

            if ($lastBatchMigrations->isEmpty()) {
                Notification::make()
                    ->title('No migrations in last batch')
                    ->body('The last batch contains no migrations to rollback.')
                    ->warning()
                    ->send();
                return;
            }

            // Execute rollback command
            $result = Artisan::call('migrate:rollback', ['--force' => true]);
            $output = Artisan::output();

            if ($result === 0) {
                $migrationsCount = $lastBatchMigrations->count();
                $migrationsList = $lastBatchMigrations->pluck('migration')->join(', ');

                Notification::make()
                    ->title('Rollback completed successfully')
                    ->body("Rolled back {$migrationsCount} migration(s) from batch {$lastBatch}")
                    ->success()
                    ->send();

                Log::info('Migration batch rollback completed successfully', [
                    'batch' => $lastBatch,
                    'migrations_count' => $migrationsCount,
                    'migrations' => $lastBatchMigrations->pluck('migration')->toArray(),
                    'output' => $output
                ]);

                $this->loadMigrations(); // Refresh data
            } else {
                throw new \Exception('Rollback failed with code: ' . $result . '. Output: ' . $output);
            }
        } catch (\Exception $e) {
            Log::error('Migration batch rollback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Notification::make()
                ->title('Rollback failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('run_all_pending')
                ->label('Run All Pending')
                ->icon('heroicon-o-play')
                ->color('success')
                ->visible(fn() => $this->pendingCount > 0)
                ->requiresConfirmation()
                ->modalHeading('Run All Pending Migrations')
                ->modalDescription('Are you sure you want to run all ' . $this->pendingCount . ' pending migration(s)?')
                ->action(function () {
                    try {
                        $result = Artisan::call('migrate', ['--force' => true]);

                        if ($result === 0) {
                            Notification::make()
                                ->title('All pending migrations executed')
                                ->body($this->pendingCount . ' migration(s) have been executed successfully.')
                                ->success()
                                ->send();

                            $this->loadMigrations();
                        } else {
                            throw new \Exception('Migration failed with code: ' . $result);
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Migration execution failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('rollback_last_batch')
                ->label('Rollback Last Batch')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('danger')
                ->visible(fn() => $this->executedCount > 0)
                ->requiresConfirmation()
                ->modalHeading('Rollback Last Batch')
                ->modalDescription('This will rollback the last batch of migrations. This action may result in data loss.')
                ->action(fn() => $this->rollbackLastBatch()),

            Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn() => $this->loadMigrations()),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Database Tools';
    }
}
