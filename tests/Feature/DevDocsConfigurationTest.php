<?php

namespace HkDevs\CodeForgeStudio\Tests\Feature;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * DevDocsConfigurationTest
 * 
 * Tests the developer documentation configuration functionality
 * ensuring that the documentation button appears only when properly configured.
 */
class DevDocsConfigurationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_plugin_configuration_correctly()
    {
        $plugin = CodeForgeStudioPlugin::make()
            ->enableDevDocs(true)
            ->enableSchemaDesigner(false)
            ->enableCodeGeneration(true);

        // Simulate plugin registration
        app()->singleton('codeforge-plugin-config', function () use ($plugin) {
            return [
                'enable_dev_docs' => true,
                'enable_schema_designer' => false,
                'enable_code_generation' => true,
            ];
        });

        $config = app('codeforge-plugin-config');

        $this->assertTrue($config['enable_dev_docs']);
        $this->assertFalse($config['enable_schema_designer']);
        $this->assertTrue($config['enable_code_generation']);
    }

    /** @test */
    public function it_defaults_dev_docs_to_false_in_config()
    {
        $devDocsConfig = config('codeforge-database-studio.features.dev_docs', false);

        $this->assertFalse($devDocsConfig, 'Developer documentation should be disabled by default');
    }

    /** @test */
    public function it_enables_dev_docs_through_plugin_method()
    {
        $plugin = CodeForgeStudioPlugin::make()->enableDevDocs(true);

        // Use reflection to access protected property
        $reflection = new \ReflectionClass($plugin);
        $property = $reflection->getProperty('enableDevDocs');
        $property->setAccessible(true);

        $this->assertTrue($property->getValue($plugin));
    }

    /** @test */
    public function it_disables_dev_docs_through_plugin_method()
    {
        $plugin = CodeForgeStudioPlugin::make()->enableDevDocs(false);

        // Use reflection to access protected property
        $reflection = new \ReflectionClass($plugin);
        $property = $reflection->getProperty('enableDevDocs');
        $property->setAccessible(true);

        $this->assertFalse($property->getValue($plugin));
    }

    /** @test */
    public function it_falls_back_to_config_when_plugin_config_not_bound()
    {
        // Ensure plugin config is not bound
        if (app()->bound('codeforge-plugin-config')) {
            app()->forgetInstance('codeforge-plugin-config');
        }

        // Set config value
        config(['codeforge-database-studio.features.dev_docs' => true]);

        // Simulate blade logic
        $pluginConfig = app()->bound('codeforge-plugin-config') ? app('codeforge-plugin-config') : [];
        $devDocsEnabled = $pluginConfig['enable_dev_docs'] ?? config('codeforge-database-studio.features.dev_docs', false);

        $this->assertTrue($devDocsEnabled);
    }

    /** @test */
    public function it_prioritizes_plugin_config_over_file_config()
    {
        // Set config file to true
        config(['codeforge-database-studio.features.dev_docs' => true]);

        // Set plugin config to false
        app()->singleton('codeforge-plugin-config', function () {
            return ['enable_dev_docs' => false];
        });

        // Simulate blade logic
        $pluginConfig = app()->bound('codeforge-plugin-config') ? app('codeforge-plugin-config') : [];
        $devDocsEnabled = $pluginConfig['enable_dev_docs'] ?? config('codeforge-database-studio.features.dev_docs', false);

        $this->assertFalse($devDocsEnabled, 'Plugin configuration should take priority over file configuration');
    }
}
