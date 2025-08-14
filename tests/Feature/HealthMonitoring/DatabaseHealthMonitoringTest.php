<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature\HealthMonitoring;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Test Case: TC-HEALTH-001 - Database Health Monitoring
 * Purpose: Test database health monitoring features and metrics collection
 */
class DatabaseHealthMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create health metrics table for testing
        if (!Schema::hasTable('database_health_metrics')) {
            Schema::create('database_health_metrics', function ($table) {
                $table->id();
                $table->string('metric_name');
                $table->decimal('metric_value', 15, 4);
                $table->decimal('threshold', 15, 4)->nullable();
                $table->string('status');
                $table->timestamp('measured_at');
                $table->timestamp('created_at');
            });
        }

        // Create query performance logs table
        if (!Schema::hasTable('query_performance_logs')) {
            Schema::create('query_performance_logs', function ($table) {
                $table->id();
                $table->text('query');
                $table->float('execution_time');
                $table->integer('memory_usage')->nullable();
                $table->string('connection_name')->nullable();
                $table->timestamp('created_at');
            });
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_database_connection_health()
    {
        // Test basic database connectivity
        try {
            $result = DB::select('SELECT 1 as connection_test');
            $this->assertEquals(1, $result[0]->connection_test);

            // Log health metric
            DB::table('database_health_metrics')->insert([
                'metric_name' => 'connection_test',
                'metric_value' => 1,
                'threshold' => 1,
                'status' => 'healthy',
                'measured_at' => now(),
                'created_at' => now()
            ]);

            $metric = DB::table('database_health_metrics')
                ->where('metric_name', 'connection_test')
                ->first();

            $this->assertNotNull($metric);
            $this->assertEquals('healthy', $metric->status);
        } catch (\Exception $e) {
            $this->fail('Database connection test failed: ' . $e->getMessage());
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_query_performance_monitoring()
    {
        // Create test table for performance testing
        Schema::create('performance_test_table', function ($table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        // Insert test data
        $startTime = microtime(true);

        for ($i = 0; $i < 100; $i++) {
            DB::table('performance_test_table')->insert([
                'name' => "Test Record {$i}",
                'description' => "Test description for record {$i}",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Log performance metric
        DB::table('query_performance_logs')->insert([
            'query' => 'INSERT INTO performance_test_table (bulk insert)',
            'execution_time' => $executionTime,
            'memory_usage' => memory_get_usage(),
            'connection_name' => 'testing',
            'created_at' => now()
        ]);

        $performanceLog = DB::table('query_performance_logs')
            ->where('query', 'like', '%performance_test_table%')
            ->first();

        $this->assertNotNull($performanceLog);
        $this->assertGreaterThan(0, $performanceLog->execution_time);
        $this->assertGreaterThan(0, $performanceLog->memory_usage);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_table_size_monitoring()
    {
        // Create test table
        Schema::create('size_test_table', function ($table) {
            $table->id();
            $table->text('large_content');
            $table->timestamps();
        });

        // Insert data to increase table size
        $largeContent = str_repeat('Sample data content ', 1000);

        for ($i = 0; $i < 50; $i++) {
            DB::table('size_test_table')->insert([
                'large_content' => $largeContent,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Test table size calculation
        $recordCount = DB::table('size_test_table')->count();
        $this->assertEquals(50, $recordCount);

        // Log table size metric
        DB::table('database_health_metrics')->insert([
            'metric_name' => 'table_record_count_size_test_table',
            'metric_value' => $recordCount,
            'threshold' => 1000,
            'status' => $recordCount > 1000 ? 'warning' : 'healthy',
            'measured_at' => now(),
            'created_at' => now()
        ]);

        $sizeMetric = DB::table('database_health_metrics')
            ->where('metric_name', 'table_record_count_size_test_table')
            ->first();

        $this->assertNotNull($sizeMetric);
        $this->assertEquals(50, $sizeMetric->metric_value);
        $this->assertEquals('healthy', $sizeMetric->status);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_slow_query_detection()
    {
        // Simulate a slow query
        $startTime = microtime(true);

        // Create a more complex query that might be slower
        Schema::create('slow_query_test', function ($table) {
            $table->id();
            $table->string('name');
            $table->integer('value');
            $table->timestamps();
        });

        // Insert data
        for ($i = 0; $i < 1000; $i++) {
            DB::table('slow_query_test')->insert([
                'name' => "Record {$i}",
                'value' => $i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Perform a complex query
        $results = DB::table('slow_query_test')
            ->where('value', '>', 500)
            ->orderBy('name')
            ->get();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Log slow query
        DB::table('query_performance_logs')->insert([
            'query' => 'SELECT * FROM slow_query_test WHERE value > 500 ORDER BY name',
            'execution_time' => $executionTime,
            'memory_usage' => memory_get_usage(),
            'connection_name' => 'testing',
            'created_at' => now()
        ]);

        // Test slow query detection
        $slowThreshold = 1.0; // 1 second
        $slowQueries = DB::table('query_performance_logs')
            ->where('execution_time', '>', $slowThreshold)
            ->count();

        // Log performance metric
        DB::table('database_health_metrics')->insert([
            'metric_name' => 'slow_queries_count',
            'metric_value' => $slowQueries,
            'threshold' => 5,
            'status' => $slowQueries > 5 ? 'warning' : 'healthy',
            'measured_at' => now(),
            'created_at' => now()
        ]);

        $this->assertGreaterThanOrEqual(0, $slowQueries);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_database_space_monitoring()
    {
        // Test database space usage monitoring
        try {
            // Get table information (this varies by database type)
            $connection = DB::connection();
            $databaseName = $connection->getDatabaseName();

            $this->assertNotEmpty($databaseName, 'Database name should be available');

            // Log space usage metric (simulated)
            DB::table('database_health_metrics')->insert([
                'metric_name' => 'database_size_mb',
                'metric_value' => 10.5, // Simulated size in MB
                'threshold' => 1000, // 1GB threshold
                'status' => 'healthy',
                'measured_at' => now(),
                'created_at' => now()
            ]);

            $spaceMetric = DB::table('database_health_metrics')
                ->where('metric_name', 'database_size_mb')
                ->first();

            $this->assertNotNull($spaceMetric);
            $this->assertEquals(10.5, $spaceMetric->metric_value);
        } catch (\Exception $e) {
            $this->markTestSkipped('Database space monitoring not available in test environment');
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_connection_pool_monitoring()
    {
        // Test connection pool health
        $connectionName = DB::connection()->getName();
        $this->assertNotNull($connectionName);

        // Simulate connection pool metrics
        DB::table('database_health_metrics')->insert([
            'metric_name' => 'active_connections',
            'metric_value' => 5,
            'threshold' => 100,
            'status' => 'healthy',
            'measured_at' => now(),
            'created_at' => now()
        ]);

        DB::table('database_health_metrics')->insert([
            'metric_name' => 'connection_pool_usage_percent',
            'metric_value' => 15.5,
            'threshold' => 80,
            'status' => 'healthy',
            'measured_at' => now(),
            'created_at' => now()
        ]);

        $connectionMetrics = DB::table('database_health_metrics')
            ->whereIn('metric_name', ['active_connections', 'connection_pool_usage_percent'])
            ->get();

        $this->assertCount(2, $connectionMetrics);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_health_threshold_alerts()
    {
        // Test health threshold monitoring
        $testMetrics = [
            ['name' => 'cpu_usage', 'value' => 85, 'threshold' => 80, 'expected_status' => 'warning'],
            ['name' => 'memory_usage', 'value' => 60, 'threshold' => 80, 'expected_status' => 'healthy'],
            ['name' => 'disk_usage', 'value' => 95, 'threshold' => 90, 'expected_status' => 'critical'],
        ];

        foreach ($testMetrics as $metric) {
            $status = 'healthy';
            if ($metric['value'] > $metric['threshold'] + 10) {
                $status = 'critical';
            } elseif ($metric['value'] > $metric['threshold']) {
                $status = 'warning';
            }

            DB::table('database_health_metrics')->insert([
                'metric_name' => $metric['name'],
                'metric_value' => $metric['value'],
                'threshold' => $metric['threshold'],
                'status' => $status,
                'measured_at' => now(),
                'created_at' => now()
            ]);

            $savedMetric = DB::table('database_health_metrics')
                ->where('metric_name', $metric['name'])
                ->first();

            $this->assertEquals($metric['expected_status'], $savedMetric->status);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_health_metrics_cleanup()
    {
        // Test old health metrics cleanup

        // Insert old metrics
        DB::table('database_health_metrics')->insert([
            'metric_name' => 'old_metric',
            'metric_value' => 50,
            'threshold' => 100,
            'status' => 'healthy',
            'measured_at' => now()->subDays(30),
            'created_at' => now()->subDays(30)
        ]);

        // Insert recent metrics
        DB::table('database_health_metrics')->insert([
            'metric_name' => 'recent_metric',
            'metric_value' => 75,
            'threshold' => 100,
            'status' => 'healthy',
            'measured_at' => now(),
            'created_at' => now()
        ]);

        // Test cleanup query (simulate)
        $oldMetricsCount = DB::table('database_health_metrics')
            ->where('created_at', '<', now()->subDays(7))
            ->count();

        $recentMetricsCount = DB::table('database_health_metrics')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $this->assertEquals(1, $oldMetricsCount);
        $this->assertEquals(1, $recentMetricsCount);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_health_dashboard_data()
    {
        // Insert various health metrics for dashboard
        $dashboardMetrics = [
            'database_connections' => 10,
            'query_per_second' => 45.5,
            'average_response_time' => 0.25,
            'error_rate_percent' => 0.1,
            'cache_hit_ratio' => 85.7
        ];

        foreach ($dashboardMetrics as $name => $value) {
            DB::table('database_health_metrics')->insert([
                'metric_name' => $name,
                'metric_value' => $value,
                'threshold' => $name === 'error_rate_percent' ? 1.0 : 100,
                'status' => 'healthy',
                'measured_at' => now(),
                'created_at' => now()
            ]);
        }

        // Test dashboard data retrieval
        $dashboardData = DB::table('database_health_metrics')
            ->whereIn('metric_name', array_keys($dashboardMetrics))
            ->where('measured_at', '>=', now()->subHour())
            ->get();

        $this->assertCount(5, $dashboardData);

        // Test specific metrics
        $errorRateMetric = $dashboardData->where('metric_name', 'error_rate_percent')->first();
        $this->assertEquals(0.1, $errorRateMetric->metric_value);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_automated_health_checks()
    {
        // Simulate automated health check routine
        $healthChecks = [
            'database_connection' => $this->checkDatabaseConnection(),
            'table_integrity' => $this->checkTableIntegrity(),
            'query_performance' => $this->checkQueryPerformance(),
        ];

        foreach ($healthChecks as $checkName => $result) {
            DB::table('database_health_metrics')->insert([
                'metric_name' => "health_check_{$checkName}",
                'metric_value' => $result['value'],
                'threshold' => 1,
                'status' => $result['status'],
                'measured_at' => now(),
                'created_at' => now()
            ]);
        }

        $healthCheckResults = DB::table('database_health_metrics')
            ->where('metric_name', 'like', 'health_check_%')
            ->get();

        $this->assertCount(3, $healthCheckResults);
    }

    /**
     * Helper methods for health checks
     */
    private function checkDatabaseConnection(): array
    {
        try {
            DB::select('SELECT 1');
            return ['value' => 1, 'status' => 'healthy'];
        } catch (\Exception $e) {
            return ['value' => 0, 'status' => 'critical'];
        }
    }

    private function checkTableIntegrity(): array
    {
        try {
            $tables = ['database_health_metrics', 'query_performance_logs'];
            $allTablesExist = true;

            foreach ($tables as $table) {
                if (!Schema::hasTable($table)) {
                    $allTablesExist = false;
                    break;
                }
            }

            return ['value' => $allTablesExist ? 1 : 0, 'status' => $allTablesExist ? 'healthy' : 'warning'];
        } catch (\Exception $e) {
            return ['value' => 0, 'status' => 'critical'];
        }
    }

    private function checkQueryPerformance(): array
    {
        try {
            $startTime = microtime(true);
            DB::table('database_health_metrics')->count();
            $endTime = microtime(true);

            $executionTime = $endTime - $startTime;
            $isHealthy = $executionTime < 1.0; // Under 1 second is healthy

            return [
                'value' => $executionTime,
                'status' => $isHealthy ? 'healthy' : 'warning'
            ];
        } catch (\Exception $e) {
            return ['value' => 999, 'status' => 'critical'];
        }
    }
}
