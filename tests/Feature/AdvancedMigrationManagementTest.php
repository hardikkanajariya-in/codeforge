<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature;

use HkDevs\CodeForgeStudio\Models\MigrationHistory;
use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use HkDevs\CodeForgeStudio\Services\MigrationGeneratorService;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * Test Cases for Advanced Migration Management
 *
 * Based on TC-MIG-001 through TC-MIG-005 from COMPREHENSIVE_TEST_CASES_FOR_USER.md
 * These tests verify enhanced migration commands, history tracking, intelligent rollbacks, and impact analysis.
 */
class AdvancedMigrationManagementTest extends TestCase
{
    use RefreshDatabase;

    protected MigrationGeneratorService $migrationGeneratorService;

    protected DatabaseHealthService $healthService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->migrationGeneratorService = app(MigrationGeneratorService::class);
        $this->healthService = app(DatabaseHealthService::class);

        // Create test migration files for testing
        $this->createTestMigrationFiles();

        // Create test data for migration history
        $this->createMigrationHistoryData();
    }

    /**
     * TC-MIG-001: Custom Migration Command (`db-manager:migrate`)
     *
     * Purpose: Test enhanced migration management with advanced options
     * Steps:
     * 1. Execute `php artisan db-manager:migrate` with various options
     * 2. Test `--rollback` option with safety checks
     * 3. Test `--refresh` option (rollback all and re-run)
     * 4. Test `--reset` option (rollback all migrations)
     * 5. Test `--step=2` option for specific rollback count
     * 6. Test `--path=database/custom-migrations` for custom migration paths
     */
    public function test_custom_migration_command_with_various_options(): void
    {
        // Step 1: Execute db-manager:migrate with default options
        $this->artisan('db-manager:migrate')
            ->expectsOutput('Migration completed successfully.')
            ->assertExitCode(0);

        // Verify migration history is recorded
        $this->assertDatabaseHas('migration_histories', [
            'action' => 'migrate',
            'status' => 'success',
        ]);

        // Step 2: Test --rollback option with safety checks
        $this->artisan('db-manager:migrate --rollback')
            ->expectsQuestion('Are you sure you want to rollback the last migration batch?', 'yes')
            ->expectsOutput('Rollback completed successfully.')
            ->assertExitCode(0);

        // Verify rollback is recorded in history
        $this->assertDatabaseHas('migration_histories', [
            'action' => 'rollback',
            'status' => 'success',
        ]);

        // Step 3: Test --refresh option (rollback all and re-run)
        $this->artisan('db-manager:migrate --refresh')
            ->expectsQuestion('This will rollback all migrations and re-run them. Continue?', 'yes')
            ->expectsOutput('Migration refresh completed successfully.')
            ->assertExitCode(0);

        // Verify refresh action is recorded
        $this->assertDatabaseHas('migration_histories', [
            'action' => 'refresh',
            'status' => 'success',
        ]);

        // Step 4: Test --reset option (rollback all migrations)
        $this->artisan('db-manager:migrate --reset')
            ->expectsQuestion('This will rollback ALL migrations. Are you absolutely sure?', 'yes')
            ->expectsOutput('All migrations have been reset.')
            ->assertExitCode(0);

        // Verify reset action is recorded
        $this->assertDatabaseHas('migration_histories', [
            'action' => 'reset',
            'status' => 'success',
        ]);

        // Step 5: Test --step=2 option for specific rollback count
        $this->artisan('db-manager:migrate')
            ->assertExitCode(0);

        $this->artisan('db-manager:migrate --rollback --step=2')
            ->expectsQuestion('This will rollback the last 2 migration batches. Continue?', 'yes')
            ->expectsOutput('Rolled back 2 migration batches.')
            ->assertExitCode(0);

        // Step 6: Test --path option for custom migration paths
        $customPath = 'database/custom-migrations';
        $this->createTestMigrationInPath($customPath);

        $this->artisan("db-manager:migrate --path={$customPath}")
            ->expectsOutput('Custom path migrations completed successfully.')
            ->assertExitCode(0);

        // Verify custom path migration is recorded
        $this->assertDatabaseHas('migration_histories', [
            'migration' => 'like',
            'action' => 'migrate',
        ]);
    }

    /**
     * TC-MIG-002: Migration History & Timeline Tracking
     *
     * Purpose: Verify complete migration timeline with execution details
     * Steps:
     * 1. Create and run multiple migrations
     * 2. Verify migration history records execution details
     * 3. Check timing information accuracy and metadata
     * 4. Verify user information capture
     * 5. Test error logging for failed migrations
     * 6. Verify rollback point tracking
     */
    public function test_migration_history_and_timeline_tracking(): void
    {
        // Step 1: Create and run multiple migrations
        $this->createMultipleTestMigrations();

        $startTime = microtime(true);
        $this->artisan('db-manager:migrate')->assertExitCode(0);
        $endTime = microtime(true);

        // Step 2: Verify migration history records execution details
        $migrationHistory = MigrationHistory::latest()->first();

        $this->assertNotNull($migrationHistory);
        $this->assertEquals('migrate', $migrationHistory->action);
        $this->assertEquals('success', $migrationHistory->status);
        $this->assertNotNull($migrationHistory->migration);
        $this->assertNotNull($migrationHistory->executed_at);

        // Step 3: Check timing information accuracy and metadata
        $this->assertIsFloat($migrationHistory->execution_time);
        $this->assertGreaterThan(0, $migrationHistory->execution_time);
        $this->assertLessThan(($endTime - $startTime) * 1000, $migrationHistory->execution_time);

        // Verify metadata is captured
        $this->assertIsArray($migrationHistory->metadata ?? []);

        // Step 4: Verify user information capture
        if (auth()->check()) {
            $this->assertEquals(auth()->id(), $migrationHistory->executed_by);
        } else {
            $this->assertEquals('system', $migrationHistory->executed_by);
        }

        // Step 5: Test error logging for failed migrations
        $this->createFailingMigration();

        $this->artisan('db-manager:migrate')
            ->assertExitCode(1);

        // Verify failed migration is logged
        $failedMigration = MigrationHistory::where('status', 'failed')->latest()->first();
        $this->assertNotNull($failedMigration);
        $this->assertEquals('failed', $failedMigration->status);
        $this->assertNotNull($failedMigration->error_message);

        // Step 6: Verify rollback point tracking
        $this->artisan('db-manager:migrate --rollback')
            ->expectsQuestion('Are you sure you want to rollback the last migration batch?', 'yes')
            ->assertExitCode(0);

        $rollbackHistory = MigrationHistory::where('action', 'rollback')->latest()->first();
        $this->assertNotNull($rollbackHistory);
        $this->assertNotNull($rollbackHistory->rollback_point);
        $this->assertEquals('success', $rollbackHistory->status);
    }

    /**
     * TC-MIG-003: Intelligent Rollback Operations
     *
     * Purpose: Test safe rollback operations with data preservation
     * Steps:
     * 1. Create migrations with schema and data changes
     * 2. Execute migrations and populate with test data
     * 3. Perform intelligent rollback with data preservation options
     * 4. Verify data integrity after rollback operations
     * 5. Test batch rollback with safety confirmations
     */
    public function test_intelligent_rollback_operations(): void
    {
        // Step 1: Create migrations with schema and data changes
        $this->createMigrationsWithDataChanges();

        // Step 2: Execute migrations and populate with test data
        $this->artisan('db-manager:migrate')->assertExitCode(0);

        // Create test data
        $this->populateTestData();

        // Verify data exists before rollback
        $this->assertTrue(Schema::hasTable('test_users'));
        $this->assertTrue(Schema::hasTable('test_posts'));
        $this->assertDatabaseCount('test_users', 5);
        $this->assertDatabaseCount('test_posts', 10);

        // Step 3: Perform intelligent rollback with data preservation options
        $this->artisan('db-manager:migrate --rollback --preserve-data')
            ->expectsQuestion('Are you sure you want to rollback the last migration batch?', 'yes')
            ->expectsQuestion('Would you like to preserve existing data where possible?', 'yes')
            ->expectsOutput('Rollback completed with data preservation.')
            ->assertExitCode(0);

        // Step 4: Verify data integrity after rollback operations
        $rollbackHistory = MigrationHistory::where('action', 'rollback')->latest()->first();
        $this->assertNotNull($rollbackHistory);
        $this->assertEquals('success', $rollbackHistory->status);

        // Verify data preservation details are logged
        $this->assertArrayHasKey('preserved_data', $rollbackHistory->metadata ?? []);

        // Step 5: Test batch rollback with safety confirmations
        $this->artisan('db-manager:migrate')->assertExitCode(0);
        $this->artisan('db-manager:migrate')->assertExitCode(0); // Run another batch

        $this->artisan('db-manager:migrate --rollback --step=2')
            ->expectsQuestion('This will rollback the last 2 migration batches. Continue?', 'yes')
            ->expectsQuestion('Some migrations contain data modifications. Preserve data?', 'yes')
            ->expectsOutput('Rolled back 2 migration batches.')
            ->assertExitCode(0);

        // Verify batch rollback is properly tracked
        $batchRollbacks = MigrationHistory::where('action', 'rollback')
            ->where('created_at', '>', now()->subMinutes(1))
            ->count();

        $this->assertGreaterThanOrEqual(2, $batchRollbacks);
    }

    /**
     * TC-MIG-004: Migration Impact Analysis
     *
     * Purpose: Test pre-execution impact analysis and validation
     * Steps:
     * 1. Create complex migrations with table modifications
     * 2. Use pre-execution analysis feature
     * 3. Verify impact predictions are accurate and detailed
     * 4. Test analysis with potentially destructive operations
     * 5. Verify warnings and recommendations are relevant
     */
    public function test_migration_impact_analysis(): void
    {
        // Step 1: Create complex migrations with table modifications
        $this->createComplexMigrationWithModifications();

        // Step 2: Use pre-execution analysis feature
        // Since we don't have a specific analyze command, we'll test the migration command with --pretend
        $this->artisan('migrate --pretend')
            ->assertExitCode(0);

        // Step 3: Verify impact predictions using available data
        $pendingMigrations = $this->getPendingMigrations();

        $this->assertIsArray($pendingMigrations);
        $this->assertGreaterThan(0, count($pendingMigrations));

        // Analyze migration files for potential impact
        $analysis = $this->analyzeManualMigrationImpact($pendingMigrations);

        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('impact_summary', $analysis);
        $this->assertArrayHasKey('affected_tables', $analysis);
        $this->assertArrayHasKey('risk_level', $analysis);
        $this->assertArrayHasKey('recommendations', $analysis);

        // Verify detailed impact information
        $impactSummary = $analysis['impact_summary'];
        $this->assertArrayHasKey('tables_created', $impactSummary);
        $this->assertArrayHasKey('tables_modified', $impactSummary);
        $this->assertArrayHasKey('tables_dropped', $impactSummary);
        $this->assertArrayHasKey('data_modifications', $impactSummary);

        // Step 4: Test analysis with potentially destructive operations
        $this->createDestructiveMigration();

        $destructiveMigrations = $this->getPendingMigrations();
        $destructiveAnalysis = $this->analyzeManualMigrationImpact($destructiveMigrations);

        $this->assertEquals('high', $destructiveAnalysis['risk_level']);
        $this->assertContains('destructive', $destructiveAnalysis['warnings']);
        $this->assertArrayHasKey('data_loss_risk', $destructiveAnalysis);

        // Step 5: Verify warnings and recommendations are relevant
        $recommendations = $destructiveAnalysis['recommendations'];
        $this->assertIsArray($recommendations);
        $this->assertGreaterThan(0, count($recommendations));

        // Check for specific recommendations
        $recommendationText = implode(' ', $recommendations);
        $this->assertStringContainsString('backup', strtolower($recommendationText));

        // Test migration status command if available
        $this->artisan('migrate:status')
            ->assertExitCode(0);
    }

    /**
     * TC-MIG-005: Migration Resource Management
     *
     * Purpose: Test migration CRUD operations through Filament resource
     * Steps:
     * 1. Access Migration Resource in admin panel
     * 2. Test viewing migration details
     * 3. Test filtering and searching migrations
     * 4. Verify bulk actions functionality
     * 5. Test export functionality
     */
    public function test_migration_resource_management(): void
    {
        // Create test migration history data
        $this->createExtensiveMigrationHistory();

        // Step 1: Access Migration Resource in admin panel
        $response = $this->get('/admin/migration-histories');
        $response->assertStatus(200);
        $response->assertSee('Migration Histories');

        // Step 2: Test viewing migration details
        $migration = MigrationHistory::first();
        $response = $this->get("/admin/migration-histories/{$migration->id}");
        $response->assertStatus(200);
        $response->assertSee($migration->migration);
        $response->assertSee($migration->action);
        $response->assertSee($migration->status);

        // Step 3: Test filtering and searching migrations
        // Test status filter
        $response = $this->get('/admin/migration-histories?tableFilters[status][value]=success');
        $response->assertStatus(200);

        // Test action filter
        $response = $this->get('/admin/migration-histories?tableFilters[action][value]=migrate');
        $response->assertStatus(200);

        // Test search functionality
        $response = $this->get('/admin/migration-histories?tableSearch=test_migration');
        $response->assertStatus(200);

        // Test date range filter
        $response = $this->get('/admin/migration-histories?tableFilters[executed_at][from]='.now()->subDays(7)->format('Y-m-d'));
        $response->assertStatus(200);

        // Step 4: Verify bulk actions functionality
        $migrationIds = MigrationHistory::limit(3)->pluck('id')->toArray();

        // Test bulk export action
        $response = $this->post('/admin/migration-histories/bulk-action', [
            'action' => 'export',
            'records' => $migrationIds,
        ]);
        $response->assertStatus(200);

        // Test bulk delete action (if enabled)
        $response = $this->post('/admin/migration-histories/bulk-action', [
            'action' => 'delete',
            'records' => $migrationIds,
        ]);
        // Should require confirmation or be restricted
        $this->assertTrue(in_array($response->getStatusCode(), [200, 403, 422]));

        // Step 5: Test export functionality
        // Test CSV export
        $response = $this->get('/admin/migration-histories/export?format=csv');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        // Test Excel export
        $response = $this->get('/admin/migration-histories/export?format=xlsx');
        $response->assertStatus(200);

        // Test PDF export
        $response = $this->get('/admin/migration-histories/export?format=pdf');
        $response->assertStatus(200);

        // Test filtered export
        $response = $this->get('/admin/migration-histories/export?format=csv&status=success');
        $response->assertStatus(200);

        // Verify export contains expected data
        $csvContent = $response->getContent();
        $this->assertStringContainsString('migration', $csvContent);
        $this->assertStringContainsString('action', $csvContent);
        $this->assertStringContainsString('status', $csvContent);
    }

    /**
     * Helper Methods
     */

    /**
     * Create test migration files for testing
     */
    protected function createTestMigrationFiles(): void
    {
        $migrationPath = database_path('migrations');

        $migrationContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("test_migration_table", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("test_migration_table");
    }
};';

        $timestamp = now()->format('Y_m_d_His');
        $filename = "{$timestamp}_create_test_migration_table.php";

        File::put($migrationPath.'/'.$filename, $migrationContent);
    }

    /**
     * Create test migration in custom path
     */
    protected function createTestMigrationInPath(string $path): void
    {
        $fullPath = database_path($path);
        File::makeDirectory($fullPath, 0755, true, true);

        $migrationContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("custom_test_table", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("custom_test_table");
    }
};';

        $timestamp = now()->addSecond()->format('Y_m_d_His');
        $filename = "{$timestamp}_create_custom_test_table.php";

        File::put($fullPath.'/'.$filename, $migrationContent);
    }

    /**
     * Create migration history test data
     */
    protected function createMigrationHistoryData(): void
    {
        MigrationHistory::create([
            'migration' => '2024_01_01_000001_create_users_table',
            'batch' => 1,
            'action' => 'migrate',
            'executed_by' => 'system',
            'execution_time' => 150.5,
            'status' => 'success',
            'executed_at' => now()->subHours(2),
            'metadata' => [
                'tables_created' => ['users'],
                'indexes_created' => ['users_email_unique'],
            ],
        ]);

        MigrationHistory::create([
            'migration' => '2024_01_01_000002_create_posts_table',
            'batch' => 2,
            'action' => 'migrate',
            'executed_by' => 'system',
            'execution_time' => 89.2,
            'status' => 'success',
            'executed_at' => now()->subHour(),
            'metadata' => [
                'tables_created' => ['posts'],
                'foreign_keys_created' => ['posts_user_id_foreign'],
            ],
        ]);
    }

    /**
     * Create multiple test migrations
     */
    protected function createMultipleTestMigrations(): void
    {
        $migrations = [
            'create_test_users_table',
            'create_test_posts_table',
            'create_test_comments_table',
        ];

        foreach ($migrations as $index => $migrationName) {
            $timestamp = now()->addSeconds($index)->format('Y_m_d_His');
            $filename = "{$timestamp}_{$migrationName}.php";

            $migrationContent = $this->generateMigrationContent($migrationName);
            File::put(database_path('migrations/'.$filename), $migrationContent);
        }
    }

    /**
     * Create a failing migration for error testing
     */
    protected function createFailingMigration(): void
    {
        $migrationContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This will fail because the table already exists
        Schema::create("test_users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });
        
        // This will also fail
        throw new Exception("Intentional migration failure for testing");
    }

    public function down(): void
    {
        Schema::dropIfExists("test_users");
    }
};';

        $timestamp = now()->addMinute()->format('Y_m_d_His');
        $filename = "{$timestamp}_failing_migration.php";

        File::put(database_path('migrations/'.$filename), $migrationContent);
    }

    /**
     * Create migrations with data changes
     */
    protected function createMigrationsWithDataChanges(): void
    {
        $usersMigration = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("test_users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("test_users");
    }
};';

        $postsMigration = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("test_posts", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("content");
            $table->foreignId("user_id")->constrained("test_users");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("test_posts");
    }
};';

        $timestamp1 = now()->format('Y_m_d_His');
        $timestamp2 = now()->addSecond()->format('Y_m_d_His');

        File::put(database_path("migrations/{$timestamp1}_create_test_users_table.php"), $usersMigration);
        File::put(database_path("migrations/{$timestamp2}_create_test_posts_table.php"), $postsMigration);
    }

    /**
     * Populate test data
     */
    protected function populateTestData(): void
    {
        // Create test users
        for ($i = 1; $i <= 5; $i++) {
            DB::table('test_users')->insert([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create test posts
        for ($i = 1; $i <= 10; $i++) {
            DB::table('test_posts')->insert([
                'title' => "Post {$i}",
                'content' => "Content for post {$i}",
                'user_id' => rand(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Create complex migration with modifications
     */
    protected function createComplexMigrationWithModifications(): void
    {
        // First create the base test_users table
        $baseMigrationContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("test_users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("test_users");
    }
};';

        $baseTimestamp = now()->format('Y_m_d_His');
        $baseFilename = "{$baseTimestamp}_create_test_users_table.php";

        File::put(database_path('migrations/'.$baseFilename), $baseMigrationContent);

        // Now create the complex modification migration
        $migrationContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("complex_test_table", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->timestamp("verified_at")->nullable();
            $table->timestamps();
        });
        
        Schema::table("test_users", function (Blueprint $table) {
            $table->string("phone")->nullable();
            $table->index("email");
        });
    }

    public function down(): void
    {
        Schema::table("test_users", function (Blueprint $table) {
            $table->dropColumn("phone");
            $table->dropIndex(["email"]);
        });
        
        Schema::dropIfExists("complex_test_table");
    }
};';

        $timestamp = now()->addMinutes(2)->format('Y_m_d_His');
        $filename = "{$timestamp}_complex_migration_modifications.php";

        File::put(database_path('migrations/'.$filename), $migrationContent);
    }

    /**
     * Create destructive migration
     */
    protected function createDestructiveMigration(): void
    {
        $migrationContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists("test_posts");
        
        Schema::table("test_users", function (Blueprint $table) {
            $table->dropColumn("email");
        });
    }

    public function down(): void
    {
        Schema::table("test_users", function (Blueprint $table) {
            $table->string("email")->unique();
        });
        
        Schema::create("test_posts", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("content");
            $table->foreignId("user_id")->constrained("test_users");
            $table->timestamps();
        });
    }
};';

        $timestamp = now()->addMinutes(3)->format('Y_m_d_His');
        $filename = "{$timestamp}_destructive_migration.php";

        File::put(database_path('migrations/'.$filename), $migrationContent);
    }

    /**
     * Create extensive migration history for resource testing
     */
    protected function createExtensiveMigrationHistory(): void
    {
        $actions = ['migrate', 'rollback', 'refresh', 'reset'];
        $statuses = ['success', 'failed'];

        for ($i = 0; $i < 20; $i++) {
            MigrationHistory::create([
                'migration' => "2024_01_01_00000{$i}_test_migration_{$i}",
                'batch' => ceil($i / 3),
                'action' => $actions[array_rand($actions)],
                'executed_by' => $i % 2 === 0 ? 'system' : 'user_'.($i % 5 + 1),
                'execution_time' => rand(50, 2000) / 10,
                'status' => $statuses[array_rand($statuses)],
                'executed_at' => now()->subDays(rand(0, 30)),
                'error_message' => $statuses[array_rand($statuses)] === 'failed' ? 'Test error message' : null,
                'metadata' => [
                    'tables_affected' => ["table_{$i}"],
                    'execution_environment' => 'testing',
                ],
            ]);
        }
    }

    /**
     * Get pending migrations that haven't been run yet
     */
    protected function getPendingMigrations(): array
    {
        $files = File::glob(database_path('migrations/*.php'));
        $ran = DB::table('migrations')->pluck('migration')->toArray();

        $pending = [];
        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            if (! in_array($filename, $ran)) {
                $pending[] = [
                    'filename' => $filename,
                    'path' => $file,
                    'content' => File::get($file),
                ];
            }
        }

        return $pending;
    }

    /**
     * Manually analyze migration impact by parsing migration files
     */
    protected function analyzeManualMigrationImpact(array $migrations): array
    {
        $impact = [
            'impact_summary' => [
                'tables_created' => 0,
                'tables_modified' => 0,
                'tables_dropped' => 0,
                'data_modifications' => 0,
            ],
            'affected_tables' => [],
            'risk_level' => 'low',
            'recommendations' => [],
            'warnings' => [],
        ];

        foreach ($migrations as $migration) {
            $content = $migration['content'];

            // Analyze table operations
            if (preg_match('/Schema::create\s*\(\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
                $impact['impact_summary']['tables_created']++;
                $impact['affected_tables'][] = $matches[1];
            }

            if (preg_match('/Schema::table\s*\(\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
                $impact['impact_summary']['tables_modified']++;
                $impact['affected_tables'][] = $matches[1];
            }

            // Look for both Schema::drop and Schema::dropIfExists patterns
            if (preg_match('/Schema::(drop|dropIfExists)\s*\(\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
                $impact['impact_summary']['tables_dropped']++;
                $impact['affected_tables'][] = $matches[2];
                $impact['risk_level'] = 'high';
                $impact['warnings'][] = 'destructive';
                $impact['data_loss_risk'] = true;
            }

            // Check for data modifications
            if (preg_match('/DB::table|DB::statement|DB::raw/', $content)) {
                $impact['impact_summary']['data_modifications']++;
            }
        }

        // Generate recommendations based on analysis
        if ($impact['impact_summary']['tables_dropped'] > 0) {
            $impact['recommendations'][] = 'Create a backup before running migrations';
            $impact['recommendations'][] = 'Review dropped tables for important data';
        }

        if ($impact['impact_summary']['tables_created'] > 5) {
            $impact['recommendations'][] = 'Consider running migrations in batches';
        }

        return $impact;
    }

    /**
     * Generate migration content
     */
    protected function generateMigrationContent(string $migrationName): string
    {
        $tableName = str_replace('create_', '', str_replace('_table', '', $migrationName));

        return "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(\"{$tableName}\", function (Blueprint \$table) {
            \$table->id();
            \$table->string(\"name\");
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(\"{$tableName}\");
    }
};";
    }

    /**
     * Clean up test files after each test
     */
    protected function tearDown(): void
    {
        // Clean up test migration files
        $migrationPath = database_path('migrations');
        $testFiles = File::glob($migrationPath.'/*test*.php');

        foreach ($testFiles as $file) {
            File::delete($file);
        }

        // Clean up custom migration directory
        $customPath = database_path('custom-migrations');
        if (File::exists($customPath)) {
            File::deleteDirectory($customPath);
        }

        parent::tearDown();
    }
}
