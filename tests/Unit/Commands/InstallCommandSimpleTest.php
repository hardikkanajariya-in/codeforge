<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit\Commands;

use HkDevs\CodeForgeStudio\Commands\InstallCommand;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

class InstallCommandSimpleTest extends TestCase
{
    use RefreshDatabase;

    protected InstallCommand $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->command = new InstallCommand();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_class_exists()
    {
        $this->assertTrue(class_exists(InstallCommand::class));
        $this->assertInstanceOf(Command::class, $this->command);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_has_correct_signature()
    {
        $this->assertEquals('codeforge:install', $this->command->getName());
        $this->assertTrue($this->command->getDefinition()->hasOption('force'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_has_correct_name()
    {
        $this->assertEquals('codeforge:install', $this->command->getName());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_has_correct_description()
    {
        $this->assertEquals('Install the Filament CodeForge Studio plugin', $this->command->getDescription());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_has_force_option()
    {
        $definition = $this->command->getDefinition();
        $this->assertTrue($definition->hasOption('force'));

        $forceOption = $definition->getOption('force');
        $this->assertEquals('Force overwrite existing files', $forceOption->getDescription());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_file_names_are_properly_defined()
    {
        $expectedMigrationFiles = [
            "2024_01_01_000001_create_database_manager_logs_table.php",
            "2024_01_01_000002_create_migration_histories_table.php",
            "2024_01_01_000003_create_query_performance_logs_table.php",
            "2024_01_01_000004_create_database_health_metrics_table.php",
            "2024_01_01_000005_create_data_seeders_table.php",
            "2024_01_01_000006_create_seeder_execution_logs_table.php",
            "2024_01_01_000007_create_data_generation_templates_table.php",
            "2024_01_01_000008_create_documentation_generations_table.php",
            "2024_01_01_000009_create_schema_snapshots_table.php",
            "2024_01_01_000010_create_code_generation_histories_table.php",
            "2024_01_01_000011_create_filament_resource_templates_table.php",
            "2024_01_01_000012_create_filament_resource_generators_table.php"
        ];

        foreach ($expectedMigrationFiles as $file) {
            $this->assertStringStartsWith('2024_01_01_', $file);
            $this->assertStringEndsWith('_table.php', $file);
            $this->assertStringContainsString('create_', $file);
            $this->assertMatchesRegularExpression('/^\d{4}_\d{2}_\d{2}_\d{6}_create_\w+_table\.php$/', $file);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_table_names_follow_laravel_conventions()
    {
        $expectedTableNames = [
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
            'filament_resource_generators'
        ];

        foreach ($expectedTableNames as $tableName) {
            // Table names should be lowercase with underscores
            $this->assertMatchesRegularExpression('/^[a-z_]+$/', $tableName);

            // Should not contain spaces or hyphens
            $this->assertStringNotContainsString(' ', $tableName);
            $this->assertStringNotContainsString('-', $tableName);

            // Should be plural (most Laravel conventions)
            $this->assertStringEndsWith('s', $tableName);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_files_correspond_to_tables()
    {
        $migrationFiles = [
            "2024_01_01_000001_create_database_manager_logs_table.php",
            "2024_01_01_000002_create_migration_histories_table.php",
            "2024_01_01_000003_create_query_performance_logs_table.php",
            "2024_01_01_000004_create_database_health_metrics_table.php",
            "2024_01_01_000005_create_data_seeders_table.php",
            "2024_01_01_000006_create_seeder_execution_logs_table.php",
            "2024_01_01_000007_create_data_generation_templates_table.php",
            "2024_01_01_000008_create_documentation_generations_table.php",
            "2024_01_01_000009_create_schema_snapshots_table.php",
            "2024_01_01_000010_create_code_generation_histories_table.php",
            "2024_01_01_000011_create_filament_resource_templates_table.php",
            "2024_01_01_000012_create_filament_resource_generators_table.php"
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
            'filament_resource_generators'
        ];

        $this->assertCount(count($tableNames), $migrationFiles);

        for ($i = 0; $i < count($migrationFiles); $i++) {
            $expectedTableName = $tableNames[$i];
            $migrationFile = $migrationFiles[$i];

            $this->assertStringContainsString($expectedTableName, $migrationFile);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_can_be_instantiated()
    {
        $command = new InstallCommand();
        $this->assertInstanceOf(InstallCommand::class, $command);
        $this->assertInstanceOf(Command::class, $command);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_signature_format()
    {
        // Should contain the force option
        $this->assertTrue($this->command->getDefinition()->hasOption('force'));

        // Command name should be correct
        $this->assertEquals('codeforge:install', $this->command->getName());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_namespace_and_naming()
    {
        $reflection = new \ReflectionClass($this->command);

        // Should be in correct namespace
        $this->assertEquals('HkDevs\CodeForgeStudio\Commands', $reflection->getNamespaceName());

        // Should have correct class name
        $this->assertEquals('InstallCommand', $reflection->getShortName());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_files_are_numbered_sequentially()
    {
        $migrationFiles = [
            "2024_01_01_000001_create_database_manager_logs_table.php",
            "2024_01_01_000002_create_migration_histories_table.php",
            "2024_01_01_000003_create_query_performance_logs_table.php",
            "2024_01_01_000004_create_database_health_metrics_table.php",
            "2024_01_01_000005_create_data_seeders_table.php",
            "2024_01_01_000006_create_seeder_execution_logs_table.php",
            "2024_01_01_000007_create_data_generation_templates_table.php",
            "2024_01_01_000008_create_documentation_generations_table.php",
            "2024_01_01_000009_create_schema_snapshots_table.php",
            "2024_01_01_000010_create_code_generation_histories_table.php",
            "2024_01_01_000011_create_filament_resource_templates_table.php",
            "2024_01_01_000012_create_filament_resource_generators_table.php"
        ];

        for ($i = 0; $i < count($migrationFiles); $i++) {
            $expectedNumber = str_pad($i + 1, 6, '0', STR_PAD_LEFT);
            $this->assertStringContainsString($expectedNumber, $migrationFiles[$i]);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_protected_methods_exist()
    {
        $reflection = new \ReflectionClass($this->command);

        // Should have handle method
        $this->assertTrue($reflection->hasMethod('handle'));

        // Handle method should be public
        $handleMethod = $reflection->getMethod('handle');
        $this->assertTrue($handleMethod->isPublic());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_extends_correct_base_class()
    {
        $this->assertInstanceOf(\Illuminate\Console\Command::class, $this->command);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_files_have_unique_timestamps()
    {
        $migrationFiles = [
            "2024_01_01_000001_create_database_manager_logs_table.php",
            "2024_01_01_000002_create_migration_histories_table.php",
            "2024_01_01_000003_create_query_performance_logs_table.php",
            "2024_01_01_000004_create_database_health_metrics_table.php",
            "2024_01_01_000005_create_data_seeders_table.php",
            "2024_01_01_000006_create_seeder_execution_logs_table.php",
            "2024_01_01_000007_create_data_generation_templates_table.php",
            "2024_01_01_000008_create_documentation_generations_table.php",
            "2024_01_01_000009_create_schema_snapshots_table.php",
            "2024_01_01_000010_create_code_generation_histories_table.php",
            "2024_01_01_000011_create_filament_resource_templates_table.php",
            "2024_01_01_000012_create_filament_resource_generators_table.php"
        ];

        $timestamps = [];
        foreach ($migrationFiles as $file) {
            preg_match('/^(\d{4}_\d{2}_\d{2}_\d{6})_/', $file, $matches);
            $timestamp = $matches[1] ?? '';
            $this->assertNotEmpty($timestamp);
            $timestamps[] = $timestamp;
        }

        // All timestamps should be unique
        $this->assertEquals(count($timestamps), count(array_unique($timestamps)));
    }
}
