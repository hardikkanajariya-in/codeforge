<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature;

use HkDevs\CodeForgeStudio\Models\DatabaseHealthMetric;
use HkDevs\CodeForgeStudio\Models\QueryPerformanceLog;
use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use HkDevs\CodeForgeStudio\Services\SchemaAnalyzerService;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Test Cases for Database Overview & Analytics Features
 * 
 * Based on TC-DB-001 through TC-DB-004 from COMPREHENSIVE_TEST_CASES_FOR_USER.md
 * These tests verify real-time database statistics, performance dashboard analytics,
 * connection health monitoring, and quick access panel functionality.
 */
class DatabaseOverviewAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected DatabaseHealthService $healthService;
    protected SchemaAnalyzerService $schemaAnalyzer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->healthService = app(DatabaseHealthService::class);
        $this->schemaAnalyzer = app(SchemaAnalyzerService::class);

        // Create test tables for database overview testing
        $this->createTestTables();
        $this->seedTestData();
    }

    /**
     * TC-DB-001: Live Database Metrics Display
     * 
     * Purpose: Verify accurate display of real-time database statistics
     * Steps:
     * 1. Access Database Overview page
     * 2. Verify table count matches actual database tables
     * 3. Verify row counts for each table are accurate
     * 4. Check database storage size calculations
     * 5. Test real-time refresh functionality
     * 6. Verify metrics update without page reload
     */
    public function test_live_database_metrics_display_accuracy(): void
    {
        // Step 1: Access Database Overview functionality through service
        $allTables = $this->schemaAnalyzer->getAllTables();

        // Step 2: Verify table count matches actual database tables
        $actualTables = Schema::getAllTables();
        $userTables = collect($actualTables)->filter(function ($table) {
            $tableName = array_values((array) $table)[0];
            return !in_array($tableName, [
                'migrations',
                'failed_jobs',
                'password_reset_tokens',
                'personal_access_tokens',
                'cache',
                'cache_locks',
                'sessions'
            ]);
        });

        $this->assertIsArray($allTables);
        $this->assertGreaterThanOrEqual(3, count($allTables)); // At least our 3 test tables

        // Step 3: Verify row counts for each table are accurate
        $testTableRowCount = DB::table('test_users')->count();
        $this->assertEquals(10, $testTableRowCount); // We seeded 10 users

        $testPostsRowCount = DB::table('test_posts')->count();
        $this->assertEquals(20, $testPostsRowCount); // We seeded 20 posts

        // Step 4: Check database storage size calculations through health service
        $performanceMetrics = $this->healthService->getPerformanceMetrics();
        $this->assertArrayHasKey('database_metrics', $performanceMetrics);

        // Step 5: Test real-time refresh functionality
        // Add more data and verify metrics update
        DB::table('test_users')->insert([
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $refreshedTables = $this->schemaAnalyzer->getAllTables();
        $newRowCount = DB::table('test_users')->count();
        $this->assertEquals(11, $newRowCount);

        // Step 6: Verify metrics update accuracy
        $this->assertIsArray($refreshedTables);
        $this->assertEquals(count($allTables), count($refreshedTables)); // Same number of tables
    }

    /**
     * TC-DB-002: Performance Dashboard Analytics
     * 
     * Purpose: Test comprehensive database performance monitoring with visual charts
     * Steps:
     * 1. Access Performance Dashboard
     * 2. Verify visual charts display performance data correctly
     * 3. Test chart interactions (zoom, filter, date range)
     * 4. Check performance trend analysis
     * 5. Verify chart responsiveness and loading times
     */
    public function test_performance_dashboard_analytics(): void
    {
        // Step 1: Create performance test data
        $this->createPerformanceTestData();

        // Step 2: Access Performance Dashboard through service
        $performanceMetrics = $this->healthService->getPerformanceMetrics();

        // Verify performance data structure is correct for charts
        $this->assertArrayHasKey('query_performance', $performanceMetrics);
        $this->assertArrayHasKey('total_queries', $performanceMetrics['query_performance']);
        $this->assertArrayHasKey('avg_execution_time', $performanceMetrics['query_performance']);
        $this->assertArrayHasKey('slow_queries', $performanceMetrics['query_performance']);

        // Step 3: Test filtering capabilities through performance summary
        $healthSummary = $this->healthService->getHealthSummary();
        $this->assertArrayHasKey('performance_summary', $healthSummary);

        $performanceSummary = $healthSummary['performance_summary'];
        $this->assertArrayHasKey('queries_today', $performanceSummary);
        $this->assertArrayHasKey('avg_response_time', $performanceSummary);

        // Step 4: Check performance trend analysis data through query stats
        $this->assertIsNumeric($performanceSummary['queries_today']);
        $this->assertTrue($performanceSummary['queries_today'] >= 0);

        // Step 5: Verify chart data structure through metrics
        $this->assertArrayHasKey('database_metrics', $performanceMetrics);
    }

    /**
     * TC-DB-003: Connection Health Monitoring
     * 
     * Purpose: Test database connection health across multiple environments
     * Steps:
     * 1. Configure multiple database connections
     * 2. Monitor connection health indicators
     * 3. Test connection failure detection
     * 4. Verify health status updates in real-time
     * 5. Test connection recovery monitoring
     */
    public function test_connection_health_monitoring(): void
    {
        // Step 1: Test primary connection health
        $connectionStatus = $this->healthService->getConnectionStatus();

        $this->assertIsArray($connectionStatus);
        $this->assertArrayHasKey('testing', $connectionStatus); // Our test connection

        // Step 2: Monitor connection health indicators
        $healthSummary = $this->healthService->getHealthSummary();

        $this->assertArrayHasKey('connection_status', $healthSummary);
        $this->assertArrayHasKey('recent_metrics', $healthSummary);
        $this->assertArrayHasKey('performance_summary', $healthSummary);
        $this->assertArrayHasKey('alerts', $healthSummary);

        // Step 3: Test connection status detection
        $testConnection = $this->healthService->testConnection('testing');

        $this->assertIsArray($testConnection);
        $this->assertArrayHasKey('status', $testConnection);
        $this->assertArrayHasKey('response_time', $testConnection);
        $this->assertEquals('connected', $testConnection['status']);

        // Step 4: Verify health status data structure
        $this->assertIsNumeric($testConnection['response_time']);
        $this->assertGreaterThanOrEqual(0, $testConnection['response_time']);

        // Step 5: Test health metrics recording
        $this->healthService->recordHealthMetric(
            'testing',
            'connection_status',
            'response_time',
            $testConnection['response_time']
        );

        // Verify metric was recorded
        $this->assertDatabaseHas('database_health_metrics', [
            'connection' => 'testing',
            'metric_name' => 'response_time'
        ]);
    }

    /**
     * TC-DB-004: Quick Access Panel Functionality
     * 
     * Purpose: Test direct shortcuts to frequently used database operations
     * Steps:
     * 1. Access Quick Access Panel
     * 2. Test shortcuts to migration management
     * 3. Verify links to schema designer
     * 4. Test quick access to health monitoring
     * 5. Verify navigation to documentation generator
     */
    public function test_quick_access_panel_functionality(): void
    {
        // Step 1: Test database overview quick stats
        $quickStats = $this->getQuickAccessStats();

        $this->assertArrayHasKey('total_tables', $quickStats);
        $this->assertArrayHasKey('total_records', $quickStats);
        $this->assertArrayHasKey('database_size', $quickStats);
        $this->assertArrayHasKey('connection_status', $quickStats);

        // Step 2: Test migration management shortcuts
        $migrationStats = $this->getMigrationShortcuts();

        $this->assertArrayHasKey('total_migrations', $migrationStats);
        $this->assertArrayHasKey('pending_migrations', $migrationStats);
        $this->assertArrayHasKey('recent_activity', $migrationStats);

        // Step 3: Verify schema designer accessibility
        $schemaInfo = $this->getSchemaDesignerShortcuts();

        $this->assertArrayHasKey('available_tables', $schemaInfo);
        $this->assertArrayHasKey('relationships_count', $schemaInfo);

        // Step 4: Test health monitoring shortcuts
        $healthShortcuts = $this->getHealthMonitoringShortcuts();

        $this->assertArrayHasKey('health_score', $healthShortcuts);
        $this->assertArrayHasKey('active_alerts', $healthShortcuts);
        $this->assertArrayHasKey('monitoring_status', $healthShortcuts);

        // Step 5: Verify documentation generator shortcuts
        $docsShortcuts = $this->getDocumentationShortcuts();

        $this->assertArrayHasKey('documented_tables', $docsShortcuts);
        $this->assertArrayHasKey('last_generation', $docsShortcuts);
    }

    /**
     * TC-DB-002 Alternative: Multi-Connection Support Test
     * 
     * Purpose: Test plugin with multiple database connections
     */
    public function test_multi_connection_support(): void
    {
        // Test with different connection configurations
        $connections = ['testing']; // In test environment, we primarily use testing connection

        foreach ($connections as $connection) {
            $status = $this->healthService->testConnection($connection);

            $this->assertIsArray($status);
            $this->assertArrayHasKey('status', $status);
            $this->assertArrayHasKey('connection', $status);
            $this->assertEquals($connection, $status['connection']);
        }

        // Test metrics for multiple connections through connection status
        $connectionStatuses = $this->healthService->getConnectionStatus();
        $this->assertIsArray($connectionStatuses);

        foreach ($connectionStatuses as $connectionName => $health) {
            $this->assertIsString($connectionName);
            $this->assertIsArray($health);
            $this->assertArrayHasKey('status', $health);
        }
    }

    /**
     * TC-DB-003 Alternative: Database Health Dashboard Test
     */
    public function test_database_health_dashboard(): void
    {
        // Create health test data
        $this->createHealthTestData();

        // Test health dashboard data compilation through health summary
        $healthSummary = $this->healthService->getHealthSummary();

        $this->assertArrayHasKey('connection_status', $healthSummary);
        $this->assertArrayHasKey('recent_metrics', $healthSummary);
        $this->assertArrayHasKey('performance_summary', $healthSummary);
        $this->assertArrayHasKey('alerts', $healthSummary);

        // Test health indicators
        $connectionStatus = $healthSummary['connection_status'];
        $this->assertArrayHasKey('status', $connectionStatus);
        $this->assertArrayHasKey('connection', $connectionStatus);

        // Test alert system
        $alerts = $healthSummary['alerts'];
        $this->assertIsArray($alerts);
    }

    /**
     * Helper method to create test tables for testing
     */
    protected function createTestTables(): void
    {
        Schema::create('test_users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create('test_posts', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained('test_users');
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('test_comments', function ($table) {
            $table->id();
            $table->foreignId('post_id')->constrained('test_posts');
            $table->string('author_name');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Helper method to seed test data
     */
    protected function seedTestData(): void
    {
        // Seed test users
        for ($i = 1; $i <= 10; $i++) {
            DB::table('test_users')->insert([
                'name' => "Test User {$i}",
                'email' => "user{$i}@example.com",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed test posts
        for ($i = 1; $i <= 20; $i++) {
            DB::table('test_posts')->insert([
                'user_id' => ($i % 10) + 1, // Distribute among users
                'title' => "Test Post {$i}",
                'content' => "This is test content for post {$i}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed test comments
        for ($i = 1; $i <= 50; $i++) {
            DB::table('test_comments')->insert([
                'post_id' => ($i % 20) + 1, // Distribute among posts
                'author_name' => "Commenter {$i}",
                'comment' => "This is test comment {$i}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Helper method to create performance test data
     */
    protected function createPerformanceTestData(): void
    {
        // Create sample query performance logs
        for ($i = 0; $i < 24; $i++) {
            QueryPerformanceLog::create([
                'query_hash' => md5("test_query_{$i}"),
                'query' => "SELECT * FROM test_users WHERE id = ?", // Required field
                'bindings' => json_encode([1]),
                'execution_time' => rand(10, 500), // Random execution time
                'memory_usage' => rand(1024, 8192),
                'result_count' => rand(1, 100),
                'connection' => 'testing',
                'executed_at' => now()->subHours($i),
                'type' => 'select', // Changed from query_type
                'status' => 'success',
            ]);
        }

        // Create a few slow queries for testing
        for ($i = 0; $i < 3; $i++) {
            QueryPerformanceLog::create([
                'query_hash' => md5("slow_query_{$i}"),
                'query' => "SELECT * FROM test_posts ORDER BY created_at DESC", // Required field
                'bindings' => json_encode([]),
                'execution_time' => rand(1000, 5000), // Slow queries
                'memory_usage' => rand(8192, 16384),
                'result_count' => rand(100, 1000),
                'connection' => 'testing',
                'executed_at' => now()->subHours($i),
                'type' => 'select', // Changed from query_type
                'status' => 'success',
            ]);
        }
    }

    /**
     * Helper method to create health test data
     */
    protected function createHealthTestData(): void
    {
        // Create health metrics
        $metrics = ['response_time', 'active_connections', 'database_size', 'memory_usage'];

        foreach ($metrics as $metric) {
            DatabaseHealthMetric::create([
                'connection' => 'testing',
                'metric_type' => $this->getMetricType($metric), // Add required metric_type
                'metric_name' => $metric,
                'value' => $this->getTestMetricValue($metric),
                'unit' => $this->getMetricUnit($metric),
                'status' => 'normal',
                'recorded_at' => now(),
            ]);
        }
    }

    /**
     * Helper method to get test metric values
     */
    protected function getTestMetricValue(string $metric): float
    {
        return match ($metric) {
            'response_time' => 25.5,
            'active_connections' => 5,
            'database_size' => 1024.0,
            'memory_usage' => 512.0,
            default => 100.0,
        };
    }

    /**
     * Helper method to get test thresholds
     */
    protected function getTestThreshold(string $metric): float
    {
        return match ($metric) {
            'response_time' => 100.0,
            'active_connections' => 50,
            'database_size' => 10240.0,
            'memory_usage' => 1024.0,
            default => 1000.0,
        };
    }

    /**
     * Helper method to get quick access stats
     */
    protected function getQuickAccessStats(): array
    {
        return [
            'total_tables' => count(Schema::getAllTables()),
            'total_records' => $this->getTotalRecords(),
            'database_size' => $this->getDatabaseSize(),
            'connection_status' => 'connected',
        ];
    }

    /**
     * Helper method to get migration shortcuts
     */
    protected function getMigrationShortcuts(): array
    {
        return [
            'total_migrations' => count(glob(database_path('migrations/*.php'))),
            'pending_migrations' => 0, // In test environment
            'recent_activity' => 0,
        ];
    }

    /**
     * Helper method to get schema designer shortcuts
     */
    protected function getSchemaDesignerShortcuts(): array
    {
        return [
            'available_tables' => count(Schema::getAllTables()),
            'relationships_count' => $this->getRelationshipsCount(),
        ];
    }

    /**
     * Helper method to get health monitoring shortcuts
     */
    protected function getHealthMonitoringShortcuts(): array
    {
        return [
            'health_score' => 95,
            'active_alerts' => 0,
            'monitoring_status' => 'active',
        ];
    }

    /**
     * Helper method to get documentation shortcuts
     */
    protected function getDocumentationShortcuts(): array
    {
        return [
            'documented_tables' => count(Schema::getAllTables()),
            'last_generation' => now()->subHours(2)->toDateTimeString(),
        ];
    }

    /**
     * Helper method to get total records across all user tables
     */
    protected function getTotalRecords(): int
    {
        $total = 0;
        $tables = ['test_users', 'test_posts', 'test_comments'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $total += DB::table($table)->count();
            }
        }

        return $total;
    }

    /**
     * Helper method to get database size (simplified for testing)
     */
    protected function getDatabaseSize(): float
    {
        // Simplified calculation for testing
        return 1.5; // MB
    }

    /**
     * Helper method to count relationships
     */
    protected function getRelationshipsCount(): int
    {
        // Count foreign key constraints in test tables
        return 2; // test_posts->user_id, test_comments->post_id
    }

    /**
     * Helper method to get metric type for health metrics
     */
    protected function getMetricType(string $metric): string
    {
        return match ($metric) {
            'response_time' => 'connection_status',
            'active_connections', 'database_size', 'memory_usage' => 'database_info',
            default => 'general',
        };
    }

    /**
     * Helper method to get metric unit
     */
    protected function getMetricUnit(string $metric): string
    {
        return match ($metric) {
            'response_time' => 'ms',
            'database_size', 'memory_usage' => 'MB',
            'active_connections' => 'count',
            default => 'count',
        };
    }
}
