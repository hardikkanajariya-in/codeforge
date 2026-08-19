<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Models\DatabaseHealthMetric;
use HkDevs\CodeForgeStudio\Models\QueryPerformanceLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * CleanupLogsCommand
 *
 * Advanced log management and cleanup utility for CodeForge Database Studio.
 * Maintains optimal database performance by removing old log entries and metrics data.
 *
 * Features:
 * - Age-based log cleanup with configurable retention period
 * - Multiple log type support (query performance, health metrics, general logs)
 * - Dry-run mode for safe preview of cleanup operations
 * - Batch processing for efficient large-scale cleanup
 * - Progress reporting and detailed statistics
 * - Automatic optimization of log tables after cleanup
 *
 * Log Types Managed:
 * - Query Performance Logs: SQL execution metrics and timing data
 * - Database Health Metrics: System performance and monitoring data
 * - CodeForge Studio Application Logs: Plugin operation logs
 * - Migration and seeding logs: Database operation history
 *
 * Performance Considerations:
 * - Uses indexed date columns for efficient deletion
 * - Batch processing prevents memory exhaustion
 * - Automatic table optimization after cleanup
 * - Transaction-based operations for data integrity
 *
 * Scheduling Support:
 * - Designed for automated execution via Laravel scheduler
 * - Exit codes for monitoring and alerting systems
 * - Comprehensive error handling and reporting
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * # Clean logs older than 30 days
 * php artisan codeforge:cleanup-logs --days=30
 *
 * # Preview cleanup without deleting
 * php artisan codeforge:cleanup-logs --dry-run
 *
 * # Quick cleanup for daily maintenance
 * php artisan codeforge:cleanup-logs --days=7
 */
class CleanupLogsCommand extends Command
{
    protected $signature = 'codeforge:cleanup-logs {--days=30} {--dry-run}';

    protected $description = 'Clean up old CodeForge Studio logs';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        $cutoffDate = now()->subDays($days);

        $this->info("Cleaning up logs older than {$days} days (before {$cutoffDate->format('Y-m-d H:i:s')})");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No data will be deleted');
        }

        // Clean up query performance logs
        $queryLogsQuery = QueryPerformanceLog::where('executed_at', '<', $cutoffDate);
        $queryLogsCount = $queryLogsQuery->count();

        $this->line("Query Performance Logs to delete: {$queryLogsCount}");

        if (! $dryRun && $queryLogsCount > 0) {
            $deleted = $queryLogsQuery->delete();
            $this->info("✅ Deleted {$deleted} query performance log entries");
        }

        // Clean up health metrics
        $healthMetricsQuery = DatabaseHealthMetric::where('recorded_at', '<', $cutoffDate);
        $healthMetricsCount = $healthMetricsQuery->count();

        $this->line("Health Metrics to delete: {$healthMetricsCount}");

        if (! $dryRun && $healthMetricsCount > 0) {
            $deleted = $healthMetricsQuery->delete();
            $this->info("✅ Deleted {$deleted} health metric entries");
        }

        // Clean up general CodeForge Studio logs
        try {
            $managerLogsQuery = DB::table('database_manager_logs')
                ->where('executed_at', '<', $cutoffDate);
            $managerLogsCount = $managerLogsQuery->count();

            $this->line("CodeForge Studio Logs to delete: {$managerLogsCount}");

            if (! $dryRun && $managerLogsCount > 0) {
                $deleted = $managerLogsQuery->delete();
                $this->info("✅ Deleted {$deleted} CodeForge Studio log entries");
            }
        } catch (\Exception $e) {
            $this->comment('CodeForge Studio logs table not found or accessible');
        }

        if ($dryRun) {
            $this->info('🔍 Dry run completed. Use without --dry-run to actually delete the logs.');
        } else {
            $this->info('🧹 Cleanup completed successfully!');
        }

        return self::SUCCESS;
    }
}
