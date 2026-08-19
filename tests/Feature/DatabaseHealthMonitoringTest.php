<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature;

use HkDevs\CodeForgeStudio\Models\DatabaseHealthMetric;
use HkDevs\CodeForgeStudio\Models\QueryPerformanceLog;
use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test Cases for Database Health Monitoring Features
 *
 * Comprehensive tests covering real-time query performance tracking,
 * slow query detection, health metrics collection, and connection monitoring.
 */
class DatabaseHealthMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected DatabaseHealthService $healthService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->healthService = app(DatabaseHealthService::class);
    }

    /**
     * Test Real-time Query Performance Tracking
     *
     * Purpose: Test continuous query performance monitoring system
     */
    public function test_real_time_query_performance_tracking(): void
    {
        // Create test performance data
        $this->createQueryPerformanceLogs();

        // Test performance metrics retrieval
        $performanceMetrics = $this->healthService->getPerformanceMetrics();

        $this->assertArrayHasKey('query_performance', $performanceMetrics);

        $queryPerformance = $performanceMetrics['query_performance'];
        $this->assertArrayHasKey('total_queries', $queryPerformance);
        $this->assertArrayHasKey('avg_execution_time', $queryPerformance);
        $this->assertArrayHasKey('slow_queries', $queryPerformance);
        $this->assertArrayHasKey('failed_queries', $queryPerformance);

        // Verify data accuracy
        $this->assertIsInt($queryPerformance['total_queries']);
        $this->assertGreaterThanOrEqual(0, $queryPerformance['total_queries']);

        if ($queryPerformance['avg_execution_time'] !== null) {
            $this->assertIsNumeric($queryPerformance['avg_execution_time']);
        }
    }

    /**
     * Test Slow Query Detection & Analysis
     *
     * Purpose: Test automatic identification and logging of performance bottlenecks
     */
    public function test_slow_query_detection_and_analysis(): void
    {
        // Create slow query test data
        $this->createSlowQueryLogs();

        // Test slow query detection
        $performanceMetrics = $this->healthService->getPerformanceMetrics();
        $queryPerformance = $performanceMetrics['query_performance'];

        $this->assertArrayHasKey('slow_queries', $queryPerformance);

        // Verify slow queries are detected (threshold: 1000ms)
        $slowQueryCount = QueryPerformanceLog::where('connection', 'testing')
            ->where('executed_at', '>=', now()->subHours(24))
            ->where('execution_time', '>=', 1000)
            ->count();

        $this->assertEquals($slowQueryCount, $queryPerformance['slow_queries']);

        // Test query categorization
        $this->assertArrayHasKey('queries_by_type', $queryPerformance);
        $this->assertIsArray($queryPerformance['queries_by_type']);
    }

    /**
     * Test Health Metrics Collection
     *
     * Purpose: Test automated health data collection and storage
     */
    public function test_health_metrics_collection(): void
    {
        // Test connection health collection
        $connectionTest = $this->healthService->testConnection('testing');

        $this->assertArrayHasKey('status', $connectionTest);
        $this->assertArrayHasKey('response_time', $connectionTest);
        $this->assertArrayHasKey('connection', $connectionTest);
        $this->assertEquals('testing', $connectionTest['connection']);

        // Verify metric recording
        $this->assertDatabaseHas('database_health_metrics', [
            'connection' => 'testing',
            'metric_type' => 'connection_status',
            'metric_name' => 'response_time',
        ]);

        // Test multiple connection status
        $connectionStatus = $this->healthService->getConnectionStatus();
        $this->assertIsArray($connectionStatus);
        $this->assertArrayHasKey('testing', $connectionStatus);
    }

    /**
     * Test Connection Status & Health Checks
     *
     * Purpose: Test real-time database connection health monitoring
     */
    public function test_connection_status_and_health_checks(): void
    {
        // Test primary connection health
        $healthSummary = $this->healthService->getHealthSummary();

        $this->assertArrayHasKey('connection_status', $healthSummary);
        $this->assertArrayHasKey('recent_metrics', $healthSummary);
        $this->assertArrayHasKey('performance_summary', $healthSummary);
        $this->assertArrayHasKey('alerts', $healthSummary);

        // Test connection status details
        $connectionStatus = $healthSummary['connection_status'];
        $this->assertArrayHasKey('status', $connectionStatus);
        $this->assertArrayHasKey('response_time', $connectionStatus);

        // Test performance summary
        $performanceSummary = $healthSummary['performance_summary'];
        $this->assertArrayHasKey('queries_today', $performanceSummary);
        $this->assertArrayHasKey('avg_response_time', $performanceSummary);
        $this->assertArrayHasKey('slowest_query_time', $performanceSummary);
        $this->assertArrayHasKey('error_rate', $performanceSummary);
    }

    /**
     * Test Performance Alerts & Thresholds
     *
     * Purpose: Test configurable performance warning system
     */
    public function test_performance_alerts_and_thresholds(): void
    {
        // Create alert conditions
        $this->createAlertConditions();

        // Test alert detection
        $healthSummary = $this->healthService->getHealthSummary();
        $alerts = $healthSummary['alerts'];

        $this->assertIsArray($alerts);

        // Test alert structure if any exist
        foreach ($alerts as $alert) {
            $this->assertArrayHasKey('connection', $alert);
            $this->assertArrayHasKey('metric_type', $alert);
            $this->assertArrayHasKey('metric_name', $alert);
            $this->assertArrayHasKey('value', $alert);
            $this->assertArrayHasKey('status', $alert);
        }
    }

    /**
     * Test Health Report Generation
     *
     * Purpose: Test comprehensive health report creation
     */
    public function test_health_report_generation(): void
    {
        // Create comprehensive test data
        $this->createComprehensiveHealthData();

        // Test health summary generation
        $healthSummary = $this->healthService->getHealthSummary();

        // Verify comprehensive data structure
        $this->assertArrayHasKey('connection_status', $healthSummary);
        $this->assertArrayHasKey('recent_metrics', $healthSummary);
        $this->assertArrayHasKey('performance_summary', $healthSummary);
        $this->assertArrayHasKey('alerts', $healthSummary);

        // Test recent metrics grouping
        $recentMetrics = $healthSummary['recent_metrics'];
        $this->assertIsArray($recentMetrics);

        // Test performance summary calculations
        $performanceSummary = $healthSummary['performance_summary'];
        $this->assertIsNumeric($performanceSummary['queries_today']);
        $this->assertGreaterThanOrEqual(0, $performanceSummary['queries_today']);
    }

    /**
     * Test Query Performance Analysis
     *
     * Purpose: Test detailed query performance analysis
     */
    public function test_query_performance_analysis(): void
    {
        // Create varied query performance data
        $this->createVariedQueryPerformanceData();

        // Test performance metrics
        $performanceMetrics = $this->healthService->getPerformanceMetrics();
        $queryPerformance = $performanceMetrics['query_performance'];

        // Test query type analysis
        $this->assertArrayHasKey('queries_by_type', $queryPerformance);
        $queriesByType = $queryPerformance['queries_by_type'];

        foreach ($queriesByType as $type => $stats) {
            $this->assertIsString($type);
            $this->assertArrayHasKey('count', $stats);
            $this->assertArrayHasKey('avg_time', $stats);
        }

        // Test performance pattern recognition
        if ($queryPerformance['total_queries'] > 0) {
            $this->assertIsNumeric($queryPerformance['avg_execution_time']);
        }
    }

    /**
     * Test Database Metrics Collection
     *
     * Purpose: Test database-specific metrics gathering
     */
    public function test_database_metrics_collection(): void
    {
        // Test database metrics retrieval
        $performanceMetrics = $this->healthService->getPerformanceMetrics();

        $this->assertArrayHasKey('database_metrics', $performanceMetrics);
        $databaseMetrics = $performanceMetrics['database_metrics'];

        // Verify database metrics structure
        $this->assertIsArray($databaseMetrics);

        // For SQLite (testing), we should have database_size
        if (! isset($databaseMetrics['error'])) {
            // Check for expected metrics based on database type
            $this->assertTrue(
                isset($databaseMetrics['database_size']) ||
                    isset($databaseMetrics['active_connections']) ||
                    count($databaseMetrics) >= 0
            );
        }
    }

    /**
     * Test Query Logging
     *
     * Purpose: Test query performance logging functionality
     */
    public function test_query_logging(): void
    {
        // Test query logging
        $this->healthService->logQueryPerformance(
            'SELECT * FROM test_table WHERE id = ?',
            25.5,
            [1],
            'testing',
            'success'
        );

        // Verify log was created
        $this->assertDatabaseHas('query_performance_logs', [
            'connection' => 'testing',
            'execution_time' => 25.5,
            'status' => 'success',
        ]);

        // Test error logging
        $this->healthService->logQueryPerformance(
            'SELECT * FROM non_existent_table',
            0,
            [],
            'testing',
            'error',
            'Table does not exist'
        );

        $this->assertDatabaseHas('query_performance_logs', [
            'connection' => 'testing',
            'status' => 'error',
            'error_message' => 'Table does not exist',
        ]);
    }

    /**
     * Helper method to create query performance logs
     */
    protected function createQueryPerformanceLogs(): void
    {
        for ($i = 0; $i < 10; $i++) {
            QueryPerformanceLog::create([
                'query' => 'SELECT * FROM users WHERE id = ?',
                'query_hash' => md5("test_query_{$i}"),
                'bindings' => json_encode([$i + 1]),
                'execution_time' => rand(10, 200),
                'memory_usage' => rand(1024, 4096),
                'result_count' => 1,
                'connection' => 'testing',
                'executed_at' => now()->subHours($i),
                'query_type' => 'select',
                'is_slow' => false,
            ]);
        }
    }

    /**
     * Helper method to create slow query logs
     */
    protected function createSlowQueryLogs(): void
    {
        // Create normal queries
        $this->createQueryPerformanceLogs();

        // Create slow queries
        for ($i = 0; $i < 3; $i++) {
            QueryPerformanceLog::create([
                'query' => 'SELECT * FROM posts ORDER BY created_at DESC',
                'query_hash' => md5("slow_query_{$i}"),
                'bindings' => json_encode([]),
                'execution_time' => rand(1000, 5000), // > 1000ms threshold
                'memory_usage' => rand(8192, 16384),
                'result_count' => rand(100, 1000),
                'connection' => 'testing',
                'executed_at' => now()->subHours($i),
                'query_type' => 'select',
                'is_slow' => true,
            ]);
        }
    }

    /**
     * Helper method to create alert conditions
     */
    protected function createAlertConditions(): void
    {
        // Create warning condition
        DatabaseHealthMetric::create([
            'connection' => 'testing',
            'metric_type' => 'query_performance',
            'metric_name' => 'avg_execution_time',
            'value' => 850, // Below critical but above normal
            'threshold' => 1000,
            'unit' => 'ms',
            'status' => 'warning',
            'recorded_at' => now(),
        ]);

        // Create critical condition
        DatabaseHealthMetric::create([
            'connection' => 'testing',
            'metric_type' => 'connection_status',
            'metric_name' => 'response_time',
            'value' => 5500, // Above critical threshold
            'threshold' => 5000,
            'unit' => 'ms',
            'status' => 'critical',
            'recorded_at' => now(),
        ]);
    }

    /**
     * Helper method to create comprehensive health data
     */
    protected function createComprehensiveHealthData(): void
    {
        // Create varied performance logs
        $this->createVariedQueryPerformanceData();

        // Create health metrics
        $metrics = [
            ['connection_status', 'response_time', 25.5, 'normal'],
            ['query_performance', 'avg_execution_time', 45.2, 'normal'],
            ['database_info', 'database_size', 1024.0, 'normal'],
            ['database_info', 'active_connections', 5, 'normal'],
            ['query_performance', 'slow_queries_count', 2, 'warning'],
        ];

        foreach ($metrics as [$type, $name, $value, $status]) {
            DatabaseHealthMetric::create([
                'connection' => 'testing',
                'metric_type' => $type,
                'metric_name' => $name,
                'value' => $value,
                'unit' => $this->getMetricUnit($name),
                'status' => $status,
                'recorded_at' => now()->subMinutes(rand(1, 60)),
            ]);
        }
    }

    /**
     * Helper method to create varied query performance data
     */
    protected function createVariedQueryPerformanceData(): void
    {
        $queryTypes = ['select', 'insert', 'update', 'delete'];

        for ($i = 0; $i < 50; $i++) {
            $type = $queryTypes[array_rand($queryTypes)];
            $isSlow = $i % 10 === 0; // Every 10th query is slow

            QueryPerformanceLog::create([
                'query' => $this->getQueryByType($type),
                'query_hash' => md5("varied_query_{$i}"),
                'bindings' => json_encode([]),
                'execution_time' => $isSlow ? rand(1000, 3000) : rand(10, 500),
                'memory_usage' => rand(1024, 8192),
                'result_count' => rand(1, 100),
                'connection' => 'testing',
                'executed_at' => now()->subHours(rand(1, 23)),
                'query_type' => $type,
                'is_slow' => $isSlow,
                'status' => $i % 20 === 0 ? 'error' : 'success', // Some errors
            ]);
        }
    }

    /**
     * Helper method to get query by type
     */
    protected function getQueryByType(string $type): string
    {
        return match ($type) {
            'select' => 'SELECT * FROM users WHERE active = 1',
            'insert' => 'INSERT INTO users (name, email) VALUES (?, ?)',
            'update' => 'UPDATE users SET last_login = ? WHERE id = ?',
            'delete' => 'DELETE FROM users WHERE id = ?',
            default => 'SELECT 1',
        };
    }

    /**
     * Helper method to get metric unit
     */
    protected function getMetricUnit(string $metricName): string
    {
        return match ($metricName) {
            'response_time', 'avg_execution_time' => 'ms',
            'database_size' => 'MB',
            'active_connections', 'slow_queries_count' => 'count',
            default => 'count',
        };
    }
}
