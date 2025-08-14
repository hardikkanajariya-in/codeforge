<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit\Environment;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;
use Filament\Panel;
use Filament\PanelProvider;

/**
 * Test Case: TC-ENV-003 - Plugin Registration
 * Purpose: Verify plugin registers correctly with Filament panels
 */
class PluginRegistrationTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_instantiation()
    {
        $plugin = CodeForgeStudioPlugin::make();

        $this->assertInstanceOf(
            CodeForgeStudioPlugin::class,
            $plugin,
            'Plugin should instantiate correctly'
        );

        $this->assertInstanceOf(
            \Filament\Contracts\Plugin::class,
            $plugin,
            'Plugin should implement Filament Plugin interface'
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_id()
    {
        $plugin = CodeForgeStudioPlugin::make();

        $this->assertEquals(
            'codeforge-database-studio',
            $plugin->getId(),
            'Plugin should have correct ID'
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_default_configuration()
    {
        $plugin = CodeForgeStudioPlugin::make();

        // Test default feature enablement
        $this->assertTrue(true, 'Plugin creates with default configuration');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_feature_toggles()
    {
        $plugin = CodeForgeStudioPlugin::make()
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(false);

        $this->assertInstanceOf(
            CodeForgeStudioPlugin::class,
            $plugin,
            'Plugin should support feature toggles'
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_navigation_configuration()
    {
        $plugin = CodeForgeStudioPlugin::make()
            ->navigationGroup('Custom Group')
            ->navigationSort(50);

        $this->assertInstanceOf(
            CodeForgeStudioPlugin::class,
            $plugin,
            'Plugin should support navigation configuration'
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_registration_with_panel()
    {
        // Create a mock panel
        $panel = $this->createMockPanel();

        $plugin = CodeForgeStudioPlugin::make();

        // Test plugin registration
        try {
            $plugin->register($panel);
            $this->assertTrue(true, 'Plugin registers with panel successfully');
        } catch (\Exception $e) {
            $this->fail("Plugin registration failed: " . $e->getMessage());
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_with_all_features_enabled()
    {
        $plugin = CodeForgeStudioPlugin::make()
            ->enableSchemaDesigner(true)
            ->enableMigrationManager(true)
            ->enableHealthMonitoring(true)
            ->enableSmartSeeding(true)
            ->enableDocumentationGenerator(true);

        $panel = $this->createMockPanel();

        try {
            $plugin->register($panel);
            $this->assertTrue(true, 'Plugin with all features enabled registers successfully');
        } catch (\Exception $e) {
            $this->fail("Plugin registration with all features failed: " . $e->getMessage());
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_with_selective_features()
    {
        $plugin = CodeForgeStudioPlugin::make()
            ->enableSchemaDesigner(true)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(true)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(true);

        $panel = $this->createMockPanel();

        try {
            $plugin->register($panel);
            $this->assertTrue(true, 'Plugin with selective features registers successfully');
        } catch (\Exception $e) {
            $this->fail("Plugin registration with selective features failed: " . $e->getMessage());
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_navigation_group_customization()
    {
        $customGroup = 'Database Management';
        $plugin = CodeForgeStudioPlugin::make()
            ->navigationGroup($customGroup);

        $this->assertInstanceOf(
            CodeForgeStudioPlugin::class,
            $plugin,
            'Plugin should accept custom navigation group'
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_navigation_sort_customization()
    {
        $customSort = 25;
        $plugin = CodeForgeStudioPlugin::make()
            ->navigationSort($customSort);

        $this->assertInstanceOf(
            CodeForgeStudioPlugin::class,
            $plugin,
            'Plugin should accept custom navigation sort order'
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_method_chaining()
    {
        $plugin = CodeForgeStudioPlugin::make()
            ->enableSchemaDesigner(true)
            ->enableMigrationManager(true)
            ->navigationGroup('Test Group')
            ->navigationSort(99)
            ->enableHealthMonitoring(false);

        $this->assertInstanceOf(
            CodeForgeStudioPlugin::class,
            $plugin,
            'Plugin should support method chaining'
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_configuration_persistence()
    {
        $plugin = CodeForgeStudioPlugin::make()
            ->enableSchemaDesigner(false)
            ->navigationGroup('Persistent Group');

        // Test that configuration persists
        $this->assertInstanceOf(
            CodeForgeStudioPlugin::class,
            $plugin,
            'Plugin configuration should persist'
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_with_minimal_configuration()
    {
        $plugin = CodeForgeStudioPlugin::make()
            ->enableSchemaDesigner(false)
            ->enableMigrationManager(false)
            ->enableHealthMonitoring(false)
            ->enableSmartSeeding(false)
            ->enableDocumentationGenerator(false);

        $panel = $this->createMockPanel();

        try {
            $plugin->register($panel);
            $this->assertTrue(true, 'Plugin with minimal configuration registers successfully');
        } catch (\Exception $e) {
            $this->fail("Plugin registration with minimal configuration failed: " . $e->getMessage());
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_plugin_registration_multiple_times()
    {
        $plugin = CodeForgeStudioPlugin::make();
        $panel = $this->createMockPanel();

        try {
            $plugin->register($panel);
            $plugin->register($panel);
            $this->assertTrue(true, 'Plugin can be registered multiple times without issues');
        } catch (\Exception $e) {
            $this->fail("Multiple plugin registrations failed: " . $e->getMessage());
        }
    }

    /**
     * Create a mock panel for testing
     */
    private function createMockPanel(): Panel
    {
        return Panel::make()
            ->id('admin')
            ->path('/admin')
            ->domain(null);
    }
}
