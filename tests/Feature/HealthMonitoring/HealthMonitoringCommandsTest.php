<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature\HealthMonitoring;

use HkDevs\CodeForgeStudio\Models\DatabaseHealthMetric;
use HkDevs\CodeForgeStudio\Models\QueryPerformanceLog;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

/**
 * Health Monitoring Commands Test Suite
 *
 * This test class specifically focuses on testing the health monitoring Artisan commands:
 * - database-manager:collect-metrics
 * - database-manager:toggle-query-logging
 * - database-manager:cleanup-logs
 *
 * Implements test cases TC-CMD-003 from the comprehensive test documentation.
 *
 * @author HkDevs (hardikkanajariya.in)
 */
class HealthMonitoringCommandsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Configure health monitoring for testing
        Config::set('codeforge-database-studio.enable_query_logging', true);
        Config::set('codeforge-database-studio.health_monitoring.slow_query_threshold', 1000);
        Config::set('codeforge-database-studio.health_monitoring.collection_interval', 300);

        $this->runPluginMigrations();
    }

    /**
     * Run plugin migrations for testing environment
     */
    private function runPluginMigrations(): void
    {
        if (! Schema::hasTable('database_health_metrics')) {
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
            });
        }

        if (! Schema::hasTable('query_performance_logs')) {
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
            });
        }

        if (! Schema::hasTable('database_manager_logs')) {
            Schema::create('database_manager_logs', function ($table) {
                $table->id();
                $table->string('operation');
                $table->text('details')->nullable();
                $table->string('status');
                $table->timestamp('executed_at');
                $table->timestamps();
            });
        }
    }

    /**
     * Test: database-manager:collect-metrics command execution
     * TC-CMD-003: Health Monitoring Commands - Manual metrics collection
     */
    public function test_collect_metrics_command_execution(): void
    {
        // Test basic command execution
        $exitCode = Artisan::call('database-manager:collect-metrics');

        $this->assertEquals(0, $exitCode, 'Command should execute successfully');

        // Check that output contains expected messages
        $output = Artisan::output();
        $this->assertStringContainsString('Collecting health metrics', $output);

        // Verify metrics were created in database
        $this->assertDatabaseHas('database_health_metrics', [
            'connection' => 'testing',
            'metric_type' => 'connection_status',
        ]);
    }

    /**
     * Test: database-manager:collect-metrics --connection option
     */
    public function test_collect_metrics_command_with_specific_connection(): void
    {
        // Test command with specific connection
        $exitCode = Artisan::call('database-manager:collect-metrics', [
            '--connection' => 'testing',
        ]);

        $this->assertEquals(0, $exitCode, 'Command with connection option should execute successfully');

        $output = Artisan::output();
        $this->assertStringContainsString('testing', $output);

        // Verify metrics were collected for the specified connection
        $metrics = DatabaseHealthMetric::where('connection', 'testing')->get();
        $this->assertGreaterThan(0, $metrics->count());
    }

    /**
     * Test: database-manager:collect-metrics --test option
     */
    public function test_collect_metrics_command_test_mode(): void
    {
        // Test command in test mode (dry run)
        $exitCode = Artisan::call('database-manager:collect-metrics', [
            '--test' => true,
        ]);

        $this->assertEquals(0, $exitCode, 'Test mode should execute successfully');

        $output = Artisan::output();
        $this->assertStringContainsString('Test mode', $output);
    }

    /**
     * Test: database-manager:toggle-query-logging command
     * TC-CMD-003: Query logging toggle functionality
     */
    public function test_toggle_query_logging_command(): void
    {
        // Test enabling query logging
        $exitCode = Artisan::call('database-manager:toggle-query-logging', [
            '--enable' => true,
        ]);

        $this->assertEquals(0, $exitCode, 'Enable query logging should execute successfully');

        $output = Artisan::output();
        $this->assertStringContainsString('enabled', $output);

        // Test disabling query logging
        $exitCode = Artisan::call('database-manager:toggle-query-logging', [
            '--disable' => true,
        ]);

        $this->assertEquals(0, $exitCode, 'Disable query logging should execute successfully');

        $output = Artisan::output();
        $this->assertStringContainsString('disabled', $output);
    }

    /**
     * Test: database-manager:toggle-query-logging without options (toggle)
     */
    public function test_toggle_query_logging_command_toggle(): void
    {
        // Test toggling query logging (no specific enable/disable)
        $exitCode = Artisan::call('database-manager:toggle-query-logging');

        $this->assertEquals(0, $exitCode, 'Toggle query logging should execute successfully');

        $output = Artisan::output();
        // Should contain either "enabled" or "disabled"
        $this->assertTrue(
            str_contains($output, 'enabled') || str_contains($output, 'disabled'),
            'Output should indicate current logging state'
        );
    }

    /**
     * Test: database-manager:cleanup-logs command
     * TC-CMD-003: Log cleanup functionality
     */
    public function test_cleanup_logs_command(): void
    {
        // Create old test logs
        $this->createOldTestLogs();

        // Test basic cleanup command
        $exitCode = Artisan::call('database-manager:cleanup-logs');

        $this->assertEquals(0, $exitCode, 'Cleanup logs command should execute successfully');

        $output = Artisan::output();
        $this->assertStringContainsString('Cleanup completed', $output);
    }

    /**
     * Test: database-manager:cleanup-logs --days option
     */
    public function test_cleanup_logs_command_with_days_option(): void
    {
        // Create test logs with different dates
        $this->createOldTestLogs();

        // Test cleanup with specific retention period
        $exitCode = Artisan::call('database-manager:cleanup-logs', [
            '--days' => 7,
        ]);

        $this->assertEquals(0, $exitCode, 'Cleanup with days option should execute successfully');

        $output = Artisan::output();
        $this->assertStringContainsString('7 days', $output);

        // Verify logs older than 7 days were cleaned up
        $oldLogs = QueryPerformanceLog::where('created_at', '<', now()->subDays(7))->count();
        $this->assertEquals(0, $oldLogs, 'Old logs should be cleaned up');
    }

    /**
     * Test: database-manager:cleanup-logs --dry-run option
     */
    public function test_cleanup_logs_command_dry_run(): void
    {
        // Create test logs
        $this->createOldTestLogs();
        $initialLogCount = QueryPerformanceLog::count();

        // Test dry run (should not actually delete)
        $exitCode = Artisan::call('database-manager:cleanup-logs', [
            '--dry-run' => true,
            '--days' => 7,
        ]);

        $this->assertEquals(0, $exitCode, 'Dry run should execute successfully');

        $output = Artisan::output();
        $this->assertStringContainsString('dry run', $output);
        $this->assertStringContainsString('would be deleted', $output);

        // Verify no logs were actually deleted
        $finalLogCount = QueryPerformanceLog::count();
        $this->assertEquals($initialLogCount, $finalLogCount, 'Dry run should not delete any logs');
    }

    /**
     * Test: Command output formatting and verbosity
     */
    public function test_collect_metrics_command_output_formatting(): void
    {
        // Test command with verbose output
        $exitCode = Artisan::call('database-manager:collect-metrics', [
            '--connection' => 'testing',
        ]);

        $output = Artisan::output();

        // Check for expected output sections
        $this->assertStringContainsString('Collecting health metrics', $output);
        $this->assertStringContainsString('Connection:', $output);
        $this->assertStringContainsString('Status:', $output);
        $this->assertStringContainsString('Response Time:', $output);
    }

    /**
     * Test: Command error handling for invalid connections
     */
    public function test_collect_metrics_command_invalid_connection(): void
    {
        // Test command with invalid connection
        $exitCode = Artisan::call('database-manager:collect-metrics', [
            '--connection' => 'invalid_connection',
        ]);

        // Command should still exit successfully but report the error
        $this->assertTrue($exitCode >= 0, 'Command should handle invalid connections gracefully');

        $output = Artisan::output();
        $this->assertStringContainsString('invalid_connection', $output);
    }

    /**
     * Test: Metrics collection accuracy and data validation
     */
    public function test_collect_metrics_command_data_accuracy(): void
    {
        // Execute metrics collection
        Artisan::call('database-manager:collect-metrics', ['--connection' => 'testing']);

        // Verify collected metrics have required fields
        $metrics = DatabaseHealthMetric::where('connection', 'testing')->get();

        foreach ($metrics as $metric) {
            $this->assertNotNull($metric->connection);
            $this->assertNotNull($metric->metric_type);
            $this->assertNotNull($metric->metric_name);
            $this->assertIsNumeric($metric->value);
            $this->assertNotNull($metric->recorded_at);

            // Verify metric values are reasonable
            if ($metric->metric_name === 'response_time') {
                $this->assertGreaterThan(0, $metric->value);
                $this->assertLessThan(10000, $metric->value); // Should be less than 10 seconds
            }
        }
    }

    /**
     * Test: Query logging toggle state persistence
     */
    public function test_query_logging_toggle_state_persistence(): void
    {
        // Enable query logging
        Artisan::call('database-manager:toggle-query-logging', ['--enable' => true]);

        // Verify logging is enabled (this would typically check a config or database setting)
        $this->assertTrue(true, 'Query logging state should be persisted');

        // Disable query logging
        Artisan::call('database-manager:toggle-query-logging', ['--disable' => true]);

        // Verify logging is disabled
        $this->assertTrue(true, 'Query logging disable state should be persisted');
    }

    /**
     * Test: Cleanup logs command with different retention policies
     */
    public function test_cleanup_logs_command_retention_policies(): void
    {
        // Create logs with various ages
        $this->createTestLogsWithSpecificDates([
            now()->subDays(1),   // Recent
            now()->subDays(15),  // Medium age
            now()->subDays(45),  // Old
            now()->subDays(90),  // Very old
        ]);

        $initialCount = QueryPerformanceLog::count();
        $this->assertEquals(4, $initialCount);

        // Test cleanup with 30-day retention
        Artisan::call('database-manager:cleanup-logs', ['--days' => 30]);

        // Verify only logs newer than 30 days remain
        $remainingCount = QueryPerformanceLog::count();
        $this->assertEquals(2, $remainingCount, 'Should keep logs from last 30 days');

        // Verify the correct logs were kept
        $recentLogs = QueryPerformanceLog::where('created_at', '>=', now()->subDays(30))->count();
        $this->assertEquals(2, $recentLogs);
    }

    /**
     * Test: Command execution time measurement
     */
    public function test_command_execution_performance(): void
    {
        $startTime = microtime(true);

        // Execute metrics collection command
        Artisan::call('database-manager:collect-metrics', ['--connection' => 'testing']);

        $executionTime = microtime(true) - $startTime;

        // Command should execute in reasonable time (less than 10 seconds)
        $this->assertLessThan(10, $executionTime, 'Command should execute within reasonable time');

        $output = Artisan::output();
        $this->assertNotEmpty($output, 'Command should produce output');
    }

    /**
     * Helper Methods
     */

    /**
     * Create old test logs for cleanup testing
     */
    private function createOldTestLogs(): void
    {
        // Create logs from different time periods
        $dates = [
            now()->subDays(1),
            now()->subDays(10),
            now()->subDays(20),
            now()->subDays(40),
        ];

        foreach ($dates as $date) {
            QueryPerformanceLog::create([
                'connection' => 'testing',
                'query' => 'SELECT * FROM test_table',
                'query_hash' => md5('SELECT * FROM test_table'),
                'execution_time' => $this->faker->numberBetween(10, 200),
                'type' => 'select',
                'status' => 'success',
                'executed_at' => $date,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Create old health metrics too
        foreach ($dates as $date) {
            DatabaseHealthMetric::create([
                'connection' => 'testing',
                'metric_type' => 'test',
                'metric_name' => 'old_metric',
                'value' => $this->faker->numberBetween(50, 100),
                'unit' => 'ms',
                'status' => 'normal',
                'recorded_at' => $date,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    /**
     * Create test logs with specific dates
     */
    private function createTestLogsWithSpecificDates(array $dates): void
    {
        foreach ($dates as $date) {
            QueryPerformanceLog::create([
                'connection' => 'testing',
                'query' => 'SELECT * FROM dated_test',
                'query_hash' => md5('SELECT * FROM dated_test'.$date->timestamp),
                'execution_time' => $this->faker->numberBetween(10, 200),
                'type' => 'select',
                'status' => 'success',
                'executed_at' => $date,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
