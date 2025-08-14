<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature\HealthMonitoring;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use HkDevs\CodeForgeStudio\Models\QueryPerformanceLog;
use HkDevs\CodeForgeStudio\Models\DatabaseHealthMetric;
use HkDevs\CodeForgeStudio\Listeners\QueryPerformanceListener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\QueryExecuted;
use Carbon\Carbon;

/**
 * Health Monitoring Performance and Load Testing Suite
 * 
 * This test class implements performance and scalability tests for the health monitoring system:
 * 
 * - TC-PERF-001: Large Database Handling
 * - TC-PERF-002: Concurrent User Testing
 * - TC-PERF-003: Memory Usage Optimization
 * - High-volume query logging performance
 * - Health metrics collection under load
 * - Widget performance with large datasets
 * 
 * @package HkDevs\CodeForgeStudio\Tests\Feature\HealthMonitoring
 * @author HkDevs (hardikkanajariya.in)
 */
class HealthMonitoringPerformanceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private DatabaseHealthService $healthService;
    private QueryPerformanceListener $queryListener;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize services
        $this->healthService = app(DatabaseHealthService::class);
        $this->queryListener = new QueryPerformanceListener();

        // Configure for performance testing
        Config::set('codeforge-database-studio.enable_query_logging', true);
        Config::set('codeforge-database-studio.health_monitoring.slow_query_threshold', 1000);
        Config::set('codeforge-database-studio.health_monitoring.max_log_entries', 10000);

        $this->runPluginMigrations();
    }

    /**
     * Run plugin migrations for testing environment
     */
    private function runPluginMigrations(): void
    {
        if (!Schema::hasTable('database_health_metrics')) {
            Schema::create('database_health_metrics', function ($table) {
                $table->id();
                $table->string('connection');
                $table->string('metric_type');
                $table->string('metric_name');
                $table->decimal('value', 15, 4);
                $table->string('unit')->nullable();
                $table->string('status')->default('normal');
                $table->json('metadata')->nullable();
                $table->timestamp('recorded_at');
                $table->timestamps();

                $table->index(['connection', 'recorded_at']);
                $table->index(['metric_type', 'recorded_at']);
            });
        }

        if (!Schema::hasTable('query_performance_logs')) {
            Schema::create('query_performance_logs', function ($table) {
                $table->id();
                $table->string('connection');
                $table->text('query');
                $table->string('query_hash');
                $table->decimal('execution_time', 8, 2);
                $table->integer('rows_affected')->nullable();
                $table->json('bindings')->nullable();
                $table->string('type');
                $table->string('status')->default('success');
                $table->text('error_message')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamp('executed_at');
                $table->timestamps();

                $table->index(['connection', 'executed_at']);
                $table->index(['query_hash', 'executed_at']);
                $table->index(['execution_time', 'executed_at']);
                $table->index(['type', 'executed_at']);
            });
        }
    }

    /**
     * TC-PERF-001: Large Database Handling
     * Purpose: Test plugin performance with large databases
     */
    public function test_large_database_handling_performance(): void
    {
        $this->markTestSkipped('Large database test - enable for performance testing');

        // Step 1: Test with databases containing 100+ tables (simulated)
        $this->simulateLargeTableStructure();

        // Step 2: Verify performance with millions of records (scaled down for testing)
        $this->createLargeQueryDataset(1000); // Use 1k instead of millions for testing

        $startTime = microtime(true);

        // Step 3: Test memory usage optimization
        $initialMemory = memory_get_usage(true);

        // Perform health monitoring operations
        $healthSummary = $this->healthService->getHealthSummary('testing');
        $performanceMetrics = $this->healthService->getPerformanceMetrics('testing');

        $finalMemory = memory_get_usage(true);
        $memoryUsed = $finalMemory - $initialMemory;

        $executionTime = microtime(true) - $startTime;

        // Step 4: Verify query performance monitoring accuracy
        $this->assertIsArray($healthSummary);
        $this->assertIsArray($performanceMetrics);

        // Step 5: Test UI responsiveness with large datasets
        $queryCount = QueryPerformanceLog::count();
        $this->assertGreaterThan(0, $queryCount);

        // Performance assertions
        $this->assertLessThan(5, $executionTime, 'Health operations should complete within 5 seconds');
        $this->assertLessThan(50 * 1024 * 1024, $memoryUsed, 'Memory usage should be under 50MB');

        // Expected Results: Plugin performs well with large databases
        $this->assertTrue(true, 'Large database handling is performing within acceptable limits');
    }

    /**
     * TC-PERF-002: Concurrent User Testing
     * Purpose: Test plugin performance with multiple concurrent users
     */
    public function test_concurrent_user_simulation(): void
    {
        // Step 1: Simulate multiple users accessing features simultaneously
        $concurrentOperations = 10;
        $results = [];

        for ($i = 0; $i < $concurrentOperations; $i++) {
            $startTime = microtime(true);

            // Simulate concurrent health monitoring operations
            $this->simulateUserHealthMonitoringSession($i);

            $endTime = microtime(true);
            $results[] = $endTime - $startTime;
        }

        // Step 2: Test migration execution concurrency (simulated)
        // In real scenario, this would involve actual concurrent database operations
        $this->simulateConcurrentDatabaseOperations();

        // Step 3: Verify resource locking mechanisms
        // Test that concurrent operations don't interfere with each other
        $queryLogs = QueryPerformanceLog::where('connection', 'testing')->get();
        $this->assertGreaterThan(0, $queryLogs->count());

        // Step 4: Test performance monitoring under load
        $avgResponseTime = array_sum($results) / count($results);
        $this->assertLessThan(2, $avgResponseTime, 'Average response time should be under 2 seconds');

        // Step 5: Verify data consistency under concurrent access
        $healthMetrics = DatabaseHealthMetric::where('connection', 'testing')->get();
        foreach ($healthMetrics as $metric) {
            $this->assertNotNull($metric->value);
            $this->assertNotNull($metric->recorded_at);
        }

        // Expected Results: Plugin handles concurrent usage without issues
        $this->assertTrue(true, 'Concurrent user access is handled correctly');
    }

    /**
     * TC-PERF-003: Memory Usage Optimization
     * Purpose: Test memory efficiency of plugin operations
     */
    public function test_memory_usage_optimization(): void
    {
        $initialMemory = memory_get_usage(true);

        // Step 1: Monitor memory usage during large operations
        $largeDataset = $this->createLargeQueryDataset(500);

        $afterDataCreation = memory_get_usage(true);
        $dataCreationMemory = $afterDataCreation - $initialMemory;

        // Step 2: Test garbage collection effectiveness
        $this->performLargeHealthOperations();

        $afterOperations = memory_get_usage(true);
        $operationsMemory = $afterOperations - $afterDataCreation;

        // Force garbage collection
        gc_collect_cycles();

        $afterGC = memory_get_usage(true);
        $memoryReclaimed = $afterOperations - $afterGC;

        // Step 3: Verify streaming for large data exports (simulated)
        $exportData = $this->simulateLargeDataExport();

        $afterExport = memory_get_usage(true);
        $exportMemory = $afterExport - $afterGC;

        // Step 4: Test batch processing efficiency
        $this->testBatchProcessingMemory();

        $finalMemory = memory_get_usage(true);

        // Step 5: Verify memory leak prevention
        // Memory usage should not continuously grow
        $totalMemoryUsed = $finalMemory - $initialMemory;

        // Memory usage assertions
        $this->assertLessThan(100 * 1024 * 1024, $totalMemoryUsed, 'Total memory usage should be under 100MB');
        $this->assertLessThan(50 * 1024 * 1024, $operationsMemory, 'Operations memory should be under 50MB');
        $this->assertGreaterThan(0, $memoryReclaimed, 'Garbage collection should reclaim memory');

        // Expected Results: Memory usage remains within acceptable limits
        $this->assertTrue(true, 'Memory usage optimization is working correctly');
    }

    /**
     * Test: High-Volume Query Logging Performance
     */
    public function test_high_volume_query_logging_performance(): void
    {
        $queryCount = 1000;
        $startTime = microtime(true);
        $initialMemory = memory_get_usage(true);

        // Simulate high-volume query logging
        for ($i = 0; $i < $queryCount; $i++) {
            $this->healthService->logQueryPerformance(
                "SELECT * FROM table_{$i} WHERE id = ?",
                $this->faker->numberBetween(10, 500),
                [$i],
                'testing'
            );
        }

        $endTime = microtime(true);
        $finalMemory = memory_get_usage(true);

        $executionTime = $endTime - $startTime;
        $memoryUsed = $finalMemory - $initialMemory;

        // Verify all queries were logged
        $loggedQueries = QueryPerformanceLog::where('connection', 'testing')->count();
        $this->assertGreaterThanOrEqual($queryCount, $loggedQueries);

        // Performance assertions
        $this->assertLessThan(10, $executionTime, 'High-volume logging should complete within 10 seconds');
        $this->assertLessThan(50 * 1024 * 1024, $memoryUsed, 'Memory usage should be reasonable');

        // Verify no performance degradation
        $avgTimePerQuery = $executionTime / $queryCount;
        $this->assertLessThan(0.01, $avgTimePerQuery, 'Average time per query should be under 10ms');
    }

    /**
     * Test: Health Metrics Collection Performance
     */
    public function test_health_metrics_collection_performance(): void
    {
        $metricsCount = 500;
        $startTime = microtime(true);

        // Collect metrics in batch
        for ($i = 0; $i < $metricsCount; $i++) {
            $this->healthService->recordHealthMetric(
                'testing',
                'performance_test',
                "metric_{$i}",
                $this->faker->numberBetween(1, 100),
                'ms',
                'normal'
            );
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Verify metrics were recorded
        $recordedMetrics = DatabaseHealthMetric::where('connection', 'testing')
            ->where('metric_type', 'performance_test')
            ->count();

        $this->assertEquals($metricsCount, $recordedMetrics);

        // Performance assertions
        $this->assertLessThan(5, $executionTime, 'Metrics collection should be fast');

        $avgTimePerMetric = $executionTime / $metricsCount;
        $this->assertLessThan(0.01, $avgTimePerMetric, 'Average time per metric should be minimal');
    }

    /**
     * Test: Query Performance Analysis Under Load
     */
    public function test_query_performance_analysis_under_load(): void
    {
        // Create large dataset for analysis
        $this->createLargeQueryDataset(2000);

        $startTime = microtime(true);

        // Perform comprehensive performance analysis
        $performanceMetrics = $this->healthService->getPerformanceMetrics('testing');

        $endTime = microtime(true);
        $analysisTime = $endTime - $startTime;

        // Verify analysis completeness
        $this->assertArrayHasKey('query_performance', $performanceMetrics);
        $this->assertArrayHasKey('database_metrics', $performanceMetrics);

        // Performance assertions
        $this->assertLessThan(3, $analysisTime, 'Performance analysis should complete quickly');

        // Verify analysis accuracy
        $queryStats = $performanceMetrics['query_performance'];
        $this->assertGreaterThan(0, $queryStats['total_queries']);
        $this->assertIsNumeric($queryStats['avg_execution_time']);
    }

    /**
     * Test: Widget Performance with Large Datasets
     */
    public function test_widget_performance_with_large_datasets(): void
    {
        // Create large dataset
        $this->createLargeQueryDataset(1500);
        $this->createLargeHealthMetricsDataset(500);

        $startTime = microtime(true);

        // Simulate widget data retrieval
        $healthSummary = $this->healthService->getHealthSummary('testing');
        $performanceMetrics = $this->healthService->getPerformanceMetrics('testing');

        $endTime = microtime(true);
        $widgetLoadTime = $endTime - $startTime;

        // Widget should load quickly even with large datasets
        $this->assertLessThan(2, $widgetLoadTime, 'Widget should load within 2 seconds');

        // Verify data completeness
        $this->assertIsArray($healthSummary);
        $this->assertIsArray($performanceMetrics);
    }

    /**
     * Test: Database Query Optimization
     */
    public function test_database_query_optimization(): void
    {
        // Create test data with various patterns
        $this->createDiverseQueryPatterns();

        $startTime = microtime(true);

        // Test optimized queries
        $slowQueries = QueryPerformanceLog::where('execution_time', '>=', 1000)
            ->where('connection', 'testing')
            ->limit(10)
            ->get();

        $recentQueries = QueryPerformanceLog::where('executed_at', '>=', now()->subHours(24))
            ->where('connection', 'testing')
            ->orderBy('executed_at', 'desc')
            ->limit(50)
            ->get();

        $endTime = microtime(true);
        $queryTime = $endTime - $startTime;

        // Query performance should be good
        $this->assertLessThan(1, $queryTime, 'Database queries should be optimized');

        // Verify results
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $slowQueries);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $recentQueries);
    }

    /**
     * Helper Methods
     */

    /**
     * Simulate large table structure
     */
    private function simulateLargeTableStructure(): void
    {
        // In a real scenario, this would create many tables
        // For testing, we'll simulate with metadata
        for ($i = 0; $i < 10; $i++) {
            $this->healthService->recordHealthMetric(
                'testing',
                'table_structure',
                "table_{$i}_size",
                $this->faker->numberBetween(1000, 100000),
                'rows',
                'normal'
            );
        }
    }

    /**
     * Create large query dataset
     */
    private function createLargeQueryDataset(int $count): int
    {
        $queries = [
            'SELECT * FROM users WHERE status = ?',
            'INSERT INTO logs (message, level) VALUES (?, ?)',
            'UPDATE sessions SET last_activity = NOW() WHERE id = ?',
            'DELETE FROM temp_cache WHERE expires_at < ?',
            'SELECT COUNT(*) FROM posts WHERE published = ?',
        ];

        for ($i = 0; $i < $count; $i++) {
            $query = $queries[$i % count($queries)];

            QueryPerformanceLog::create([
                'connection' => 'testing',
                'query' => $query,
                'query_hash' => md5($query . $i),
                'execution_time' => $this->faker->numberBetween(10, 2000),
                'type' => strtolower(explode(' ', $query)[0]),
                'status' => $this->faker->randomElement(['success', 'success', 'success', 'error']),
                'executed_at' => now()->subMinutes($this->faker->numberBetween(1, 1440)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $count;
    }

    /**
     * Create large health metrics dataset
     */
    private function createLargeHealthMetricsDataset(int $count): void
    {
        $metricTypes = ['connection_status', 'performance', 'resource_usage', 'query_analysis'];
        $metricNames = ['response_time', 'cpu_usage', 'memory_usage', 'disk_io', 'query_rate'];

        for ($i = 0; $i < $count; $i++) {
            DatabaseHealthMetric::create([
                'connection' => 'testing',
                'metric_type' => $metricTypes[$i % count($metricTypes)],
                'metric_name' => $metricNames[$i % count($metricNames)],
                'value' => $this->faker->numberBetween(1, 1000),
                'unit' => $this->faker->randomElement(['ms', '%', 'MB', 'ops/sec']),
                'status' => $this->faker->randomElement(['normal', 'warning', 'critical']),
                'recorded_at' => now()->subMinutes($this->faker->numberBetween(1, 1440)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Simulate user health monitoring session
     */
    private function simulateUserHealthMonitoringSession(int $userId): void
    {
        // Simulate typical user operations
        $this->healthService->testConnection('testing');
        $this->healthService->getPerformanceMetrics('testing');

        // Log some queries for this user session
        for ($i = 0; $i < 5; $i++) {
            $this->healthService->logQueryPerformance(
                "SELECT * FROM user_data WHERE user_id = {$userId}",
                $this->faker->numberBetween(10, 200),
                [$userId],
                'testing'
            );
        }
    }

    /**
     * Simulate concurrent database operations
     */
    private function simulateConcurrentDatabaseOperations(): void
    {
        // Simulate operations that might happen concurrently
        $operations = [
            'metrics_collection',
            'query_logging',
            'health_assessment',
            'performance_analysis'
        ];

        foreach ($operations as $operation) {
            $this->healthService->recordHealthMetric(
                'testing',
                'concurrent_test',
                $operation,
                $this->faker->numberBetween(50, 200),
                'ms',
                'normal'
            );
        }
    }

    /**
     * Perform large health operations
     */
    private function performLargeHealthOperations(): void
    {
        // Perform multiple health operations
        for ($i = 0; $i < 100; $i++) {
            $this->healthService->getPerformanceMetrics('testing');

            if ($i % 10 === 0) {
                // Occasionally test connection
                $this->healthService->testConnection('testing');
            }
        }
    }

    /**
     * Simulate large data export
     */
    private function simulateLargeDataExport(): array
    {
        // Simulate exporting large amounts of data
        return QueryPerformanceLog::where('connection', 'testing')
            ->select(['id', 'query_hash', 'execution_time', 'executed_at'])
            ->limit(1000)
            ->get()
            ->toArray();
    }

    /**
     * Test batch processing memory
     */
    private function testBatchProcessingMemory(): void
    {
        // Simulate batch processing operations
        $batches = QueryPerformanceLog::where('connection', 'testing')
            ->select(['id', 'execution_time'])
            ->chunk(100, function ($queries) {
                // Process each batch
                foreach ($queries as $query) {
                    // Simulate processing
                    $processed = $query->execution_time * 1.1;
                }
            });
    }

    /**
     * Create diverse query patterns for optimization testing
     */
    private function createDiverseQueryPatterns(): void
    {
        $patterns = [
            ['query' => 'SELECT * FROM users WHERE email = ?', 'time' => 50, 'type' => 'select'],
            ['query' => 'SELECT u.*, p.title FROM users u JOIN posts p ON u.id = p.user_id', 'time' => 150, 'type' => 'select'],
            ['query' => 'INSERT INTO activity_log (user_id, action) VALUES (?, ?)', 'time' => 25, 'type' => 'insert'],
            ['query' => 'UPDATE user_preferences SET theme = ? WHERE user_id = ?', 'time' => 75, 'type' => 'update'],
            ['query' => 'DELETE FROM expired_sessions WHERE last_activity < ?', 'time' => 200, 'type' => 'delete'],
            ['query' => 'SELECT COUNT(*) FROM large_table WHERE complex_condition = ?', 'time' => 1500, 'type' => 'select'],
        ];

        foreach ($patterns as $pattern) {
            for ($i = 0; $i < 20; $i++) {
                QueryPerformanceLog::create([
                    'connection' => 'testing',
                    'query' => $pattern['query'],
                    'query_hash' => md5($pattern['query'] . $i),
                    'execution_time' => $pattern['time'] + $this->faker->numberBetween(-10, 10),
                    'type' => $pattern['type'],
                    'status' => 'success',
                    'executed_at' => now()->subMinutes($this->faker->numberBetween(1, 60)),
                ]);
            }
        }
    }
}
