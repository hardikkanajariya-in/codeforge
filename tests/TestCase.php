<?php

namespace HkDevs\CodeForgeStudio\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as BaseTestCase;
use HkDevs\CodeForgeStudio\CodeForgeStudioServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up plugin configuration for testing
        config(['codeforge-database-studio.features.documentation_generator' => true]);
        config(['codeforge-database-studio.features.schema_designer' => true]);
        config(['codeforge-database-studio.features.migration_manager' => true]);
        config(['codeforge-database-studio.features.health_monitoring' => true]);
        config(['codeforge-database-studio.features.smart_seeding' => true]);
    }

    /**
     * Get package providers.
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Livewire\LivewireServiceProvider::class,
            CodeForgeStudioServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     */
    protected function defineEnvironment($app): void
    {
        // Set up database configuration for testing
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]);
        
        // Disable testbench default migrations
        $app['config']->set('database.migrations', 'migrations');
    }


    /**
     * Helper to assert view contains expected data keys
     */
    protected function assertViewHasKeys($response, array $keys): void
    {
        $viewData = $response->getOriginalContent()->getData();

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $viewData, "View data is missing key: {$key}");
        }
    }

    /**
     * Helper to assert view contains expected title
     */
    protected function assertViewHasTitle($response, string $expectedTitle): void
    {
        $viewData = $response->getOriginalContent()->getData();

        $this->assertArrayHasKey('title', $viewData);
        $this->assertEquals($expectedTitle, $viewData['title']);
    }

    /**
     * Helper to assert response contains specific text
     */
    protected function assertResponseContains($response, string $text): void
    {
        $content = $response->getContent();
        $this->assertStringContainsString($text, $content, "Response does not contain: {$text}");
    }

    /**
     * Helper to assert JSON response structure
     */
    protected function assertJsonStructure($response, array $structure): void
    {
        $response->assertJsonStructure($structure);
    }
}
