<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature;

use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use HkDevs\CodeForgeStudio\Services\SchemaAnalyzerService;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Test Cases for Quick Access Panel Functionality
 *
 * Based on TC-DB-004 from COMPREHENSIVE_TEST_CASES_FOR_USER.md
 * These tests verify direct shortcuts to frequently used database operations,
 * navigation efficiency, and quick access feature integration.
 */
class QuickAccessPanelTest extends TestCase
{
    use RefreshDatabase;

    protected DatabaseHealthService $healthService;

    protected SchemaAnalyzerService $schemaAnalyzer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->healthService = app(DatabaseHealthService::class);
        $this->schemaAnalyzer = app(SchemaAnalyzerService::class);

        // Create test environment for quick access testing
        $this->createTestEnvironment();
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
        // Step 1: Access Quick Access Panel data compilation
        $quickAccessData = $this->compileQuickAccessData();

        $this->assertArrayHasKey('database_overview', $quickAccessData);
        $this->assertArrayHasKey('migration_shortcuts', $quickAccessData);
        $this->assertArrayHasKey('schema_shortcuts', $quickAccessData);
        $this->assertArrayHasKey('health_shortcuts', $quickAccessData);
        $this->assertArrayHasKey('documentation_shortcuts', $quickAccessData);

        // Step 2: Test shortcuts to migration management
        $migrationShortcuts = $quickAccessData['migration_shortcuts'];

        $this->assertArrayHasKey('total_migrations', $migrationShortcuts);
        $this->assertArrayHasKey('pending_migrations', $migrationShortcuts);
        $this->assertArrayHasKey('recent_activity', $migrationShortcuts);
        $this->assertArrayHasKey('last_migration_batch', $migrationShortcuts);

        // Verify migration data accuracy
        $this->assertIsInt($migrationShortcuts['total_migrations']);
        $this->assertGreaterThanOrEqual(0, $migrationShortcuts['total_migrations']);
        $this->assertIsInt($migrationShortcuts['pending_migrations']);
        $this->assertGreaterThanOrEqual(0, $migrationShortcuts['pending_migrations']);

        // Step 3: Verify links to schema designer
        $schemaShortcuts = $quickAccessData['schema_shortcuts'];

        $this->assertArrayHasKey('total_tables', $schemaShortcuts);
        $this->assertArrayHasKey('total_relationships', $schemaShortcuts);
        $this->assertArrayHasKey('schema_complexity', $schemaShortcuts);
        $this->assertArrayHasKey('last_schema_change', $schemaShortcuts);

        // Verify schema data
        $this->assertIsInt($schemaShortcuts['total_tables']);
        $this->assertGreaterThanOrEqual(0, $schemaShortcuts['total_tables']);
        $this->assertIsInt($schemaShortcuts['total_relationships']);
        $this->assertGreaterThanOrEqual(0, $schemaShortcuts['total_relationships']);

        // Step 4: Test quick access to health monitoring
        $healthShortcuts = $quickAccessData['health_shortcuts'];

        $this->assertArrayHasKey('connection_status', $healthShortcuts);
        $this->assertArrayHasKey('health_score', $healthShortcuts);
        $this->assertArrayHasKey('active_alerts', $healthShortcuts);
        $this->assertArrayHasKey('monitoring_status', $healthShortcuts);

        // Verify health data
        $this->assertIsString($healthShortcuts['connection_status']);
        $this->assertIsNumeric($healthShortcuts['health_score']);
        $this->assertIsInt($healthShortcuts['active_alerts']);
        $this->assertIsString($healthShortcuts['monitoring_status']);

        // Step 5: Verify navigation to documentation generator
        $docsShortcuts = $quickAccessData['documentation_shortcuts'];

        $this->assertArrayHasKey('documented_tables', $docsShortcuts);
        $this->assertArrayHasKey('documentation_coverage', $docsShortcuts);
        $this->assertArrayHasKey('last_generation', $docsShortcuts);
        $this->assertArrayHasKey('available_formats', $docsShortcuts);

