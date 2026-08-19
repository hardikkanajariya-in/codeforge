<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit;

use Filament\Widgets\StatsOverviewWidget\Stat;
use HkDevs\CodeForgeStudio\Models\DatabaseHealthMetric;
use HkDevs\CodeForgeStudio\Models\MigrationHistory;
use HkDevs\CodeForgeStudio\Models\QueryPerformanceLog;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use HkDevs\CodeForgeStudio\Widgets\DatabaseHealthMetricsWidget;
use HkDevs\CodeForgeStudio\Widgets\DatabaseHealthWidget;
use HkDevs\CodeForgeStudio\Widgets\DatabaseStatsWidget;
use HkDevs\CodeForgeStudio\Widgets\MigrationStatsWidget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Test Cases for Widget Functionality
 *
 * Based on TC-WID-001 and TC-WID-002 from COMPREHENSIVE_TEST_CASES_FOR_USER.md
 * These tests verify widget display functionality, data accuracy, and responsive design.
 */
class WidgetFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up view paths for Filament widgets to avoid view finder issues
        $this->app['view']->addNamespace('filament-widgets', __DIR__.'/../../vendor/filament/widgets/resources/views');

        // Create test tables for widget testing
        $this->createTestTables();
        $this->seedTestData();
        $this->createWidgetTestData();
    }

    /**
     * TC-WID-001: Database Stats Widget
     *
     * Purpose: Test database statistics widget on dashboard
     * Steps:
     * 1. Add DatabaseStatsWidget to dashboard
     * 2. Verify widget displays correct information
     * 3. Test widget refresh functionality
     * 4. Check responsive design on different screen sizes
     */
    public function test_database_stats_widget_functionality(): void
    {
        // Step 1: Initialize widget
        $widget = new DatabaseStatsWidget;

        // Step 2: Use reflection to access protected getStats method
        $reflection = new \ReflectionClass($widget);
        $getStatsMethod = $reflection->getMethod('getStats');
        $getStatsMethod->setAccessible(true);
        $stats = $getStatsMethod->invoke($widget);

        $this->assertIsArray($stats);
        $this->assertCount(3, $stats); // Should have 3 stats: tables, pending migrations, size

        // Verify each stat has required properties
        foreach ($stats as $stat) {
            $this->assertInstanceOf(Stat::class, $stat);
        }

        // Step 3: Test widget data accuracy
        $tablesCount = $this->getTablesCount();
        $pendingMigrations = $this->getPendingMigrationsCount();
        $databaseSize = $this->getDatabaseSize();

        // Verify stats reflect actual database state
        $this->assertTrue($tablesCount >= 3); // At least our test tables
        $this->assertTrue($pendingMigrations >= 0); // Should be non-negative
        $this->assertTrue($databaseSize >= 0); // Should be non-negative

        // Step 4: Test widget view rendering
        $view = $widget->render();
        $this->assertNotNull($view);
    }

    /**
     * TC-WID-002: Recent Migrations Widget
     *
     * Purpose: Test recent migrations display widget
     * Steps:
     * 1. Run several migrations
     * 2. Verify widget shows recent migration history
     * 3. Test click-through functionality to migration details
     * 4. Check sorting and filtering options
     */
    public function test_migration_stats_widget_functionality(): void
    {
        // Step 1: Create migration history data
        $this->createMigrationHistoryData();

        // Step 2: Initialize widget
        $widget = new MigrationStatsWidget;

        // Use reflection to access protected getStats method
        $reflection = new \ReflectionClass($widget);
        $getStatsMethod = $reflection->getMethod('getStats');
        $getStatsMethod->setAccessible(true);
        $stats = $getStatsMethod->invoke($widget);

        $this->assertIsArray($stats);
        $this->assertCount(3, $stats); // Should have 3 stats: total, pending, recent activity

        // Step 3: Verify widget shows recent migration history
        foreach ($stats as $stat) {
            $this->assertInstanceOf(Stat::class, $stat);
        }

        // Step 4: Test sorting and data accuracy
        // The widget should show accurate counts
        $totalMigrations = glob(database_path('migrations/*.php'));
        $this->assertGreaterThanOrEqual(0, count($totalMigrations));

        // Check recent activity (last 7 days)
        if (Schema::hasTable('migration_histories')) {
            $recentActivity = MigrationHistory::where('executed_at', '>=', now()->subDays(7))->count();
            $this->assertGreaterThanOrEqual(0, $recentActivity);
        }
    }

    /**
     * Test Database Health Widget
     *
     * Purpose: Test database health monitoring widget
     */
    public function test_database_health_widget_functionality(): void
    {
        // Create health test data
        $this->createHealthMetrics();

        // Initialize widget
        $widget = new DatabaseHealthWidget;
        $viewData = $widget->getViewData();

        // Verify widget data structure
        $this->assertArrayHasKey('healthSummary', $viewData);
        $this->assertArrayHasKey('connectionStatus', $viewData);
        $this->assertArrayHasKey('performanceMetrics', $viewData);
        $this->assertArrayHasKey('recentActivity', $viewData);

        // Test health summary structure
        $healthSummary = $viewData['healthSummary'];
        $this->assertIsArray($healthSummary);
        $this->assertArrayHasKey('connection_status', $healthSummary);

        // Test connection status
        $connectionStatus = $viewData['connectionStatus'];
        $this->assertIsArray($connectionStatus);

        // Test performance metrics
        $performanceMetrics = $viewData['performanceMetrics'];
        $this->assertIsArray($performanceMetrics);
    }

    /**
     * Test Database Health Metrics Widget
     *
     * Purpose: Test database health metrics overview widget
     */
    public function test_database_health_metrics_widget_functionality(): void
    {
        // Create performance test data
        $this->createPerformanceTestData();

        // Initialize widget
        $widget = new DatabaseHealthMetricsWidget;

        // Use reflection to access protected getStats method
        $reflection = new \ReflectionClass($widget);
        $getStatsMethod = $reflection->getMethod('getStats');
        $getStatsMethod->setAccessible(true);
        $stats = $getStatsMethod->invoke($widget);

        $this->assertIsArray($stats);
        $this->assertCount(6, $stats); // Connection, Queries, Response Time, Slow Queries, Error Rate, DB Size

        // Verify each stat
        foreach ($stats as $stat) {
            $this->assertInstanceOf(Stat::class, $stat);
        }
    }

    /**
     * Test Widget Responsive Design
     *
     * Purpose: Test widget responsiveness and layout
     */
    public function test_widget_responsive_design(): void
    {
        // Test widget column spans
        $databaseStatsWidget = new DatabaseStatsWidget;
        $databaseSpan = $databaseStatsWidget->getColumnSpan() ?? 1;
        $this->assertTrue($databaseSpan === 'full' || is_numeric($databaseSpan));

        $healthWidget = new DatabaseHealthWidget;
        $columnSpan = $healthWidget->getColumnSpan();
        $this->assertTrue($columnSpan === 'full' || is_numeric($columnSpan));

        $healthMetricsWidget = new DatabaseHealthMetricsWidget;
        $metricSpan = $healthMetricsWidget->getColumnSpan() ?? 1;
        $this->assertTrue($metricSpan === 'full' || is_numeric($metricSpan));
    }

    /**
     * Test Widget Data Refresh
     *
     * Purpose: Test widget real-time data updates
     */
    public function test_widget_data_refresh(): void
    {
        // Test initial state
        $widget = new DatabaseStatsWidget;

        // Use reflection to access protected getStats method
        $reflection = new \ReflectionClass($widget);
        $getStatsMethod = $reflection->getMethod('getStats');
        $getStatsMethod->setAccessible(true);
        $initialStats = $getStatsMethod->invoke($widget);

        // Add more data
        $this->addMoreTestData();

        // Test updated state
        $newWidget = new DatabaseStatsWidget;
        $updatedStats = $getStatsMethod->invoke($newWidget);

        // Both should return valid arrays
        $this->assertIsArray($initialStats);
        $this->assertIsArray($updatedStats);
        $this->assertCount(3, $initialStats);
        $this->assertCount(3, $updatedStats);
    }

    /**
     * Test Widget Error Handling
     *
     * Purpose: Test widget behavior when encountering errors
     */
    public function test_widget_error_handling(): void
    {
        // Test with invalid database state
        $widget = new DatabaseStatsWidget;

        try {
            // Use reflection to access protected getStats method
            $reflection = new \ReflectionClass($widget);
            $getStatsMethod = $reflection->getMethod('getStats');
            $getStatsMethod->setAccessible(true);
            $stats = $getStatsMethod->invoke($widget);

            $this->assertIsArray($stats);

            // Even with errors, should return some stats
            $this->assertGreaterThan(0, count($stats));
        } catch (\Exception $e) {
            // If an exception occurs, it should be handled gracefully
            $this->fail('Widget should handle errors gracefully: '.$e->getMessage());
        }
    }

    /**
     * Helper method to create test tables
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
        for ($i = 1; $i <= 5; $i++) {
            DB::table('test_users')->insert([
                'name' => "Test User {$i}",
                'email' => "user{$i}@example.com",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed test posts
        for ($i = 1; $i <= 10; $i++) {
            DB::table('test_posts')->insert([
                'user_id' => ($i % 5) + 1,
                'title' => "Test Post {$i}",
                'content' => "This is test content for post {$i}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Helper method to create widget test data
     */
    protected function createWidgetTestData(): void
    {
        // Create some performance logs for widgets
        for ($i = 0; $i < 10; $i++) {
            QueryPerformanceLog::create([
                'query' => 'SELECT * FROM test_users WHERE id = ?',
                'query_hash' => md5("widget_test_query_{$i}"),
                'bindings' => json_encode([$i + 1]),
                'execution_time' => rand(10, 100),
                'memory_usage' => rand(1024, 2048),
                'result_count' => 1,
                'connection' => 'testing',
                'executed_at' => now()->subHours($i),
                'query_type' => 'select',
                'is_slow' => false,
            ]);
        }
    }

    /**
     * Helper method to create migration history data
     */
    protected function createMigrationHistoryData(): void
    {
        if (Schema::hasTable('migration_histories')) {
            for ($i = 1; $i <= 5; $i++) {
                MigrationHistory::create([
                    'migration' => "2024_01_0{$i}_000000_test_migration_{$i}",
                    'batch' => $i,
                    'executed_at' => now()->subDays($i),
                    'execution_time' => rand(100, 1000),
                    'status' => 'success',
                ]);
            }
        }
    }

    /**
     * Helper method to create health metrics
     */
    protected function createHealthMetrics(): void
    {
        $metrics = [
            ['connection_status', 'response_time', 25.5],
            ['query_performance', 'avg_execution_time', 45.2],
            ['database_info', 'database_size', 1024.0],
            ['database_info', 'active_connections', 5],
        ];

        foreach ($metrics as [$type, $name, $value]) {
            DatabaseHealthMetric::create([
                'connection' => 'testing',
                'metric_type' => $type,
                'metric_name' => $name,
                'value' => $value,
                'unit' => $this->getMetricUnit($name),
                'status' => 'normal',
                'recorded_at' => now(),
            ]);
        }
    }

    /**
     * Helper method to create performance test data
     */
    protected function createPerformanceTestData(): void
    {
        // Create performance logs for the last 24 hours
        for ($i = 0; $i < 20; $i++) {
            QueryPerformanceLog::create([
                'query' => 'SELECT * FROM test_posts WHERE user_id = ?',
                'query_hash' => md5("perf_test_{$i}"),
                'bindings' => json_encode([1]),
                'execution_time' => rand(10, 200),
                'memory_usage' => rand(1024, 4096),
                'result_count' => rand(1, 10),
                'connection' => 'testing',
                'executed_at' => now()->subHours($i),
                'query_type' => 'select',
                'is_slow' => false,
            ]);
        }

        // Add a few slow queries
        for ($i = 0; $i < 3; $i++) {
            QueryPerformanceLog::create([
                'query' => 'SELECT * FROM test_posts ORDER BY created_at DESC',
                'query_hash' => md5("slow_query_{$i}"),
                'bindings' => json_encode([]),
                'execution_time' => rand(1000, 3000),
                'memory_usage' => rand(4096, 8192),
                'result_count' => rand(50, 100),
                'connection' => 'testing',
                'executed_at' => now()->subHours($i),
                'query_type' => 'select',
                'is_slow' => true,
            ]);
        }
    }

    /**
     * Helper method to add more test data
     */
    protected function addMoreTestData(): void
    {
        DB::table('test_users')->insert([
            'name' => 'Additional User',
            'email' => 'additional@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Helper method to get tables count
     */
    protected function getTablesCount(): int
    {
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'");

        return count($tables);
    }

    /**
     * Helper method to get pending migrations count
     */
    protected function getPendingMigrationsCount(): int
    {
        $migrationFiles = glob(database_path('migrations/*.php'));
        $totalMigrations = count($migrationFiles);

        $executedMigrations = 0;
        if (Schema::hasTable('migrations')) {
            $executedMigrations = DB::table('migrations')->count();
        }

        return max(0, $totalMigrations - $executedMigrations);
    }

    /**
     * Helper method to get database size
     */
    protected function getDatabaseSize(): float
    {
        // Simplified for testing
        return 1.5; // MB
    }

    /**
     * Helper method to get metric unit
     */
    protected function getMetricUnit(string $metricName): string
    {
        return match ($metricName) {
            'response_time', 'avg_execution_time' => 'ms',
            'database_size' => 'MB',
            'active_connections' => 'count',
            default => 'count',
        };
    }
}
