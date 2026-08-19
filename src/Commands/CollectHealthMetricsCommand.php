<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use Illuminate\Console\Command;

/**
 * CollectHealthMetricsCommand
 *
 * Real-time database health monitoring and metrics collection utility for CodeForge Database Studio.
 * Provides comprehensive database performance monitoring and health assessment capabilities.
 *
 * Features:
 * - Multi-connection database health monitoring
 * - Real-time performance metrics collection
 * - Connection status verification and response time measurement
 * - Query performance analysis and slow query detection
 * - Database size and storage metrics
 * - Failed query tracking and error analysis
 * - Historical data comparison and trending
 *
 * Metrics Collected:
 * - Connection Health: Status, response times, availability
 * - Query Performance: Execution times, slow queries, failed queries
 * - Database Metrics: Size, table counts, index efficiency
 * - System Resources: Memory usage, CPU utilization
 * - Error Tracking: Failed connections, query errors, timeouts
 *
 * Monitoring Capabilities:
 * - 24-hour rolling statistics
 * - Threshold-based alerting
 * - Performance trend analysis
 * - Automated health scoring
 * - Resource utilization tracking
 *
 * Integration Features:
 * - Designed for Laravel scheduler automation
 * - Compatible with monitoring dashboards
 * - Exportable metrics for external systems
 * - Real-time notification support
 * - Historical data retention management
 *
 * Use Cases:
 * - Continuous database health monitoring
 * - Performance regression detection
 * - Capacity planning and optimization
 * - Proactive issue identification
 * - SLA compliance monitoring
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * # Collect metrics for default connection
 * php artisan codeforge:collect-metrics
 *
 * # Monitor specific database connection
 * php artisan codeforge:collect-metrics --connection=mysql
 *
 * # Use with Laravel scheduler for continuous monitoring
 * $schedule->command('codeforge:collect-metrics')->everyFiveMinutes();
 */
class CollectHealthMetricsCommand extends Command
{
    protected $signature = 'codeforge:collect-metrics {--connection=}';

    protected $description = 'Collect database health metrics';

    protected DatabaseHealthService $healthService;

    public function __construct()
    {
        parent::__construct();
        $this->healthService = app(DatabaseHealthService::class);
    }

    public function handle(): int
    {
        $connection = $this->option('connection') ?? config('database.default');

        $this->info("Collecting health metrics for connection: {$connection}");

        try {
            // Test connection and record metrics
            $connectionStatus = $this->healthService->testConnection($connection);
            $this->line('Connection Status: '.$connectionStatus['status']);

            if ($connectionStatus['status'] === 'connected') {
                $this->line('Response Time: '.$connectionStatus['response_time'].'ms');

                // Collect performance metrics
                $performanceMetrics = $this->healthService->getPerformanceMetrics($connection);

                if (isset($performanceMetrics['query_performance'])) {
                    $queryStats = $performanceMetrics['query_performance'];
                    $this->line('Queries (24h): '.$queryStats['total_queries']);

                    if ($queryStats['avg_execution_time']) {
                        $this->line('Avg Execution Time: '.number_format($queryStats['avg_execution_time'], 2).'ms');
                    }

                    $this->line('Slow Queries: '.$queryStats['slow_queries']);
                    $this->line('Failed Queries: '.$queryStats['failed_queries']);
                }

                if (isset($performanceMetrics['database_metrics'])) {
                    $dbMetrics = $performanceMetrics['database_metrics'];

                    if (isset($dbMetrics['database_size'])) {
                        $this->line('Database Size: '.$dbMetrics['database_size'].' MB');
                    }

                    if (isset($dbMetrics['active_connections'])) {
                        $this->line('Active Connections: '.$dbMetrics['active_connections']);
                    }
                }

                $this->info('✅ Health metrics collected successfully');
            } else {
                $this->error('❌ Connection failed: '.$connectionStatus['message']);
            }
        } catch (\Exception $e) {
            $this->error('Failed to collect metrics: '.$e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
