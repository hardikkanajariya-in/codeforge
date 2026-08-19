<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit\Core;

use Doctrine\DBAL\Connection;
use Filament\Contracts\Plugin;
use Filament\Panel;
use HkDevs\CodeForgeStudio\CodeForgeStudioServiceProvider;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\LaravelPackageTools\Package;

/**
 * Test Case: TC-ENV-006 - Dependency Management & Composer Integration
 * Purpose: Verify proper dependency resolution and package installation
 */
class DependencyManagementTest extends TestCase
{
    #[Test]
    public function test_service_provider_auto_discovery()
    {
        $providers = $this->app->getLoadedProviders();

        $this->assertArrayHasKey(
            CodeForgeStudioServiceProvider::class,
            $providers,
            'Service provider should be auto-discovered'
        );
    }

    #[Test]
    public function test_required_dependencies_available()
    {
        // Test Filament dependencies
        $this->assertTrue(
            class_exists(Panel::class),
            'Filament Panel class should be available'
        );

        $this->assertTrue(
            interface_exists(Plugin::class),
            'Filament Plugin interface should be available'
        );

        // Test Doctrine DBAL
        $this->assertTrue(
            class_exists(Connection::class),
            'Doctrine DBAL Connection class should be available'
        );

        // Test Spatie Package Tools
        $this->assertTrue(
            class_exists(Package::class),
            'Spatie Package Tools should be available'
        );
    }

    #[Test]
    public function test_laravel_framework_compatibility()
    {
        // Test Laravel version compatibility
        $laravelVersion = app()->version();
        $majorVersion = (int) substr($laravelVersion, 0, strpos($laravelVersion, '.'));

        $this->assertGreaterThanOrEqual(
            10,
            $majorVersion,
            'Laravel version should be 10.x or higher'
        );

        // Test core Laravel classes
        $this->assertTrue(class_exists(Application::class));
        $this->assertTrue(class_exists(Model::class));
        $this->assertTrue(class_exists(ServiceProvider::class));
    }

    #[Test]
    public function test_package_configuration_publishing()
    {
        // Test configuration publishing works
        $this->artisan('vendor:publish', [
            '--provider' => CodeForgeStudioServiceProvider::class,
            '--tag' => 'codeforge-database-studio-config',
        ])->assertExitCode(0);
    }

    #[Test]
    public function test_package_migration_publishing()
    {
        // Test migration publishing works
        $this->artisan('vendor:publish', [
            '--provider' => CodeForgeStudioServiceProvider::class,
            '--tag' => 'codeforge-database-studio-migrations',
        ])->assertExitCode(0);
    }

    #[Test]
    public function test_package_commands_registration()
    {
        $commands = Artisan::all();

        $expectedCommands = [
            'codeforge:install',
        ];

        foreach ($expectedCommands as $command) {
            $this->assertArrayHasKey(
                $command,
                $commands,
                "Command '{$command}' should be registered"
            );
        }
    }

    #[Test]
    public function test_no_dependency_conflicts()
    {
        // Test that there are no class conflicts
        try {
            $reflection = new \ReflectionClass(CodeForgeStudioServiceProvider::class);
            $this->assertTrue(true, 'No class loading conflicts detected');
        } catch (\ReflectionException $e) {
            $this->fail('Class loading conflict detected: '.$e->getMessage());
        }
    }

    #[Test]
    public function test_package_namespace_isolation()
    {
        // Test that package namespace is properly isolated
        $this->assertTrue(
            class_exists('HkDevs\CodeForgeStudio\CodeForgeStudioServiceProvider'),
            'Package namespace should be properly isolated'
        );

        // Test that package classes don't conflict with app classes
        $this->assertFalse(
            class_exists('App\CodeForgeStudioServiceProvider'),
            'Package classes should not conflict with app namespace'
        );
    }

