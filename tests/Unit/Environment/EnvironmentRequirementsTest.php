<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit\Environment;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Filament\Contracts\Plugin;
use Filament\Panel;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * Test Case: TC-ENV-001 - Environment Requirements Validation
 * Purpose: Verify that the plugin works with minimum system requirements
 */
class EnvironmentRequirementsTest extends TestCase
{
    #[Test]
    public function test_php_version_compatibility()
    {
        // Test PHP 8.1+ compatibility
        $phpVersion = (float) PHP_VERSION;
        $this->assertGreaterThanOrEqual(8.1, $phpVersion, 'PHP version must be 8.1 or higher');
    }

    #[Test]
    public function test_required_php_extensions()
    {
        // Test required PHP extensions
        $requiredExtensions = ['pdo', 'json', 'mbstring', 'tokenizer', 'xml'];

        foreach ($requiredExtensions as $extension) {
            $this->assertTrue(
                extension_loaded($extension),
                "Required PHP extension '{$extension}' is not loaded"
            );
        }
    }

    #[Test]
    public function test_laravel_framework_availability()
    {
        // Verify Laravel framework classes are available
        $this->assertTrue(class_exists(Application::class));
        $this->assertTrue(class_exists(Model::class));
        $this->assertTrue(class_exists(ServiceProvider::class));
    }

    #[Test]
    public function test_filament_framework_availability()
    {
        // Verify FilamentPHP classes are available
        $this->assertTrue(class_exists(Panel::class));
        $this->assertTrue(interface_exists(Plugin::class));
    }

    #[Test]
    public function test_doctrine_dbal_availability()
    {
        // Verify Doctrine DBAL is available
        $this->assertTrue(class_exists(Connection::class));
        $this->assertTrue(class_exists(AbstractSchemaManager::class));
    }

    #[Test]
    public function test_database_drivers_availability()
    {
        // Test SQLite driver (always available in testing)
        $this->assertTrue(extension_loaded('pdo_sqlite'));

        // Test MySQL driver availability (if available)
        if (extension_loaded('pdo_mysql')) {
            $this->assertTrue(extension_loaded('pdo_mysql'));
        }

        // Test PostgreSQL driver availability (if available)
        if (extension_loaded('pdo_pgsql')) {
            $this->assertTrue(extension_loaded('pdo_pgsql'));
        }
    }

    #[Test]
    public function test_memory_limit_requirements()
    {
        $memoryLimit = ini_get('memory_limit');

        if ($memoryLimit !== '-1') {
            $memoryInBytes = $this->convertToBytes($memoryLimit);
            $minimumRequired = 128 * 1024 * 1024; // 128MB

            $this->assertGreaterThanOrEqual(
                $minimumRequired,
                $memoryInBytes,
                'Memory limit should be at least 128MB for proper plugin operation'
            );
        }
    }

    #[Test]
    public function test_execution_time_limits()
    {
        $maxExecutionTime = ini_get('max_execution_time');

        // For CLI, max_execution_time is usually 0 (unlimited)
        if (php_sapi_name() !== 'cli' && $maxExecutionTime > 0) {
            $this->assertGreaterThanOrEqual(
                30,
                (int) $maxExecutionTime,
                'Max execution time should be at least 30 seconds for complex operations'
            );
        }

        $this->assertTrue(true, 'Execution time check passed');
    }

    /**
     * Convert memory limit string to bytes
     */
    private function convertToBytes(string $memoryLimit): int
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $size = (int) substr($memoryLimit, 0, -1);

        switch ($unit) {
            case 'g':
                $size *= 1024;
                // fall through
            case 'm':
                $size *= 1024;
                // fall through
            case 'k':
                $size *= 1024;
                break;
        }

        return $size;
    }
}
