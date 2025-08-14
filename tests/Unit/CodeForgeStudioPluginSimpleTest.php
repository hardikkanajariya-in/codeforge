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
use Filament\Contracts\Plugin;

class CodeForgeStudioPluginSimpleTest extends TestCase
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

        $enableCodeGeneration = $reflection->getProperty('enableCodeGeneration');
        $enableCodeGeneration->setAccessible(true);
        $this->assertTrue($enableCodeGeneration->getValue($this->plugin));
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
    public function test_enable_code_generation_method()
    {
        $result = $this->plugin->enableCodeGeneration(false);
        $this->assertSame($this->plugin, $result); // Test fluent interface

        $reflection = new \ReflectionClass($this->plugin);
        $property = $reflection->getProperty('enableCodeGeneration');
        $property->setAccessible(true);
        $this->assertFalse($property->getValue($this->plugin));
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
    public function test_feature_state_changes_are_persistent()
    {
        // Test that feature state changes persist
        $this->plugin
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(true)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(true);

        $reflection = new \ReflectionClass($this->plugin);

        $enableSchemaDesigner = $reflection->getProperty('enableSchemaDesigner');
        $enableSchemaDesigner->setAccessible(true);
        $this->assertFalse($enableSchemaDesigner->getValue($this->plugin));

        $enableMigrationManager = $reflection->getProperty('enableMigrationManager');
        $enableMigrationManager->setAccessible(true);
        $this->assertFalse($enableMigrationManager->getValue($this->plugin));

        $enableHealthMonitoring = $reflection->getProperty('enableHealthMonitoring');
        $enableHealthMonitoring->setAccessible(true);
        $this->assertTrue($enableHealthMonitoring->getValue($this->plugin));

        $enableSmartSeeding = $reflection->getProperty('enableSmartSeeding');
        $enableSmartSeeding->setAccessible(true);
        $this->assertFalse($enableSmartSeeding->getValue($this->plugin));

        $enableDocumentationGenerator = $reflection->getProperty('enableDocumentationGenerator');
        $enableDocumentationGenerator->setAccessible(true);
        $this->assertTrue($enableDocumentationGenerator->getValue($this->plugin));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_configuration_is_chainable()
    {
        $plugin = CodeForgeStudioPlugin::make()
            ->enableSchemaDesigner(true)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(true)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(true)
            ->enableCodeGeneration(false);

        $this->assertInstanceOf(CodeForgeStudioPlugin::class, $plugin);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_all_feature_toggles_work_correctly()
    {
        $reflection = new \ReflectionClass($this->plugin);

        // Test turning all features off
        $this->plugin
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(false);

        $properties = [
            'enableSchemaDesigner',
            'enableMigrationManager',
            'enableHealthMonitoring',
            'enableSmartSeeding',
            'enableDocumentationGenerator'
        ];

        foreach ($properties as $propertyName) {
            $property = $reflection->getProperty($propertyName);
            $property->setAccessible(true);
            $this->assertFalse($property->getValue($this->plugin), "Property {$propertyName} should be false");
        }

        // Test turning all features back on
        $this->plugin
            ->enableSchemaDesigner(true)
            ->enableMigrationManager(true)
            ->enableHealthMonitoring(true)
            ->enableSmartSeeding(true)
            ->enableDocumentationGenerator(true);

        foreach ($properties as $propertyName) {
            $property = $reflection->getProperty($propertyName);
            $property->setAccessible(true);
            $this->assertTrue($property->getValue($this->plugin), "Property {$propertyName} should be true");
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_has_all_required_public_methods()
    {
        $requiredMethods = [
            'getId',
            'register',
            'boot',
            'enableSchemaDesigner',
            'enableMigrationManager',
            'enableHealthMonitoring',
            'enableSmartSeeding',
            'enableDocumentationGenerator',
            'enableCodeGeneration',
            'make'
        ];

        $reflection = new \ReflectionClass($this->plugin);

        foreach ($requiredMethods as $method) {
            $this->assertTrue(
                $reflection->hasMethod($method),
                "Plugin is missing required method: {$method}"
            );
        }
    }
}
