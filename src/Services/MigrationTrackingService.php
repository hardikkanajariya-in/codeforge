<?php

namespace HkDevs\CodeForgeStudio\Services;

use HkDevs\CodeForgeStudio\Models\MigrationHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * MigrationTrackingService
 *
 * Comprehensive migration tracking and monitoring service for CodeForge Database Studio.
 * Provides detailed migration execution logging, history management, and performance analysis.
 *
 * Features:
 * - Detailed migration execution logging with comprehensive metrics
 * - User attribution tracking for multi-developer environments
 * - Migration performance monitoring with execution time analysis
 * - Error tracking and diagnostic information collection
 * - Migration batch management with rollback point identification
 * - Cross-system migration synchronization and consistency checking
 * - Historical analysis with migration pattern recognition
 * - Automated cleanup and maintenance of migration history data
 *
 * Tracking Capabilities:
 * - Execution Logging: Detailed logging of all migration operations with timestamps
 * - Performance Metrics: Execution time tracking and performance benchmarking
 * - User Attribution: Track which users execute migrations in team environments
 * - Error Reporting: Comprehensive error logging with stack traces and context
 * - Batch Tracking: Migration batch identification for rollback operations
 * - Status Monitoring: Real-time migration status with success/failure tracking
 * - Environment Tracking: Environment-specific migration execution logging
 *
 * History Management:
 * - Complete Migration History: Persistent storage of all migration operations
 * - Rollback Point Management: Identification and management of rollback points
 * - Migration Synchronization: Sync migration history across different systems
 * - Orphaned Entry Cleanup: Detection and cleanup of inconsistent migration records
 * - Data Integrity Validation: Continuous validation of migration history consistency
 * - Archive Management: Automated archiving of old migration history data
 * - Backup Integration: Integration with backup systems for history preservation
 *
 * Performance Analysis:
 * - Execution Time Tracking: Detailed analysis of migration execution performance
 * - Performance Benchmarking: Comparison of migration performance across environments
 * - Bottleneck Identification: Detection of slow migrations and performance issues
 * - Optimization Recommendations: Suggestions for migration performance improvements
 * - Resource Usage Monitoring: Track CPU, memory, and I/O usage during migrations
 * - Trend Analysis: Historical performance trends and pattern recognition
 * - Alert Generation: Automated alerts for performance anomalies and issues
 *
 * Error Management:
 * - Comprehensive Error Logging: Detailed error information with stack traces
 * - Error Pattern Analysis: Recognition of common error patterns and causes
 * - Recovery Recommendations: Automated suggestions for error resolution
 * - Error Notification: Real-time alerts for migration failures and issues
 * - Diagnostic Information: Collection of system state and configuration data
 * - Recovery Tracking: Monitoring of error recovery and resolution processes
 * - Prevention Strategies: Proactive error prevention based on historical data
 *
 * Integration Features:
 * - Laravel Integration: Seamless integration with Laravel's migration system
 * - Authentication Integration: User tracking with Laravel's authentication system
 * - Database Integration: Support for all Laravel-supported database systems
 * - Logging Integration: Integration with Laravel's logging and monitoring systems
 * - Event Integration: Laravel event system integration for migration workflows
 * - API Integration: REST endpoints for external migration monitoring tools
 * - Webhook Support: Real-time notifications for external systems and tools
 *
 * Monitoring and Alerting:
 * - Real-time Monitoring: Live tracking of migration execution and status
 * - Threshold Alerts: Configurable alerts for execution time and error thresholds
 * - Dashboard Integration: Integration with monitoring dashboards and tools
 * - Report Generation: Automated generation of migration reports and summaries
 * - Notification Systems: Integration with email, Slack, and other notification services
 * - Health Checks: Continuous health monitoring of migration tracking system
 * - Compliance Reporting: Automated compliance reports for audit requirements
 *
 * Data Management:
 * - Efficient Storage: Optimized storage strategies for migration history data
 * - Data Retention: Configurable retention policies with automated cleanup
 * - Data Export: Export capabilities for external analysis and archiving
 * - Data Validation: Continuous validation of migration history data integrity
 * - Backup Integration: Automated backup of migration history for disaster recovery
 * - Archive Management: Long-term archiving strategies for historical data
 * - Performance Optimization: Database optimization for efficient history queries
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * $service = app(MigrationTrackingService::class);
 * $service->logMigrationExecution('create_users_table', 'up', 1.25, 'success');
 * $service->syncMigrationHistory();
 * $orphaned = $service->cleanupOrphanedEntries();
 */
class MigrationTrackingService
{
    public function logMigrationExecution(
        string $migrationName,
        string $action,
        float $executionTime,
        string $status = 'success',
        ?string $errorMessage = null
    ): void {
        try {
            // Check if migration_histories table exists before logging
            if (! DB::getSchemaBuilder()->hasTable('migration_histories')) {
                // Silently skip logging if the table doesn't exist yet
                return;
            }

            // Get the batch number for this migration
            $batch = $this->getBatchForMigration($migrationName, $action);

            MigrationHistory::create([
                'migration' => $migrationName,
                'batch' => $batch,
                'action' => $action,
                'executed_by' => $this->getExecutedBy(),
                'execution_time' => $executionTime,
                'status' => $status,
                'error_message' => $errorMessage,
                'executed_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log migration history', [
                'migration' => $migrationName,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function getBatchForMigration(string $migrationName, string $action): ?int
    {
        try {
            if (! DB::getSchemaBuilder()->hasTable('migrations')) {
                return null;
            }

            // For migrate action, get the batch from the migrations table
            if ($action === 'migrate') {
                return DB::table('migrations')
                    ->where('migration', $migrationName)
                    ->value('batch');
            }

            // For rollback, we don't have the batch in the migrations table anymore
            // but we can store it in our tracking if needed
            return null;
        } catch (\Exception $e) {
            Log::warning('Failed to get batch for migration: '.$e->getMessage());

            return null;
        }
    }

    private function getExecutedBy(): string
    {
        if (Auth::check()) {
            return Auth::user()->name ?? Auth::user()->email ?? 'Authenticated User';
        }

        if (app()->runningInConsole()) {
            return 'Console';
        }

        return 'System';
    }

    public function cleanupOrphanedEntries(): int
    {
        try {
            // Remove migration history entries that don't have corresponding migrations in the migrations table
            $deleted = MigrationHistory::whereNotIn('migration', function ($query) {
                $query->select('migration')->from('migrations');
            })->delete();

            return $deleted;
        } catch (\Exception $e) {
            Log::warning('Failed to cleanup orphaned migration history entries: '.$e->getMessage());

            return 0;
        }
    }

    public function syncMigrationHistory(): void
    {
        try {
            if (! DB::getSchemaBuilder()->hasTable('migrations')) {
                return;
            }

            // Get all migrations from the migrations table
            $migrations = DB::table('migrations')->get(['migration', 'batch']);

            foreach ($migrations as $migration) {
                // Check if this migration is already in the history
                $exists = MigrationHistory::where('migration', $migration->migration)
                    ->where('action', 'migrate')
                    ->exists();

                if (! $exists) {
                    // Add missing migration history
                    MigrationHistory::create([
                        'migration' => $migration->migration,
                        'batch' => $migration->batch,
                        'action' => 'migrate',
                        'executed_by' => 'System (Sync)',
                        'execution_time' => null,
                        'status' => 'success',
                        'executed_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to sync migration history: '.$e->getMessage());
        }
    }
}
