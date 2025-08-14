<?php

namespace HkDevs\CodeForgeStudio\Tests\Integration\Commands;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstallCommandExecutionTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_install_command_is_available()
    {
        // Test that the command is registered and available
        $this->artisan('codeforge-database-studio:install')
            ->assertExitCode(Command::SUCCESS);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_install_command_with_force_option()
    {
        // Test that the force option is recognized
        $this->artisan('codeforge-database-studio:install', ['--force' => true])
            ->assertExitCode(Command::SUCCESS);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_install_command_can_run_multiple_times()
    {
        // First run
        $this->artisan('codeforge-database-studio:install')
            ->assertExitCode(Command::SUCCESS);

        // Second run should also succeed (idempotent)
        $this->artisan('codeforge-database-studio:install')
            ->assertExitCode(Command::SUCCESS);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_install_command_handles_force_flag_multiple_times()
    {
        // First run with force
        $this->artisan('codeforge-database-studio:install', ['--force' => true])
            ->assertExitCode(Command::SUCCESS);

        // Second run with force should also succeed
        $this->artisan('codeforge-database-studio:install', ['--force' => true])
            ->assertExitCode(Command::SUCCESS);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_install_command_basic_execution()
    {
        // Very basic test - just ensure it runs without fatal errors
        $exitCode = $this->artisan('codeforge-database-studio:install')->run();
        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_signature_validation()
    {
        // Test that invalid arguments are properly handled
        $this->expectException(\Symfony\Component\Console\Exception\RuntimeException::class);
        $this->expectExceptionMessage('No arguments expected');

        $this->artisan('codeforge-database-studio:install invalid-arg');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_help_information()
    {
        // Test help output is available
        $this->artisan('codeforge-database-studio:install', ['--help' => true])
            ->assertExitCode(0);
    }
}