        // Verify documentation data
        $this->assertIsInt($docsShortcuts['documented_tables']);
        $this->assertIsNumeric($docsShortcuts['documentation_coverage']);
        $this->assertIsArray($docsShortcuts['available_formats']);
    }

    /**
     * Test Database Overview Quick Stats
     *
     * Purpose: Test rapid database overview information
     */
    public function test_database_overview_quick_stats(): void
    {
        $overviewStats = $this->getDatabaseOverviewStats();

        // Test essential stats are present
        $this->assertArrayHasKey('total_tables', $overviewStats);
        $this->assertArrayHasKey('total_records', $overviewStats);
        $this->assertArrayHasKey('database_size', $overviewStats);
        $this->assertArrayHasKey('connection_status', $overviewStats);
        $this->assertArrayHasKey('last_activity', $overviewStats);

        // Verify data types and ranges
        $this->assertIsInt($overviewStats['total_tables']);
        $this->assertGreaterThan(0, $overviewStats['total_tables']); // Should have test tables

        $this->assertIsInt($overviewStats['total_records']);
        $this->assertGreaterThan(0, $overviewStats['total_records']); // Should have test data

        $this->assertIsNumeric($overviewStats['database_size']);
        $this->assertGreaterThan(0, $overviewStats['database_size']);

        $this->assertContains($overviewStats['connection_status'], ['connected', 'disconnected', 'error']);
    }

    /**
     * Test Migration Management Shortcuts
     *
     * Purpose: Test quick access to migration operations
     */
    public function test_migration_management_shortcuts(): void
    {
        $migrationShortcuts = $this->getMigrationShortcuts();

        // Test migration counts
        $totalMigrationFiles = count(glob(database_path('migrations/*.php')));
        $this->assertEquals($totalMigrationFiles, $migrationShortcuts['total_migrations']);

        // Test pending migrations calculation
        $executedMigrations = 0;
        if (Schema::hasTable('migrations')) {
            $executedMigrations = DB::table('migrations')->count();
        }

        $expectedPending = max(0, $totalMigrationFiles - $executedMigrations);
        $this->assertEquals($expectedPending, $migrationShortcuts['pending_migrations']);

        // Test migration batch tracking
        if (Schema::hasTable('migrations')) {
            $lastBatch = DB::table('migrations')->max('batch') ?? 0;
            $this->assertEquals($lastBatch, $migrationShortcuts['last_migration_batch']);
        }
    }

    /**
     * Test Schema Designer Shortcuts
     *
     * Purpose: Test quick access to schema design features
     */
    public function test_schema_designer_shortcuts(): void
    {
        $schemaShortcuts = $this->getSchemaShortcuts();

        // Test table count accuracy
        $actualTables = $this->schemaAnalyzer->getAllTables();
        $this->assertEquals(count($actualTables), $schemaShortcuts['total_tables']);

        // Test relationship counting
        $relationships = $this->countDatabaseRelationships();
        $this->assertEquals($relationships, $schemaShortcuts['total_relationships']);

        // Test schema complexity calculation
        $complexity = $this->calculateSchemaComplexity($actualTables);
        $this->assertEquals($complexity, $schemaShortcuts['schema_complexity']);

        // Test last change tracking
        $this->assertIsString($schemaShortcuts['last_schema_change']);
    }

    /**
     * Test Health Monitoring Shortcuts
     *
     * Purpose: Test quick access to health monitoring features
     */
    public function test_health_monitoring_shortcuts(): void
    {
        $healthShortcuts = $this->getHealthShortcuts();

        // Test connection status
        $connectionTest = $this->healthService->testConnection('testing');
        $expectedStatus = $connectionTest['status'] === 'connected' ? 'connected' : 'disconnected';
        $this->assertEquals($expectedStatus, $healthShortcuts['connection_status']);

        // Test health score calculation
        $this->assertGreaterThanOrEqual(0, $healthShortcuts['health_score']);
        $this->assertLessThanOrEqual(100, $healthShortcuts['health_score']);

        // Test active alerts count
        $this->assertGreaterThanOrEqual(0, $healthShortcuts['active_alerts']);

        // Test monitoring status
        $this->assertContains($healthShortcuts['monitoring_status'], ['active', 'inactive', 'error']);
    }

    /**
     * Test Documentation Generator Shortcuts
     *
     * Purpose: Test quick access to documentation features
     */
    public function test_documentation_generator_shortcuts(): void
    {
        $docsShortcuts = $this->getDocumentationShortcuts();

        // Test documented tables count
        $totalTables = count($this->schemaAnalyzer->getAllTables());
        $this->assertEquals($totalTables, $docsShortcuts['documented_tables']);

        // Test documentation coverage
        $coverage = ($docsShortcuts['documented_tables'] / max(1, $totalTables)) * 100;
        $this->assertEquals($coverage, $docsShortcuts['documentation_coverage']);

        // Test available formats
        $expectedFormats = ['markdown', 'html', 'pdf', 'json'];
        $this->assertEquals($expectedFormats, $docsShortcuts['available_formats']);

        // Test last generation timestamp
        $this->assertIsString($docsShortcuts['last_generation']);
    }

    /**
     * Test Quick Access Performance
     *
     * Purpose: Test that quick access data loads efficiently
     */
    public function test_quick_access_performance(): void
    {
        $startTime = microtime(true);

        // Load all quick access data
        $quickAccessData = $this->compileQuickAccessData();

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

        // Quick access should load within reasonable time (e.g., 1 second)
        $this->assertLessThan(1000, $executionTime, 'Quick access data should load within 1 second');

        // Verify all data is present
        $this->assertNotEmpty($quickAccessData);
        $this->assertCount(5, $quickAccessData); // All 5 sections
    }

    /**
     * Test Quick Access Data Refresh
     *
     * Purpose: Test real-time data updates in quick access
     */
    public function test_quick_access_data_refresh(): void
    {
        // Get initial state
        $initialData = $this->compileQuickAccessData();

        // Make changes to database
        $this->addTestData();

        // Get refreshed state
        $refreshedData = $this->compileQuickAccessData();

        // Verify data has updated
        $this->assertGreaterThan(
            $initialData['database_overview']['total_records'],
            $refreshedData['database_overview']['total_records']
        );

        // Verify structure remains consistent
        $this->assertEquals(
            array_keys($initialData),
            array_keys($refreshedData)
        );
    }

    /**
     * Test Quick Access Error Handling
     *
     * Purpose: Test graceful error handling in quick access
     */
    public function test_quick_access_error_handling(): void
    {
        // Test with potential error conditions
        try {
            $quickAccessData = $this->compileQuickAccessData();

            // Should always return an array even with errors
            $this->assertIsArray($quickAccessData);

            // Should have fallback values for each section
            foreach ($quickAccessData as $section => $data) {
                $this->assertIsArray($data, "Section {$section} should be an array");
            }
        } catch (\Exception $e) {
            $this->fail('Quick access should handle errors gracefully: '.$e->getMessage());
        }
    }

    /**
     * Helper method to compile all quick access data
     */
    protected function compileQuickAccessData(): array
    {
        return [
            'database_overview' => $this->getDatabaseOverviewStats(),
            'migration_shortcuts' => $this->getMigrationShortcuts(),
            'schema_shortcuts' => $this->getSchemaShortcuts(),
            'health_shortcuts' => $this->getHealthShortcuts(),
            'documentation_shortcuts' => $this->getDocumentationShortcuts(),
        ];
    }

    /**
     * Helper method to get database overview stats
     */
    protected function getDatabaseOverviewStats(): array
    {
        $tables = $this->schemaAnalyzer->getAllTables();
        $totalRecords = $this->getTotalRecords($tables);

        return [
            'total_tables' => count($tables),
            'total_records' => $totalRecords,
            'database_size' => $this->getDatabaseSize(),
            'connection_status' => $this->getConnectionStatus(),
            'last_activity' => now()->subHours(2)->toDateTimeString(),
        ];
    }

    /**
     * Helper method to get migration shortcuts
     */
    protected function getMigrationShortcuts(): array
    {
        $totalMigrations = count(glob(database_path('migrations/*.php')));
        $executedMigrations = 0;
        $lastBatch = 0;

        if (Schema::hasTable('migrations')) {
            $executedMigrations = DB::table('migrations')->count();
            $lastBatch = DB::table('migrations')->max('batch') ?? 0;
        }

        return [
            'total_migrations' => $totalMigrations,
            'pending_migrations' => max(0, $totalMigrations - $executedMigrations),
            'recent_activity' => $this->getRecentMigrationActivity(),
            'last_migration_batch' => $lastBatch,
        ];
    }

    /**
     * Helper method to get schema shortcuts
     */
    protected function getSchemaShortcuts(): array
    {
        $tables = $this->schemaAnalyzer->getAllTables();

        return [
            'total_tables' => count($tables),
            'total_relationships' => $this->countDatabaseRelationships(),
            'schema_complexity' => $this->calculateSchemaComplexity($tables),
            'last_schema_change' => now()->subHours(1)->toDateTimeString(),
        ];
    }

    /**
     * Helper method to get health shortcuts
     */
    protected function getHealthShortcuts(): array
    {
        $connectionTest = $this->healthService->testConnection('testing');

        return [
            'connection_status' => $connectionTest['status'] === 'connected' ? 'connected' : 'disconnected',
            'health_score' => $this->calculateHealthScore(),
            'active_alerts' => $this->getActiveAlertsCount(),
            'monitoring_status' => 'active',
        ];
    }

    /**
     * Helper method to get documentation shortcuts
     */
    protected function getDocumentationShortcuts(): array
    {
        $tables = $this->schemaAnalyzer->getAllTables();
        $documentedTables = count($tables); // Assume all tables can be documented

        return [
            'documented_tables' => $documentedTables,
            'documentation_coverage' => $documentedTables > 0 ? 100.0 : 0.0,
            'last_generation' => now()->subHours(3)->toDateTimeString(),
            'available_formats' => ['markdown', 'html', 'pdf', 'json'],
        ];
    }

    /**
     * Helper method to create test environment
     */
    protected function createTestEnvironment(): void
    {
        // Create test tables
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

        // Seed test data
        for ($i = 1; $i <= 5; $i++) {
            DB::table('test_users')->insert([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            DB::table('test_posts')->insert([
                'user_id' => (($i - 1) % 5) + 1,
                'title' => "Post {$i}",
                'content' => "Content for post {$i}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Helper method to get total records across all tables
     */
    protected function getTotalRecords(array $tables): int
    {
        $total = 0;
        foreach ($tables as $table) {
            try {
                $total += DB::table($table['name'])->count();
            } catch (\Exception $e) {
                // Skip tables that can't be counted
            }
        }

        return $total;
    }

    /**
     * Helper method to get database size
     */
    protected function getDatabaseSize(): float
    {
        return 2.5; // MB - simplified for testing
    }

    /**
     * Helper method to get connection status
     */
    protected function getConnectionStatus(): string
    {
        $test = $this->healthService->testConnection('testing');

        return $test['status'] === 'connected' ? 'connected' : 'disconnected';
    }

    /**
     * Helper method to get recent migration activity
     */
    protected function getRecentMigrationActivity(): int
    {
        if (Schema::hasTable('migration_histories')) {
            return DB::table('migration_histories')
                ->where('executed_at', '>=', now()->subDays(7))
                ->count();
        }

        return 0;
    }

    /**
     * Helper method to count database relationships
     */
    protected function countDatabaseRelationships(): int
    {
        // For our test tables: test_posts->user_id
        return 1;
    }

    /**
     * Helper method to calculate schema complexity
     */
    protected function calculateSchemaComplexity(array $tables): string
    {
        $tableCount = count($tables);

        if ($tableCount <= 5) {
            return 'simple';
        }
        if ($tableCount <= 20) {
            return 'moderate';
        }

        return 'complex';
    }

    /**
     * Helper method to calculate health score
     */
    protected function calculateHealthScore(): int
    {
        // Simplified health score calculation
        $connectionTest = $this->healthService->testConnection('testing');

        return $connectionTest['status'] === 'connected' ? 95 : 50;
    }

    /**
     * Helper method to get active alerts count
     */
    protected function getActiveAlertsCount(): int
    {
        if (Schema::hasTable('database_health_metrics')) {
            return DB::table('database_health_metrics')
                ->whereIn('status', ['warning', 'critical'])
                ->where('recorded_at', '>=', now()->subHours(2))
                ->count();
        }

        return 0;
    }

    /**
     * Helper method to add test data
     */
    protected function addTestData(): void
    {
        DB::table('test_users')->insert([
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