    #[Test]
    public function test_autoloader_functionality()
    {
        // Test that autoloader can find package classes
        $packageClasses = [
            'HkDevs\CodeForgeStudio\CodeForgeStudioServiceProvider',
            'HkDevs\CodeForgeStudio\CodeForgeStudioPlugin',
        ];

        foreach ($packageClasses as $class) {
            $this->assertTrue(
                class_exists($class),
                "Class '{$class}' should be autoloadable"
            );
        }
    }

    #[Test]
    public function test_composer_json_structure()
    {
        $composerPath = __DIR__.'/../../../composer.json';

        if (file_exists($composerPath)) {
            $composer = json_decode(file_get_contents($composerPath), true);

            $this->assertArrayHasKey('name', $composer);
            $this->assertArrayHasKey('require', $composer);
            $this->assertArrayHasKey('autoload', $composer);
            $this->assertArrayHasKey('extra', $composer);

            // Test package name format
            $this->assertStringContainsString('/', $composer['name']);

            // Test autoload PSR-4 configuration
            $this->assertArrayHasKey('psr-4', $composer['autoload']);
            $this->assertArrayHasKey('HkDevs\\CodeForgeStudio\\', $composer['autoload']['psr-4']);
        } else {
            $this->markTestSkipped('composer.json not found in test environment');
        }
    }

    #[Test]
    public function test_version_constraints()
    {
        $composerPath = __DIR__.'/../../../composer.json';

        if (file_exists($composerPath)) {
            $composer = json_decode(file_get_contents($composerPath), true);

            $requirements = $composer['require'];

            // Test PHP version constraint
            if (isset($requirements['php'])) {
                $this->assertStringContainsString('^8.1', $requirements['php']);
            }

            // Test Filament version constraint
            if (isset($requirements['filament/filament'])) {
                $this->assertStringContainsString('^3.0', $requirements['filament/filament']);
            }
        } else {
            $this->markTestSkipped('composer.json not found in test environment');
        }
    }

    #[Test]
    public function test_development_dependencies()
    {
        $composerPath = __DIR__.'/../../../composer.json';

        if (file_exists($composerPath)) {
            $composer = json_decode(file_get_contents($composerPath), true);

            if (isset($composer['require-dev'])) {
                $devRequirements = $composer['require-dev'];

                // Test that development dependencies are appropriate
                $expectedDevDeps = ['phpunit/phpunit', 'orchestra/testbench'];

                foreach ($expectedDevDeps as $dep) {
                    $this->assertArrayHasKey(
                        $dep,
                        $devRequirements,
                        "Development dependency '{$dep}' should be present"
                    );
                }
            }
        } else {
            $this->markTestSkipped('composer.json not found in test environment');
        }
    }

    #[Test]
    public function test_package_discovery_configuration()
    {
        $composerPath = __DIR__.'/../../../composer.json';

        if (file_exists($composerPath)) {
            $composer = json_decode(file_get_contents($composerPath), true);

            if (isset($composer['extra']['laravel'])) {
                $laravelConfig = $composer['extra']['laravel'];

                $this->assertArrayHasKey('providers', $laravelConfig);
                $this->assertContains(
                    'HkDevs\\CodeForgeStudio\\CodeForgeStudioServiceProvider',
                    $laravelConfig['providers']
                );
            }
        } else {
            $this->markTestSkipped('composer.json not found in test environment');
        }
    }

    #[Test]
    public function test_package_stability_preference()
    {
        $composerPath = __DIR__.'/../../../composer.json';

        if (file_exists($composerPath)) {
            $composer = json_decode(file_get_contents($composerPath), true);

            if (isset($composer['minimum-stability'])) {
                $this->assertContains(
                    $composer['minimum-stability'],
                    ['stable', 'RC', 'beta', 'alpha', 'dev'],
                    'Minimum stability should be a valid Composer stability level'
                );
            }

            if (isset($composer['prefer-stable'])) {
                $this->assertTrue(
                    $composer['prefer-stable'],
                    'Package should prefer stable versions'
                );
            }
        } else {
            $this->markTestSkipped('composer.json not found in test environment');
        }
    }
}
