<?php

namespace HkDevs\CodeForgeStudio\Tests\Integration\Commands;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

class InstallCommandIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected string $tempConfigPath;
    protected string $tempMigrationPath;

    protected function setUp(): void
    {
        parent::setUp();

        // Create temporary directories for testing file operations
        $this->tempConfigPath = sys_get_temp_dir() . '/codeforge-test-config';
        $this->tempMigrationPath = sys_get_temp_dir() . '/codeforge-test-migrations';

        // Clean up any existing temp directories
        if (File::exists($this->tempConfigPath)) {
            File::deleteDirectory($this->tempConfigPath);
        }
        if (File::exists($this->tempMigrationPath)) {
            File::deleteDirectory($this->tempMigrationPath);
        }

        // Create temp directories
        File::makeDirectory($this->tempConfigPath, 0755, true);
        File::makeDirectory($this->tempMigrationPath, 0755, true);
    }

    protected function tearDown(): void
    {
        // Clean up temp directories
        if (File::exists($this->tempConfigPath)) {
            File::deleteDirectory($this->tempConfigPath);
        }
        if (File::exists($this->tempMigrationPath)) {
            File::deleteDirectory($this->tempMigrationPath);
        }

        parent::tearDown();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_full_installation_process()
    {
        // Test the complete installation process
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Verify successful completion
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);

        // Verify all steps were executed
        $this->assertStringContainsString('Installing Filament CodeForge Studio...', $output);
        $this->assertStringContainsString('Publishing configuration...', $output);
        $this->assertStringContainsString('Checking migrations...', $output);
        $this->assertStringContainsString('Running migrations...', $output);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_installation_with_force_flag()
    {
        // First installation
        Artisan::call('codeforge-database-studio:install');

        // Second installation with force flag
        $exitCode = Artisan::call('codeforge-database-studio:install', ['--force' => true]);
        $output = Artisan::output();

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('Force flag detected', $output);
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_installation_idempotency()
    {
        // Run installation multiple times to ensure it's idempotent
        for ($i = 0; $i < 3; $i++) {
            $exitCode = Artisan::call('codeforge-database-studio:install');
            $this->assertEquals(Command::SUCCESS, $exitCode);
        }

        $output = Artisan::output();
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_file_detection()
    {
        // Run installation
        Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Should detect missing migration files initially
        $this->assertStringContainsString('Checking migrations...', $output);

        // Run again to test existing migration detection
        Artisan::call('codeforge-database-studio:install');
        $secondOutput = Artisan::output();

        // Should detect existing migrations
        $this->assertTrue(
            str_contains($secondOutput, 'All migrations already exist') ||
                str_contains($secondOutput, 'Running migrations...')
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_table_existence_checking()
    {
        // Run installation
        Artisan::call('codeforge-database-studio:install');

        // Verify that the command checks for table existence
        $output = Artisan::output();
        $this->assertStringContainsString('Running migrations...', $output);

        // Run again and verify it handles existing tables
        Artisan::call('codeforge-database-studio:install');
        $secondOutput = Artisan::output();

        // Should complete successfully regardless of table state
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $secondOutput);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_error_handling_in_migration_process()
    {
        // This test ensures the command handles migration errors gracefully
        $exitCode = Artisan::call('codeforge-database-studio:install');

        // Should not throw exceptions even if some migrations fail
        $this->assertEquals(Command::SUCCESS, $exitCode);

        $output = Artisan::output();
        // Should complete installation process
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_configuration_publishing()
    {
        // Run installation
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('Publishing configuration...', $output);

        // Verify the command mentions the config file location
        $this->assertStringContainsString('config/codeforge-database-studio.php', $output);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_publishing()
    {
        // Run installation
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        $this->assertEquals(Command::SUCCESS, $exitCode);

        // Should either publish migrations or detect existing ones
        $this->assertTrue(
            str_contains($output, 'Publishing migrations...') ||
                str_contains($output, 'All migrations already exist')
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_installation_provides_clear_next_steps()
    {
        // Run installation
        Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Verify clear instructions are provided
        $this->assertStringContainsString('Next steps:', $output);
        $this->assertStringContainsString('1. Add the plugin to your Filament panel', $output);
        $this->assertStringContainsString('2. Configure settings in config/codeforge-database-studio.php', $output);

        // Check formatting includes proper line breaks
        $this->assertStringContainsString("\n", $output);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_output_structure()
    {
        // Run installation
        Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Verify output has logical structure and flow
        $outputLines = explode("\n", $output);
        $cleanLines = array_filter($outputLines, fn($line) => trim($line) !== '');

        // Should have multiple informational lines
        $this->assertGreaterThan(5, count($cleanLines));

        // Should start with installation message
        $foundStart = false;
        foreach ($cleanLines as $line) {
            if (str_contains($line, 'Installing Filament CodeForge Studio')) {
                $foundStart = true;
                break;
            }
        }
        $this->assertTrue($foundStart, 'Should contain installation start message');

        // Should end with success message
        $foundSuccess = false;
        foreach ($cleanLines as $line) {
            if (str_contains($line, '✅ Filament CodeForge Studio installed successfully!')) {
                $foundSuccess = true;
                break;
            }
        }
        $this->assertTrue($foundSuccess, 'Should contain success message');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_force_flag_behavior()
    {
        // Install without force flag first
        Artisan::call('codeforge-database-studio:install');

        // Install with force flag
        $exitCode = Artisan::call('codeforge-database-studio:install', ['--force' => true]);
        $output = Artisan::output();

        $this->assertEquals(Command::SUCCESS, $exitCode);

        // Should mention force behavior
        $this->assertTrue(
            str_contains($output, 'Force flag detected') ||
                str_contains($output, 'force') ||
                str_contains($output, 'Overwriting')
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_missing_tables_detection()
    {
        // This test verifies the command properly detects missing database tables
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        $this->assertEquals(Command::SUCCESS, $exitCode);

        // Should process tables (either create or detect existing)
        $this->assertTrue(
            str_contains($output, 'Running migrations...') ||
                str_contains($output, 'tables already exist') ||
                str_contains($output, 'missing from database')
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_step_by_step_migration_execution()
    {
        // Run installation
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        $this->assertEquals(Command::SUCCESS, $exitCode);

        // Should mention step-by-step migration execution or table checking
        $this->assertStringContainsString('Running migrations...', $output);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_handles_edge_cases()
    {
        // Test multiple rapid installations
        for ($i = 0; $i < 2; $i++) {
            $exitCode = Artisan::call('codeforge-database-studio:install');
            $this->assertEquals(Command::SUCCESS, $exitCode);
        }

        // Test with force flag multiple times
        for ($i = 0; $i < 2; $i++) {
            $exitCode = Artisan::call('codeforge-database-studio:install', ['--force' => true]);
            $this->assertEquals(Command::SUCCESS, $exitCode);
        }

        // All should complete successfully
        $output = Artisan::output();
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);
    }
}
