<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;
use HkDevs\CodeForgeStudio\Pages\DatabaseOverview;
use HkDevs\CodeForgeStudio\Pages\DatabaseHealthDashboard;
use HkDevs\CodeForgeStudio\Pages\SchemaDesigner;
use HkDevs\CodeForgeStudio\Pages\SmartDataSeeder;
use HkDevs\CodeForgeStudio\Pages\DocumentationGenerator;
use HkDevs\CodeForgeStudio\Pages\GeneratorOverviewPage;
use HkDevs\CodeForgeStudio\Pages\MigrationGeneratorPage;
use HkDevs\CodeForgeStudio\Pages\ModelGeneratorPage;
use HkDevs\CodeForgeStudio\Pages\FactoryGeneratorPage;
use HkDevs\CodeForgeStudio\Pages\SeederGeneratorPage;
use HkDevs\CodeForgeStudio\Pages\FilamentResourceGeneratorPage;
use HkDevs\CodeForgeStudio\Resources\MigrationHistoryResource;
use HkDevs\CodeForgeStudio\Resources\QueryPerformanceLogResource;
use HkDevs\CodeForgeStudio\Resources\DatabaseHealthMetricResource;
use HkDevs\CodeForgeStudio\Resources\DataSeederResource;
use HkDevs\CodeForgeStudio\Resources\SeederExecutionLogResource;
use HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource;
use HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource;
use HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource;
use Filament\Panel;
use Filament\Contracts\Plugin;

