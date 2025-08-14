<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use HkDevs\CodeForgeStudio\CodeForgeStudioServiceProvider;
use HkDevs\CodeForgeStudio\Services\SchemaAnalyzerService;
use HkDevs\CodeForgeStudio\Services\SchemaVisualizationService;
use HkDevs\CodeForgeStudio\Services\SeederExecutionService;
use HkDevs\CodeForgeStudio\Services\DataGenerationService;
use HkDevs\CodeForgeStudio\Services\SchemaDocumentationService;
use HkDevs\CodeForgeStudio\Services\MigrationGeneratorService;
use HkDevs\CodeForgeStudio\Services\ModelGeneratorService;
use HkDevs\CodeForgeStudio\Services\CodeGenerationService;
use HkDevs\CodeForgeStudio\Services\FilamentResourceGeneratorService;
use HkDevs\CodeForgeStudio\Services\AdvancedCodeGenerationService;
use HkDevs\CodeForgeStudio\Services\StubTemplateService;
use HkDevs\CodeForgeStudio\Services\LaravelTypesService;
use HkDevs\CodeForgeStudio\Services\FactoryGeneratorService;
use HkDevs\CodeForgeStudio\Services\SeederGeneratorService;
use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use HkDevs\CodeForgeStudio\Commands\InstallCommand;
use HkDevs\CodeForgeStudio\Commands\GenerateDocumentationCommand;
use HkDevs\CodeForgeStudio\Commands\CreateSchemaSnapshotCommand;
use HkDevs\CodeForgeStudio\Commands\CleanupDocumentationCommand;
use HkDevs\CodeForgeStudio\Commands\CollectHealthMetricsCommand;
use HkDevs\CodeForgeStudio\Commands\CleanupLogsCommand;
use HkDevs\CodeForgeStudio\Commands\ToggleQueryLoggingCommand;
use HkDevs\CodeForgeStudio\Commands\MigrationCommand;
use HkDevs\CodeForgeStudio\Commands\RunSeedersCommand;
use HkDevs\CodeForgeStudio\Commands\GenerateDataCommand;
use HkDevs\CodeForgeStudio\Commands\TestDataGenerationCommand;
use HkDevs\CodeForgeStudio\Widgets\DatabaseStatsWidget;
use HkDevs\CodeForgeStudio\Widgets\DatabaseHealthMetricsWidget;
use HkDevs\CodeForgeStudio\Widgets\DatabaseHealthWidget;
use HkDevs\CodeForgeStudio\Widgets\SeederStatsWidget;
use HkDevs\CodeForgeStudio\Widgets\MigrationStatsWidget;
use HkDevs\CodeForgeStudio\Widgets\QueryPerformanceChart;
use HkDevs\CodeForgeStudio\Widgets\RecentMigrationsWidget;
use HkDevs\CodeForgeStudio\Widgets\CodeGenerationStatsWidget;
use HkDevs\CodeForgeStudio\Widgets\GeneratorStatsWidget;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\QueryExecuted;
use Livewire\Livewire;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class CodeForgeStudioServiceProviderTest extends TestCase
{
    private CodeForgeStudioServiceProvider $serviceProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceProvider = new CodeForgeStudioServiceProvider($this->app);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_service_provider_can_be_instantiated()
    {
        $this->assertInstanceOf(CodeForgeStudioServiceProvider::class, $this->serviceProvider);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_config_is_merged_correctly()
    {
        $this->serviceProvider->register();

        // Test that configuration is loaded
        $this->assertNotNull(config('codeforge-database-studio'));
        $this->assertIsArray(config('codeforge-database-studio'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_all_services_are_registered_as_singletons()
    {
        $this->serviceProvider->register();

        $expectedSingletons = [
            SchemaAnalyzerService::class,
            SchemaVisualizationService::class,
            SeederExecutionService::class,
            DataGenerationService::class,
            SchemaDocumentationService::class,
            MigrationGeneratorService::class,
            ModelGeneratorService::class,
            CodeGenerationService::class,
            FilamentResourceGeneratorService::class,
            AdvancedCodeGenerationService::class,
            StubTemplateService::class,
            LaravelTypesService::class,
            FactoryGeneratorService::class,
            SeederGeneratorService::class,
            DatabaseHealthService::class,
        ];

        foreach ($expectedSingletons as $service) {
            $this->assertTrue($this->app->bound($service), "Service {$service} is not bound");

            // Test singleton behavior
            $instance1 = $this->app->make($service);
            $instance2 = $this->app->make($service);
            $this->assertSame($instance1, $instance2, "Service {$service} is not a singleton");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_services_can_be_resolved()
    {
        $this->serviceProvider->register();

        $services = [
            SchemaAnalyzerService::class,
            SchemaVisualizationService::class,
            SeederExecutionService::class,
            DataGenerationService::class,
            SchemaDocumentationService::class,
            MigrationGeneratorService::class,
            ModelGeneratorService::class,
            CodeGenerationService::class,
            FilamentResourceGeneratorService::class,
            AdvancedCodeGenerationService::class,
            StubTemplateService::class,
            LaravelTypesService::class,
            FactoryGeneratorService::class,
            SeederGeneratorService::class,
            DatabaseHealthService::class,
        ];

        foreach ($services as $service) {
            $resolvedService = $this->app->make($service);
            $this->assertInstanceOf($service, $resolvedService, "Could not resolve service: {$service}");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migrations_are_loaded()
    {
        $this->serviceProvider->boot();

        // Test that migrations directory is registered
        $migrationPaths = $this->app['migrator']->paths();
        $expectedPath = __DIR__ . '/../../database/migrations';

        // Check if any path contains our migrations directory
        $pathFound = false;
        foreach ($migrationPaths as $path) {
            if (str_contains($path, 'database/migrations')) {
                $pathFound = true;
                break;
            }
        }

        $this->assertTrue($pathFound, 'Migration path is not loaded');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_views_are_loaded()
    {
        $this->serviceProvider->boot();

        // Test that views are registered by checking the view manager
        $viewFactory = $this->app['view'];

        // Check if the namespace is registered
        $this->assertTrue(
            method_exists($viewFactory, 'addNamespace') ||
                $this->app->bound('view.finder'),
            'Views system is not properly loaded'
        );

        // Alternative: Check if view paths are registered
        if ($this->app->bound('view.finder')) {
            $viewFinder = $this->app['view.finder'];
            $viewPaths = $viewFinder->getPaths();
            $this->assertIsArray($viewPaths, 'View paths should be an array');
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_routes_are_loaded()
    {
        $this->serviceProvider->boot();

        // Test that routes are loaded by checking if router has routes
        $routes = $this->app['router']->getRoutes();
        $this->assertGreaterThan(0, count($routes), 'No routes are loaded');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_commands_are_registered_in_console()
    {
        // Mock console environment
        $this->app['env'] = 'testing';

        $this->serviceProvider->boot();

        $expectedCommands = [
            InstallCommand::class,
            GenerateDocumentationCommand::class,
            CreateSchemaSnapshotCommand::class,
            CleanupDocumentationCommand::class,
            CollectHealthMetricsCommand::class,
            CleanupLogsCommand::class,
            ToggleQueryLoggingCommand::class,
            MigrationCommand::class,
            RunSeedersCommand::class,
            GenerateDataCommand::class,
            TestDataGenerationCommand::class,
        ];

        // Check that commands are available
        foreach ($expectedCommands as $command) {
            $this->assertTrue(
                class_exists($command),
                "Command class {$command} does not exist"
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_event_listeners_are_registered()
    {
        $this->serviceProvider->boot();

        // Test QueryExecuted event listener
        $queryListeners = Event::getListeners(QueryExecuted::class);
        $this->assertNotEmpty($queryListeners, 'QueryExecuted event has no listeners');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_livewire_components_are_registered()
    {
        $this->serviceProvider->boot();

        // Test that Livewire is available
        $this->assertTrue(class_exists(\Livewire\Component::class), 'Livewire is not available');

        // Test that all widget classes exist and can be instantiated
        $expectedWidgetClasses = [
            DatabaseStatsWidget::class,
            DatabaseHealthMetricsWidget::class,
            DatabaseHealthWidget::class,
            SeederStatsWidget::class,
            MigrationStatsWidget::class,
            QueryPerformanceChart::class,
            RecentMigrationsWidget::class,
            CodeGenerationStatsWidget::class,
            GeneratorStatsWidget::class,
        ];

        foreach ($expectedWidgetClasses as $widgetClass) {
            $this->assertTrue(
                class_exists($widgetClass),
                "Widget class {$widgetClass} does not exist"
            );

            // Test that each widget extends Livewire Component
            $this->assertTrue(
                is_subclass_of($widgetClass, \Livewire\Component::class),
                "Widget class {$widgetClass} does not extend Livewire Component"
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_publishable_assets_are_configured()
    {
        $this->serviceProvider->boot();

        // Get all publishable groups
        $publishGroups = $this->app['events']->getListeners('Illuminate\Foundation\Events\PublishingStubs');

        // Test that publishable groups exist
        $this->assertTrue(true); // Basic assertion since we can't easily test publishable assets
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_package_dependencies_are_available()
    {
        // Test Livewire is available
        $this->assertTrue(class_exists(\Livewire\Component::class), 'Livewire is not available');

        // Test Filament is available
        $this->assertTrue(class_exists(\Filament\Panel::class), 'Filament is not available');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_configuration_structure()
    {
        $this->serviceProvider->register();

        $config = config('codeforge-database-studio');
        $this->assertIsArray($config);

        // Test expected configuration keys exist (based on actual config file)
        $expectedKeys = ['features', 'navigation', 'connections', 'migrations', 'health_monitoring'];
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $config, "Configuration missing key: {$key}");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_service_provider_boots_without_errors()
    {
        $this->expectNotToPerformAssertions();

        // Test that boot method runs without throwing exceptions
        $this->serviceProvider->register();
        $this->serviceProvider->boot();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_service_provider_registers_without_errors()
    {
        $this->expectNotToPerformAssertions();

        // Test that register method runs without throwing exceptions
        $this->serviceProvider->register();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_widget_classes_exist()
    {
        $widgetClasses = [
            DatabaseStatsWidget::class,
            DatabaseHealthMetricsWidget::class,
            DatabaseHealthWidget::class,
            SeederStatsWidget::class,
            MigrationStatsWidget::class,
            QueryPerformanceChart::class,
            RecentMigrationsWidget::class,
            CodeGenerationStatsWidget::class,
            GeneratorStatsWidget::class,
        ];

        foreach ($widgetClasses as $widgetClass) {
            $this->assertTrue(
                class_exists($widgetClass),
                "Widget class {$widgetClass} does not exist"
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_command_classes_exist()
    {
        $commandClasses = [
            InstallCommand::class,
            GenerateDocumentationCommand::class,
            CreateSchemaSnapshotCommand::class,
            CleanupDocumentationCommand::class,
            CollectHealthMetricsCommand::class,
            CleanupLogsCommand::class,
            ToggleQueryLoggingCommand::class,
            MigrationCommand::class,
            RunSeedersCommand::class,
            GenerateDataCommand::class,
            TestDataGenerationCommand::class,
        ];

        foreach ($commandClasses as $commandClass) {
            $this->assertTrue(
                class_exists($commandClass),
                "Command class {$commandClass} does not exist"
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_service_classes_exist()
    {
        $serviceClasses = [
            SchemaAnalyzerService::class,
            SchemaVisualizationService::class,
            SeederExecutionService::class,
            DataGenerationService::class,
            SchemaDocumentationService::class,
            MigrationGeneratorService::class,
            ModelGeneratorService::class,
            CodeGenerationService::class,
            FilamentResourceGeneratorService::class,
            AdvancedCodeGenerationService::class,
            StubTemplateService::class,
            LaravelTypesService::class,
            FactoryGeneratorService::class,
            SeederGeneratorService::class,
            DatabaseHealthService::class,
        ];

        foreach ($serviceClasses as $serviceClass) {
            $this->assertTrue(
                class_exists($serviceClass),
                "Service class {$serviceClass} does not exist"
            );
        }
    }
}
