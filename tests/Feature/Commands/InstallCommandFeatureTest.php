<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature\Commands;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;

class InstallCommandFeatureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_user_can_install_plugin_successfully()
    {
        // Act: User runs the install command
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: Installation completes successfully
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);
    }

    #[Test]
    public function test_user_receives_clear_installation_feedback()
    {
        // Act: User runs the install command
        Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: User sees clear progress messages
        $this->assertStringContainsString('Installing Filament CodeForge Studio...', $output);
        $this->assertStringContainsString('Publishing configuration...', $output);
        $this->assertStringContainsString('Checking migrations...', $output);
        $this->assertStringContainsString('Running migrations...', $output);
    }

    #[Test]
    public function test_user_receives_helpful_next_steps()
    {
        // Act: User runs the install command
        Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: User gets actionable next steps
        $this->assertStringContainsString('Next steps:', $output);
        $this->assertStringContainsString('1. Add the plugin to your Filament panel', $output);
        $this->assertStringContainsString('2. Configure settings in config/codeforge-database-studio.php', $output);
    }

    #[Test]
    public function test_user_can_force_reinstall_plugin()
    {
        // Arrange: Install plugin first
        Artisan::call('codeforge-database-studio:install');

        // Act: User forces reinstallation
        $exitCode = Artisan::call('codeforge-database-studio:install', ['--force' => true]);
        $output = Artisan::output();

        // Assert: Force installation works
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('Force flag detected', $output);
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);
    }

    #[Test]
    public function test_user_can_reinstall_without_errors()
    {
        // Arrange: Install plugin first
        Artisan::call('codeforge-database-studio:install');

        // Act: User runs install again without force flag
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: Reinstallation handles existing files gracefully
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);
    }

    #[Test]
    public function test_installation_creates_necessary_database_structure()
    {
        // Act: User runs the install command
        Artisan::call('codeforge-database-studio:install');

        // Assert: The installation process attempts to create database structure
        $output = Artisan::output();
        $this->assertStringContainsString('Running migrations...', $output);
    }

    #[Test]
    public function test_user_sees_professional_output_formatting()
    {
        // Act: User runs the install command
        Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: Output is well-formatted and professional
        $this->assertStringContainsString('✅', $output); // Check mark for success
        $this->assertStringContainsString('Installing Filament CodeForge Studio...', $output);

        // Check for proper line spacing (empty lines for readability)
        $this->assertStringContainsString("\n\n", $output);

        // Check for numbered list in next steps
        $this->assertStringContainsString('1.', $output);
        $this->assertStringContainsString('2.', $output);
    }

    #[Test]
    public function test_installation_handles_missing_migrations_gracefully()
    {
        // Act: User runs install command
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: Command handles missing migrations appropriately
        $this->assertEquals(Command::SUCCESS, $exitCode);

        // Should either publish migrations or handle existing ones
        $this->assertTrue(
            str_contains($output, 'Publishing migrations...') ||
                str_contains($output, 'All migrations already exist') ||
                str_contains($output, 'Missing migration files')
        );
    }

    #[Test]
    public function test_installation_provides_config_file_guidance()
    {
        // Act: User runs the install command
        Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: User is informed about config file location
        $this->assertStringContainsString('config/codeforge-database-studio.php', $output);
        $this->assertStringContainsString('Configure settings', $output);
    }

    #[Test]
    public function test_command_signature_is_user_friendly()
    {
        // Assert: Command has intuitive signature
        $availableCommands = Artisan::all();
        $this->assertArrayHasKey('codeforge-database-studio:install', $availableCommands);

        $command = $availableCommands['codeforge-database-studio:install'];
        $this->assertEquals('Install the Filament CodeForge Studio plugin', $command->getDescription());
    }

    #[Test]
    public function test_force_option_is_documented()
    {
        // Act: Get command information
        $availableCommands = Artisan::all();
        $command = $availableCommands['codeforge-database-studio:install'];

        // Assert: Force option exists and is documented
        $definition = $command->getDefinition();
        $this->assertTrue($definition->hasOption('force'));

        $forceOption = $definition->getOption('force');
        $this->assertStringContainsString('Force', $forceOption->getDescription());
    }

    #[Test]
    public function test_installation_workflow_is_logical()
    {
        // Act: User runs the install command
        Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: Steps occur in logical order
        $configPos = strpos($output, 'Publishing configuration');
        $migrationCheckPos = strpos($output, 'Checking migrations');
        $migrationRunPos = strpos($output, 'Running migrations');
        $successPos = strpos($output, '✅ Filament CodeForge Studio installed successfully!');

        // Configuration should come first
        $this->assertNotFalse($configPos);
        $this->assertNotFalse($migrationCheckPos);
        $this->assertNotFalse($migrationRunPos);
        $this->assertNotFalse($successPos);

        // Verify logical order
        $this->assertLessThan($migrationCheckPos, $configPos);
        $this->assertLessThan($migrationRunPos, $migrationCheckPos);
        $this->assertLessThan($successPos, $migrationRunPos);
    }

    #[Test]
    public function test_user_experience_with_existing_installation()
    {
        // Arrange: Install plugin first
        Artisan::call('codeforge-database-studio:install');

        // Act: User runs install again (common user behavior)
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: User gets appropriate feedback about existing installation
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);

        // Should inform about existing state
        $this->assertTrue(
            str_contains($output, 'already exist') ||
                str_contains($output, 'already been run') ||
                str_contains($output, 'Force flag') ||
                str_contains($output, 'Skipping')
        );
    }

    #[Test]
    public function test_installation_error_recovery()
    {
        // This test ensures users can recover from partial installations

        // Act: Run installation (might have some issues in test environment)
        $exitCode = Artisan::call('codeforge-database-studio:install');

        // Assert: Command completes gracefully even with potential issues
        $this->assertEquals(Command::SUCCESS, $exitCode);

        // Act: Run again to test recovery
        $secondExitCode = Artisan::call('codeforge-database-studio:install');

        // Assert: Recovery works
        $this->assertEquals(Command::SUCCESS, $secondExitCode);
    }

    #[Test]
    public function test_command_provides_complete_user_journey()
    {
        // Act: Complete installation journey
        $exitCode = Artisan::call('codeforge-database-studio:install');
        $output = Artisan::output();

        // Assert: User has complete information for next steps
        $this->assertEquals(Command::SUCCESS, $exitCode);

        // Should contain all necessary information
        $this->assertStringContainsString('Installing Filament CodeForge Studio...', $output);
        $this->assertStringContainsString('✅ Filament CodeForge Studio installed successfully!', $output);
        $this->assertStringContainsString('Next steps:', $output);
        $this->assertStringContainsString('Add the plugin to your Filament panel', $output);
        $this->assertStringContainsString('Configure settings', $output);

        // User should know where to find config
        $this->assertStringContainsString('config/codeforge-database-studio.php', $output);
    }
}