class CodeForgeStudioPluginTest extends TestCase
{
    private CodeForgeStudioPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->plugin = new CodeForgeStudioPlugin();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_implements_plugin_interface()
    {
        $this->assertInstanceOf(Plugin::class, $this->plugin);
        $this->assertInstanceOf(CodeForgeStudioPlugin::class, $this->plugin);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_has_correct_id()
    {
        $this->assertEquals('codeforge-database-studio', $this->plugin->getId());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_can_be_created_via_make_method()
    {
        $plugin = CodeForgeStudioPlugin::make();
        $this->assertInstanceOf(CodeForgeStudioPlugin::class, $plugin);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_default_feature_states()
    {
        // Test default values via reflection since properties are protected
        $reflection = new \ReflectionClass($this->plugin);

        $enableSchemaDesigner = $reflection->getProperty('enableSchemaDesigner');
        $enableSchemaDesigner->setAccessible(true);
        $this->assertTrue($enableSchemaDesigner->getValue($this->plugin));

        $enableMigrationManager = $reflection->getProperty('enableMigrationManager');
        $enableMigrationManager->setAccessible(true);
        $this->assertTrue($enableMigrationManager->getValue($this->plugin));

        $enableHealthMonitoring = $reflection->getProperty('enableHealthMonitoring');
        $enableHealthMonitoring->setAccessible(true);
        $this->assertTrue($enableHealthMonitoring->getValue($this->plugin));

        $enableSmartSeeding = $reflection->getProperty('enableSmartSeeding');
        $enableSmartSeeding->setAccessible(true);
        $this->assertTrue($enableSmartSeeding->getValue($this->plugin));

        $enableDocumentationGenerator = $reflection->getProperty('enableDocumentationGenerator');
        $enableDocumentationGenerator->setAccessible(true);
        $this->assertTrue($enableDocumentationGenerator->getValue($this->plugin));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_enable_schema_designer_method()
    {
        $result = $this->plugin->enableSchemaDesigner(false);
        $this->assertSame($this->plugin, $result); // Test fluent interface

        // Test that the setting is applied via reflection
        $reflection = new \ReflectionClass($this->plugin);
        $property = $reflection->getProperty('enableSchemaDesigner');
        $property->setAccessible(true);
        $this->assertFalse($property->getValue($this->plugin));

        // Test enabling again
        $this->plugin->enableSchemaDesigner(true);
        $this->assertTrue($property->getValue($this->plugin));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_enable_migration_manager_method()
    {
        $result = $this->plugin->enableMigrationManager(false);
        $this->assertSame($this->plugin, $result); // Test fluent interface

        $reflection = new \ReflectionClass($this->plugin);
        $property = $reflection->getProperty('enableMigrationManager');
        $property->setAccessible(true);
        $this->assertFalse($property->getValue($this->plugin));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_enable_health_monitoring_method()
    {
        $result = $this->plugin->enableHealthMonitoring(false);
        $this->assertSame($this->plugin, $result); // Test fluent interface

        $reflection = new \ReflectionClass($this->plugin);
        $property = $reflection->getProperty('enableHealthMonitoring');
        $property->setAccessible(true);
        $this->assertFalse($property->getValue($this->plugin));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_enable_smart_seeding_method()
    {
        $result = $this->plugin->enableSmartSeeding(false);
        $this->assertSame($this->plugin, $result); // Test fluent interface

        $reflection = new \ReflectionClass($this->plugin);
        $property = $reflection->getProperty('enableSmartSeeding');
        $property->setAccessible(true);
        $this->assertFalse($property->getValue($this->plugin));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_enable_documentation_generator_method()
    {
        $result = $this->plugin->enableDocumentationGenerator(false);
        $this->assertSame($this->plugin, $result); // Test fluent interface

        $reflection = new \ReflectionClass($this->plugin);
        $property = $reflection->getProperty('enableDocumentationGenerator');
        $property->setAccessible(true);
        $this->assertFalse($property->getValue($this->plugin));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_register_method_with_all_features_enabled()
    {
        // Create a mock panel
        /** @var Panel $panel */
        $panel = $this->createMock(Panel::class);

        // Capture the pages and resources registered
        $registeredPages = [];
        $registeredResources = [];

        $panel->expects($this->once())
            ->method('pages')
            ->willReturnCallback(function ($pages) use (&$registeredPages, $panel) {
                $registeredPages = $pages;
                return $panel;
            });

        $panel->expects($this->once())
            ->method('resources')
            ->willReturnCallback(function ($resources) use (&$registeredResources, $panel) {
                $registeredResources = $resources;
                return $panel;
            });

        // Register the plugin
        $this->plugin->register($panel);

        // Verify all expected pages are registered
        $expectedPages = [
            DatabaseOverview::class,
            SchemaDesigner::class,
            GeneratorOverviewPage::class,
            MigrationGeneratorPage::class,
            ModelGeneratorPage::class,
            FactoryGeneratorPage::class,
            SeederGeneratorPage::class,
            FilamentResourceGeneratorPage::class,
            DatabaseHealthDashboard::class,
            SmartDataSeeder::class,
            DocumentationGenerator::class,
        ];

        foreach ($expectedPages as $expectedPage) {
            $this->assertContains($expectedPage, $registeredPages, "Page {$expectedPage} was not registered");
        }

        // Verify all expected resources are registered
        $expectedResources = [
            MigrationHistoryResource::class,
            QueryPerformanceLogResource::class,
            DatabaseHealthMetricResource::class,
            DataSeederResource::class,
            SeederExecutionLogResource::class,
            DataGenerationTemplateResource::class,
            DocumentationGenerationResource::class,
            SchemaSnapshotResource::class,
        ];

        foreach ($expectedResources as $expectedResource) {
            $this->assertContains($expectedResource, $registeredResources, "Resource {$expectedResource} was not registered");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_register_method_with_all_features_disabled()
    {
        $this->plugin
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(false)
            ->enableCodeGeneration(false);

        /** @var Panel $panel */
        $panel = $this->createMock(Panel::class);

        $registeredPages = [];
        $registeredResources = [];

        $panel->expects($this->once())
            ->method('pages')
            ->willReturnCallback(function ($pages) use (&$registeredPages, $panel) {
                $registeredPages = $pages;
                return $panel;
            });

        $panel->expects($this->once())
            ->method('resources')
            ->willReturnCallback(function ($resources) use (&$registeredResources, $panel) {
                $registeredResources = $resources;
                return $panel;
            });

        $this->plugin->register($panel);

        // Only DatabaseOverview should be registered
        $this->assertEquals([DatabaseOverview::class], $registeredPages);
        $this->assertEquals([], $registeredResources);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_register_method_with_only_schema_designer_enabled()
    {
        $this->plugin
            ->enableSchemaDesigner(true)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(false)
            ->enableCodeGeneration(false);

        $panel = $this->createMock(Panel::class);

        $registeredPages = [];
        $registeredResources = [];

        $panel->expects($this->once())
            ->method('pages')
            ->willReturnCallback(function ($pages) use (&$registeredPages, $panel) {
                $registeredPages = $pages;
                return $panel;
            });

        $panel->expects($this->once())
            ->method('resources')
            ->willReturnCallback(function ($resources) use (&$registeredResources, $panel) {
                $registeredResources = $resources;
                return $panel;
            });

        $this->plugin->register($panel);

        $expectedPages = [
            DatabaseOverview::class,
            SchemaDesigner::class,
        ];

        $this->assertEquals($expectedPages, $registeredPages);
        $this->assertEquals([], $registeredResources);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_boot_method_exists_and_can_be_called()
    {
        $panel = $this->createMock(Panel::class);
        $this->expectNotToPerformAssertions();

        // Test that boot method exists and can be called without errors
        $this->plugin->boot($panel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_fluent_interface_chaining()
    {
        $result = $this->plugin
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(true)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(true)
            ->enableDocumentationGenerator(false)
            ->enableCodeGeneration(true);

        $this->assertSame($this->plugin, $result);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_page_classes_exist()
    {
        $pageClasses = [
            DatabaseOverview::class,
            DatabaseHealthDashboard::class,
            SchemaDesigner::class,
            SmartDataSeeder::class,
            DocumentationGenerator::class,
            GeneratorOverviewPage::class,
            MigrationGeneratorPage::class,
            ModelGeneratorPage::class,
            FactoryGeneratorPage::class,
            SeederGeneratorPage::class,
            FilamentResourceGeneratorPage::class,
        ];

        foreach ($pageClasses as $pageClass) {
            $this->assertTrue(
                class_exists($pageClass),
                "Page class {$pageClass} does not exist"
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_resource_classes_exist()
    {
        $resourceClasses = [
            MigrationHistoryResource::class,
            QueryPerformanceLogResource::class,
            DatabaseHealthMetricResource::class,
            DataSeederResource::class,
            SeederExecutionLogResource::class,
            DataGenerationTemplateResource::class,
            DocumentationGenerationResource::class,
            SchemaSnapshotResource::class,
        ];

        foreach ($resourceClasses as $resourceClass) {
            $this->assertTrue(
                class_exists($resourceClass),
                "Resource class {$resourceClass} does not exist"
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_always_includes_database_overview_page()
    {
        // Test with all features disabled
        $this->plugin
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(false);

        $panel = $this->createMock(Panel::class);

        $registeredPages = [];

        $panel->expects($this->once())
            ->method('pages')
            ->willReturnCallback(function ($pages) use (&$registeredPages, $panel) {
                $registeredPages = $pages;
                return $panel;
            });

        $panel->expects($this->once())
            ->method('resources')
            ->willReturn($panel);

        $this->plugin->register($panel);

        $this->assertContains(DatabaseOverview::class, $registeredPages, 'DatabaseOverview page should always be included');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_migration_manager_controls_generator_pages()
    {
        // Generator pages should only be included when migration manager is enabled
        $this->plugin
            ->enableMigrationManager(true)
            ->enableSchemaDesigner(false)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(false);

        $panel = $this->createMock(Panel::class);

        $registeredPages = [];

        $panel->expects($this->once())
            ->method('pages')
            ->willReturnCallback(function ($pages) use (&$registeredPages, $panel) {
                $registeredPages = $pages;
                return $panel;
            });

        $panel->expects($this->once())
            ->method('resources')
            ->willReturn($panel);

        $this->plugin->register($panel);

        $generatorPages = [
            GeneratorOverviewPage::class,
            MigrationGeneratorPage::class,
            ModelGeneratorPage::class,
            FactoryGeneratorPage::class,
            SeederGeneratorPage::class,
            FilamentResourceGeneratorPage::class,
        ];

        foreach ($generatorPages as $generatorPage) {
            $this->assertContains($generatorPage, $registeredPages, "Generator page {$generatorPage} should be included when migration manager is enabled");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_health_monitoring_controls_related_pages_and_resources()
    {
        $this->plugin
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(true)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(false);

        $panel = $this->createMock(Panel::class);

        $registeredPages = [];
        $registeredResources = [];

        $panel->expects($this->once())
            ->method('pages')
            ->willReturnCallback(function ($pages) use (&$registeredPages, $panel) {
                $registeredPages = $pages;
                return $panel;
            });

        $panel->expects($this->once())
            ->method('resources')
            ->willReturnCallback(function ($resources) use (&$registeredResources, $panel) {
                $registeredResources = $resources;
                return $panel;
            });

        $this->plugin->register($panel);

        $this->assertContains(DatabaseHealthDashboard::class, $registeredPages);
        $this->assertContains(QueryPerformanceLogResource::class, $registeredResources);
        $this->assertContains(DatabaseHealthMetricResource::class, $registeredResources);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_smart_seeding_controls_related_pages_and_resources()
    {
        $this->plugin
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(true)
            ->enableDocumentationGenerator(false);

        $panel = $this->createMock(Panel::class);

        $registeredPages = [];
        $registeredResources = [];

        $panel->expects($this->once())
            ->method('pages')
            ->willReturnCallback(function ($pages) use (&$registeredPages, $panel) {
                $registeredPages = $pages;
                return $panel;
            });

        $panel->expects($this->once())
            ->method('resources')
            ->willReturnCallback(function ($resources) use (&$registeredResources, $panel) {
                $registeredResources = $resources;
                return $panel;
            });

        $this->plugin->register($panel);

        $this->assertContains(SmartDataSeeder::class, $registeredPages);
        $this->assertContains(DataSeederResource::class, $registeredResources);
        $this->assertContains(SeederExecutionLogResource::class, $registeredResources);
        $this->assertContains(DataGenerationTemplateResource::class, $registeredResources);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_documentation_generator_controls_related_pages_and_resources()
    {
        $this->plugin
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(true);

        $panel = $this->createMock(Panel::class);

        $registeredPages = [];
        $registeredResources = [];

        $panel->expects($this->once())
            ->method('pages')
            ->willReturnCallback(function ($pages) use (&$registeredPages, $panel) {
                $registeredPages = $pages;
                return $panel;
            });

        $panel->expects($this->once())
            ->method('resources')
            ->willReturnCallback(function ($resources) use (&$registeredResources, $panel) {
                $registeredResources = $resources;
                return $panel;
            });

        $this->plugin->register($panel);

        $this->assertContains(DocumentationGenerator::class, $registeredPages);
        $this->assertContains(DocumentationGenerationResource::class, $registeredResources);
        $this->assertContains(SchemaSnapshotResource::class, $registeredResources);
    }
}
