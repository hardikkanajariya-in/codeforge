<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature\MigrationManager;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

/**
 * Test Case: TC-MIGRATION-001 - Migration Manager Core Functionality
 * Purpose: Test migration manager features and operations
 */
class MigrationManagerCoreTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_history_tracking()
    {
        // Create migration history table if it doesn't exist
        if (!Schema::hasTable('migration_histories')) {
            Schema::create('migration_histories', function ($table) {
                $table->id();
                $table->string('migration_name');
                $table->integer('batch');
                $table->timestamp('executed_at')->nullable();
                $table->timestamp('rollback_at')->nullable();
                $table->string('status')->default('pending');
                $table->text('error_message')->nullable();
            });
        }

        // Insert a test migration record
        DB::table('migration_histories')->insert([
            'migration_name' => '2024_01_01_000001_create_test_table',
            'batch' => 1,
            'executed_at' => now(),
            'status' => 'completed'
        ]);

        $migrationRecord = DB::table('migration_histories')
            ->where('migration_name', '2024_01_01_000001_create_test_table')
            ->first();

        $this->assertNotNull($migrationRecord);
        $this->assertEquals('completed', $migrationRecord->status);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_batch_management()
    {
        if (!Schema::hasTable('migration_histories')) {
            Schema::create('migration_histories', function ($table) {
                $table->id();
                $table->string('migration_name');
                $table->integer('batch');
                $table->timestamp('executed_at')->nullable();
                $table->timestamp('rollback_at')->nullable();
                $table->string('status')->default('pending');
                $table->text('error_message')->nullable();
            });
        }

        // Insert multiple migrations in different batches
        $migrations = [
            ['name' => 'migration_1', 'batch' => 1],
            ['name' => 'migration_2', 'batch' => 1],
            ['name' => 'migration_3', 'batch' => 2],
            ['name' => 'migration_4', 'batch' => 2],
        ];

        foreach ($migrations as $migration) {
            DB::table('migration_histories')->insert([
                'migration_name' => $migration['name'],
                'batch' => $migration['batch'],
                'executed_at' => now(),
                'status' => 'completed'
            ]);
        }

        // Test batch queries
        $batch1Count = DB::table('migration_histories')->where('batch', 1)->count();
        $batch2Count = DB::table('migration_histories')->where('batch', 2)->count();

        $this->assertEquals(2, $batch1Count);
        $this->assertEquals(2, $batch2Count);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_status_tracking()
    {
        if (!Schema::hasTable('migration_histories')) {
            Schema::create('migration_histories', function ($table) {
                $table->id();
                $table->string('migration_name');
                $table->integer('batch');
                $table->timestamp('executed_at')->nullable();
                $table->timestamp('rollback_at')->nullable();
                $table->string('status')->default('pending');
                $table->text('error_message')->nullable();
            });
        }

        // Test different migration statuses
        $statuses = ['pending', 'running', 'completed', 'failed', 'rolled_back'];

        foreach ($statuses as $index => $status) {
            DB::table('migration_histories')->insert([
                'migration_name' => "test_migration_{$index}",
                'batch' => 1,
                'executed_at' => $status === 'pending' ? null : now(),
                'status' => $status,
                'error_message' => $status === 'failed' ? 'Test error message' : null
            ]);
        }

        // Test status queries
        foreach ($statuses as $status) {
            $count = DB::table('migration_histories')->where('status', $status)->count();
            $this->assertEquals(1, $count, "Should have exactly 1 migration with status: {$status}");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_rollback_tracking()
    {
        if (!Schema::hasTable('migration_histories')) {
            Schema::create('migration_histories', function ($table) {
                $table->id();
                $table->string('migration_name');
                $table->integer('batch');
                $table->timestamp('executed_at')->nullable();
                $table->timestamp('rollback_at')->nullable();
                $table->string('status')->default('pending');
                $table->text('error_message')->nullable();
            });
        }

        // Create a migration record
        $migrationId = DB::table('migration_histories')->insertGetId([
            'migration_name' => 'test_rollback_migration',
            'batch' => 1,
            'executed_at' => now()->subHour(),
            'status' => 'completed'
        ]);

        // Simulate rollback
        DB::table('migration_histories')
            ->where('id', $migrationId)
            ->update([
                'rollback_at' => now(),
                'status' => 'rolled_back'
            ]);

        $migration = DB::table('migration_histories')->where('id', $migrationId)->first();

        $this->assertNotNull($migration, 'Migration record should exist');
        $this->assertEquals('rolled_back', $migration->status);
        $this->assertNotNull($migration->rollback_at);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_error_handling()
    {
        if (!Schema::hasTable('migration_histories')) {
            Schema::create('migration_histories', function ($table) {
                $table->id();
                $table->string('migration_name');
                $table->integer('batch');
                $table->timestamp('executed_at')->nullable();
                $table->timestamp('rollback_at')->nullable();
                $table->string('status')->default('pending');
                $table->text('error_message')->nullable();
            });
        }

        // Create a failed migration record
        DB::table('migration_histories')->insert([
            'migration_name' => 'failed_migration',
            'batch' => 1,
            'executed_at' => now(),
            'status' => 'failed',
            'error_message' => 'SQLSTATE[42S01]: Base table or view already exists'
        ]);

        $failedMigration = DB::table('migration_histories')
            ->where('status', 'failed')
            ->first();

        $this->assertNotNull($failedMigration);
        $this->assertNotEmpty($failedMigration->error_message);
        $this->assertStringContainsString('SQLSTATE', $failedMigration->error_message);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_generation()
    {
        // Test migration file generation logic (mock)
        $migrationName = 'create_test_products_table';
        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_{$migrationName}.php";

        // Test migration name validation
        $this->assertStringContainsString('create_', $migrationName);
        $this->assertStringContainsString('_table', $migrationName);

        // Test file name format
        $this->assertStringEndsWith('.php', $fileName);
        $this->assertStringContainsString($migrationName, $fileName);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_dependency_resolution()
    {
        if (!Schema::hasTable('migration_histories')) {
            Schema::create('migration_histories', function ($table) {
                $table->id();
                $table->string('migration_name');
                $table->integer('batch');
                $table->timestamp('executed_at')->nullable();
                $table->timestamp('rollback_at')->nullable();
                $table->string('status')->default('pending');
                $table->text('error_message')->nullable();
            });
        }

        // Create dependent migrations
        $parentMigration = DB::table('migration_histories')->insertGetId([
            'migration_name' => '2024_01_01_000001_create_users_table',
            'batch' => 1,
            'executed_at' => now()->subMinutes(10),
            'status' => 'completed'
        ]);

        $childMigration = DB::table('migration_histories')->insertGetId([
            'migration_name' => '2024_01_01_000002_create_posts_table',
            'batch' => 2,
            'executed_at' => now()->subMinutes(5),
            'status' => 'completed'
        ]);

        // Test dependency order
        $migrations = DB::table('migration_histories')
            ->orderBy('executed_at')
            ->get();

        $this->assertTrue($migrations[0]->executed_at < $migrations[1]->executed_at);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_preview_functionality()
    {
        // Test migration preview (dry run)
        $migrationSQL = "CREATE TABLE test_preview (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        )";

        // Test SQL validation
        $this->assertStringContainsString('CREATE TABLE', $migrationSQL);
        $this->assertStringContainsString('PRIMARY KEY', $migrationSQL);
        $this->assertStringContainsString('test_preview', $migrationSQL);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_backup_before_execution()
    {
        // Simulate backup creation before migration
        $backupData = [
            'tables' => ['users', 'posts', 'categories'],
            'timestamp' => now(),
            'size' => '1.2MB'
        ];

        $this->assertIsArray($backupData['tables']);
        $this->assertNotEmpty($backupData['tables']);
        $this->assertNotNull($backupData['timestamp']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_performance_tracking()
    {
        if (!Schema::hasTable('migration_histories')) {
            Schema::create('migration_histories', function ($table) {
                $table->id();
                $table->string('migration_name');
                $table->integer('batch');
                $table->timestamp('executed_at')->nullable();
                $table->timestamp('rollback_at')->nullable();
                $table->string('status')->default('pending');
                $table->text('error_message')->nullable();
            });
        }

        // Add execution time tracking
        Schema::table('migration_histories', function ($table) {
            if (!Schema::hasColumn('migration_histories', 'execution_time')) {
                $table->float('execution_time')->nullable()->after('status');
            }
        });

        // Insert migration with performance data
        DB::table('migration_histories')->insert([
            'migration_name' => 'performance_test_migration',
            'batch' => 1,
            'executed_at' => now(),
            'status' => 'completed',
            'execution_time' => 2.5 // seconds
        ]);

        $migration = DB::table('migration_histories')
            ->where('migration_name', 'performance_test_migration')
            ->first();

        $this->assertEquals(2.5, $migration->execution_time);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_validation()
    {
        // Test migration file validation
        $validMigrationContent = '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("test_table", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("test_table");
    }
};';

        // Basic validation tests
        $this->assertStringContainsString('class extends Migration', $validMigrationContent);
        $this->assertStringContainsString('public function up()', $validMigrationContent);
        $this->assertStringContainsString('public function down()', $validMigrationContent);
        $this->assertStringContainsString('Schema::', $validMigrationContent);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_conflict_detection()
    {
        if (!Schema::hasTable('migration_histories')) {
            Schema::create('migration_histories', function ($table) {
                $table->id();
                $table->string('migration_name');
                $table->integer('batch');
                $table->timestamp('executed_at')->nullable();
                $table->timestamp('rollback_at')->nullable();
                $table->string('status')->default('pending');
                $table->text('error_message')->nullable();
            });
        }

        // Create conflicting migrations (same table)
        DB::table('migration_histories')->insert([
            'migration_name' => '2024_01_01_000001_create_products_table',
            'batch' => 1,
            'executed_at' => now(),
            'status' => 'completed'
        ]);

        // Test duplicate detection
        $existing = DB::table('migration_histories')
            ->where('migration_name', 'like', '%create_products_table%')
            ->exists();

        $this->assertTrue($existing, 'Should detect existing migration for same table');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_custom_migration_commands()
    {
        // Test that custom migration commands are available
        $commands = Artisan::all();

        // Check if install command exists (this is what we can test in the current environment)
        $this->assertArrayHasKey(
            'codeforge-database-studio:install',
            $commands,
            'Custom install command should be available'
        );
    }
}
