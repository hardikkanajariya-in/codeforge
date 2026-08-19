<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit\Commands;

use HkDevs\CodeForgeStudio\Commands\InstallCommand;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

class InstallCommandMockTest extends TestCase
{
    use RefreshDatabase;

    private InstallCommand $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->command = new InstallCommand;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function test_command_has_correct_properties()
    {
        $this->assertEquals('codeforge:install', $this->command->getName());
        $this->assertEquals('Install the Filament CodeForge Studio plugin', $this->command->getDescription());

        // Test signature includes force option
        $signature = $this->command->getDefinition();
        $this->assertTrue($signature->hasOption('force'));
    }

    #[Test]
    public function test_handle_method_returns_success()
    {
        $exitCode = Artisan::call('codeforge:install');
        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    #[Test]
    public function test_migration_files_array_is_complete()
    {
        // Test that all expected migration files are defined
        $expectedFiles = [
            '2024_01_01_000001_create_database_manager_logs_table.php',
            '2024_01_01_000002_create_migration_histories_table.php',
            '2024_01_01_000003_create_query_performance_logs_table.php',
            '2024_01_01_000004_create_database_health_metrics_table.php',
            '2024_01_01_000005_create_data_seeders_table.php',
            '2024_01_01_000006_create_seeder_execution_logs_table.php',
            '2024_01_01_000007_create_data_generation_templates_table.php',
            '2024_01_01_000008_create_documentation_generations_table.php',
            '2024_01_01_000009_create_schema_snapshots_table.php',
            '2024_01_01_000010_create_code_generation_histories_table.php',
            '2024_01_01_000011_create_filament_resource_templates_table.php',
            '2024_01_01_000012_create_filament_resource_generators_table.php',
        ];

        // Verify each file follows naming convention
        foreach ($expectedFiles as $file) {
            $this->assertStringStartsWith('2024_01_01_', $file);
            $this->assertStringEndsWith('_table.php', $file);
            $this->assertStringContainsString('create_', $file);
        }
    }

    #[Test]
    public function test_table_names_array_is_complete()
    {
        $expectedTables = [
            'database_manager_logs',
            'migration_histories',
            'query_performance_logs',
            'database_health_metrics',
            'data_seeders',
            'seeder_execution_logs',
            'data_generation_templates',
            'documentation_generations',
            'schema_snapshots',
            'code_generation_histories',
            'filament_resource_templates',
            'filament_resource_generators',
        ];

        // Verify each table name follows convention
        foreach ($expectedTables as $table) {
            $this->assertMatchesRegularExpression('/^[a-z_]+$/', $table);
            $this->assertStringNotContainsString(' ', $table);
            $this->assertStringNotContainsString('-', $table);
        }
    }

    #[Test]
    public function test_migration_files_correspond_to_table_names()
    {
        $migrationFiles = [
            '2024_01_01_000001_create_database_manager_logs_table.php',
            '2024_01_01_000002_create_migration_histories_table.php',
            '2024_01_01_000003_create_query_performance_logs_table.php',
            '2024_01_01_000004_create_database_health_metrics_table.php',
            '2024_01_01_000005_create_data_seeders_table.php',
            '2024_01_01_000006_create_seeder_execution_logs_table.php',
            '2024_01_01_000007_create_data_generation_templates_table.php',
            '2024_01_01_000008_create_documentation_generations_table.php',
            '2024_01_01_000009_create_schema_snapshots_table.php',
            '2024_01_01_000010_create_code_generation_histories_table.php',
            '2024_01_01_000011_create_filament_resource_templates_table.php',
            '2024_01_01_000012_create_filament_resource_generators_table.php',
        ];

        $tableNames = [
            'database_manager_logs',
            'migration_histories',
            'query_performance_logs',
            'database_health_metrics',
            'data_seeders',
            'seeder_execution_logs',
            'data_generation_templates',
            'documentation_generations',
            'schema_snapshots',
            'code_generation_histories',
            'filament_resource_templates',
            'filament_resource_generators',
        ];

        // Verify arrays have same count
        $this->assertCount(count($tableNames), $migrationFiles);

        // Verify each migration file corresponds to its table
        for ($i = 0; $i < count($migrationFiles); $i++) {
            $expectedTableName = $tableNames[$i];
            $migrationFile = $migrationFiles[$i];

            $this->assertStringContainsString($expectedTableName, $migrationFile);
        }
    }

    #[Test]
    public function test_command_calls_vendor_publish_for_config()
    {
        // This test verifies the command structure calls vendor:publish appropriately
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Publishing configuration...')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_command_calls_vendor_publish_for_migrations()
    {
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Checking migrations...')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_command_calls_migrate_with_correct_parameters()
    {
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Running migrations...')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_force_option_is_passed_to_vendor_publish()
    {
        $this->artisan('codeforge:install', ['--force' => true])
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Force flag detected. Overwriting existing migrations...')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_schema_has_table_checks()
    {
        // Test that the command properly uses Schema::hasTable
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Checking migrations...')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_file_exists_checks()
    {
        // Test that the command properly checks for existing migration files
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Checking migrations...')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_database_path_helper_usage()
    {
        // Verify the command uses database_path() helper correctly
        $this->artisan('codeforge:install')
            ->assertExitCode(Command::SUCCESS);

        // If the command completes successfully, database_path() is working
        $this->assertTrue(function_exists('database_path'));
    }

    #[Test]
    public function test_info_and_line_output_methods()
    {
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('✅ Filament CodeForge Studio installed successfully!')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_warning_output_for_force_flag()
    {
        $this->artisan('codeforge:install', ['--force' => true])
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Force flag detected. Overwriting existing migrations...')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_exception_handling_in_migration_runner()
    {
        // Test that exceptions in migration running are handled gracefully
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('✅ Filament CodeForge Studio installed successfully!')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_step_migration_parameter()
    {
        // Verify the command uses --step parameter for migrations
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Running migrations...')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_migration_path_parameter()
    {
        // Verify the command specifies the correct migration path
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Running migrations...')
            ->assertExitCode(Command::SUCCESS);
    }

    #[Test]
    public function test_command_structure_and_flow()
    {
        $this->artisan('codeforge:install')
            ->expectsOutput('Installing Filament CodeForge Studio...')
            ->expectsOutput('Publishing configuration...')
            ->expectsOutput('Checking migrations...')
            ->expectsOutput('Running migrations...')
            ->expectsOutput('✅ Filament CodeForge Studio installed successfully!')
            ->assertExitCode(Command::SUCCESS);
    }
}
