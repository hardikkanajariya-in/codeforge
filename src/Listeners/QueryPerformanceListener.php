<?php

namespace HkDevs\CodeForgeStudio\Listeners;

use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use Illuminate\Database\Events\QueryExecuted;

/**
 * QueryPerformanceListener
 * 
 * Event listener for database query execution that provides performance monitoring
 * and health tracking for CodeForge Database Studio applications.
 * 
 * Key Features:
 * - Real-time query performance monitoring and logging
 * - Automatic recursion prevention with smart flag management
 * - Configurable query logging with performance filtering
 * - Intelligent query skipping to avoid monitoring overhead
 * - Integration with DatabaseHealthService for metrics storage
 * - Connection-aware logging for multi-database setups
 * 
 * Performance Monitoring:
 * - Execution time tracking with microsecond precision
 * - Query binding capture for debugging and analysis
 * - Connection name tracking for multi-database environments
 * - Memory-efficient logging with selective query filtering
 * 
 * Smart Query Filtering:
 * - Automatic skipping of health monitoring table queries
 * - System table query filtering (information_schema, performance_schema)
 * - Development query filtering (SHOW, DESCRIBE, EXPLAIN commands)
 * - Recursion prevention for logging queries themselves
 * 
 * Configuration Options:
 * - Configurable query logging enable/disable
 * - Selective table monitoring with pattern matching
 * - Performance threshold configuration
 * - Error handling and silent failure modes
 * 
 * Integration Features:
 * - Laravel event system compatibility
 * - DatabaseHealthService integration for data persistence
 * - Multi-connection database support
 * - Graceful error handling without application interruption
 * 
 * @package HkDevs\CodeForgeStudio\Listeners
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * // Auto-registered through service provider
 * Event::listen(QueryExecuted::class, [QueryPerformanceListener::class, 'handle']);
 */
class QueryPerformanceListener
{
    protected static bool $isLogging = false;

    public function __construct()
    {
        // Empty constructor - we'll get the service using app() to avoid DI issues
    }

    public function handle(QueryExecuted $event): void
    {
        // Prevent recursion by checking if we're already logging
        if (static::$isLogging) {
            return;
        }

        // Only log if performance monitoring is enabled
        if (!config('codeforge-database-studio.enable_query_logging', true)) {
            return;
        }

        // Skip logging for our own health monitoring tables to avoid recursion
        if ($this->shouldSkipQuery($event->sql)) {
            return;
        }

        try {
            // Set flag to prevent recursion
            static::$isLogging = true;

            app(DatabaseHealthService::class)->logQueryPerformance(
                query: $event->sql,
                executionTime: $event->time,
                bindings: $event->bindings,
                connection: $event->connectionName
            );
        } catch (\Exception $e) {
            // Silently fail to avoid breaking the application
        } finally {
            // Always reset the flag
            static::$isLogging = false;
        }
    }

    protected function shouldSkipQuery(string $sql): bool
    {
        $sql = strtolower(trim($sql));

        // Skip queries to our health monitoring tables (most important for preventing recursion)
        $skipPatterns = [
            'query_performance_logs',
            'database_health_metrics',
            'database_manager_logs',
            'migration_histories',
        ];

        foreach ($skipPatterns as $pattern) {
            if (str_contains($sql, $pattern)) {
                return true;
            }
        }

        // Skip INSERT queries to our tables specifically
        if (
            str_starts_with($sql, 'insert into') &&
            (str_contains($sql, 'query_performance_logs') ||
                str_contains($sql, 'database_health_metrics') ||
                str_contains($sql, 'database_manager_logs'))
        ) {
            return true;
        }

        // Skip certain types of queries that might cause noise
        $skipQueries = [
            'show tables',
            'show columns',
            'show status',
            'show variables',
            'describe ',
            'information_schema',
            'pg_catalog',
            'sqlite_master',
            'performance_schema',
            'mysql.user',
            'sys.',
        ];

        foreach ($skipQueries as $skipQuery) {
            if (str_contains($sql, $skipQuery)) {
                return true;
            }
        }

        // Skip queries that start with SHOW, DESCRIBE, EXPLAIN
        $skipStarts = ['show ', 'describe ', 'explain ', 'analyze '];
        foreach ($skipStarts as $start) {
            if (str_starts_with($sql, $start)) {
                return true;
            }
        }

        return false;
    }
}
