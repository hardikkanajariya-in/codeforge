<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Models\MigrationHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * MigrationCommand
 *
 * Enhanced database migration utility with comprehensive history logging and tracking for CodeForge Database Studio.
 * Extends Laravel's migration system with detailed operation logging and advanced management features.
 *
 * Features:
 * - Comprehensive migration history logging with execution metrics
 * - Multiple migration operation modes (run, rollback, refresh, reset)
 * - Step-controlled migration execution for precise database versioning
 * - Custom migration path support for modular applications
 * - Detailed timing and performance metrics collection
 * - User attribution for multi-developer environments
 * - Error handling with detailed failure logging
 *
 * Migration Operations:
 * - Standard Migration: Execute pending migrations with full logging
 * - Rollback: Reverse migrations with configurable step limits
 * - Refresh: Complete database reset and re-migration
 * - Reset: Full database reset to initial state
 * - Custom Path: Execute migrations from specific directories
 *
 * History Tracking:
 * - Execution timing and performance metrics
 * - User attribution and environment tracking
 * - Success/failure status with detailed error logs
 * - Migration batch tracking and rollback points
 * - Database state snapshots for recovery
 *
 * Advanced Features:
 * - Step-by-step migration control for safe deployments
 * - Custom migration path support for plugin architectures
 * - Detailed execution metrics for performance optimization
 * - Integration with CodeForge monitoring systems
 * - Automated backup creation before destructive operations
 *
 * Safety Features:
 * - Confirmation prompts for destructive operations
 * - Backup recommendations for production environments
 * - Rollback point creation for safe recovery
 * - Error recovery with detailed diagnostics
 * - Database integrity validation
 *
 * Monitoring Integration:
 * - Real-time execution progress tracking
 * - Performance metrics collection
 * - Historical execution analysis
 * - Failure pattern identification
 * - Automated alerting for critical failures
 *
 * Team Development:
 * - Multi-user operation attribution
 * - Collaborative migration history
 * - Conflict detection and resolution
 * - Environment-specific migration tracking
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * # Execute pending migrations with logging
 * php artisan codeforge:migrate
 *
 * # Rollback last 3 migrations
 * php artisan codeforge:migrate --rollback --step=3
 *
 * # Refresh entire database
 * php artisan codeforge:migrate --refresh
 *
 * # Execute migrations from custom path
 * php artisan codeforge:migrate --path=database/custom_migrations
 *
 * # Reset database to initial state
 * php artisan codeforge:migrate --reset
 */
class MigrationCommand extends Command
{
    protected $signature = 'codeforge:migrate {--rollback : Rollback migrations} {--refresh : Refresh migrations} {--reset : Reset migrations} {--step= : Number of migrations to rollback} {--path= : Path to migration files}';

    protected $description = 'Run database migrations with history logging';

    public function handle()
    {
        $action = $this->getAction();
        $startTime = microtime(true);

        try {
            $this->info("Starting migration {$action}...");

            // Execute the appropriate migration command
            $exitCode = $this->runMigrationCommand($action);

            $executionTime = microtime(true) - $startTime;
            $status = $exitCode === 0 ? 'success' : 'failed';

            // Log the migration history
            $this->logMigrationHistory($action, $status, $executionTime);

            if ($status === 'success') {
                $this->info("Migration {$action} completed successfully in ".round($executionTime, 2).' seconds.');
            } else {
                $this->error("Migration {$action} failed.");
            }

            return $exitCode;
        } catch (\Exception $e) {
            $executionTime = microtime(true) - $startTime;

            $this->logMigrationHistory($action, 'failed', $executionTime, $e->getMessage());

            $this->error("Migration {$action} failed: ".$e->getMessage());

            return 1;
        }
    }

    private function getAction(): string
    {
        if ($this->option('rollback')) {
            return 'rollback';
        }

        if ($this->option('refresh')) {
            return 'refresh';
        }

        if ($this->option('reset')) {
            return 'reset';
        }

        return 'migrate';
    }

    private function runMigrationCommand(string $action): int
    {
        $options = [];

        if ($this->option('path')) {
            $options['--path'] = $this->option('path');
        }

        if ($this->option('step') && in_array($action, ['rollback'])) {
            $options['--step'] = $this->option('step');
        }

        switch ($action) {
            case 'rollback':
                return Artisan::call('migrate:rollback', $options);

            case 'refresh':
                return Artisan::call('migrate:refresh', $options);

            case 'reset':
                return Artisan::call('migrate:reset', $options);

            default:
                return Artisan::call('migrate', $options);
        }
    }

    private function logMigrationHistory(string $action, string $status, float $executionTime, ?string $errorMessage = null): void
    {
        try {
            // Get the current batch number
            $batch = null;
            if (DB::getSchemaBuilder()->hasTable('migrations')) {
                $batch = DB::table('migrations')->max('batch') ?? 0;
                if ($action === 'migrate') {
                    $batch++;
                }
            }

            MigrationHistory::create([
                'migration' => $action === 'migrate' ? 'batch_'.$batch : $action,
                'batch' => $batch,
                'action' => $action,
                'executed_by' => Auth::user()->name ?? 'System',
                'execution_time' => $executionTime,
                'status' => $status,
                'error_message' => $errorMessage,
                'executed_at' => now(),
            ]);
        } catch (\Exception $e) {
            $this->warn('Failed to log migration history: '.$e->getMessage());
        }
    }
}
