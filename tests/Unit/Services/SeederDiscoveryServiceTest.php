<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit\Services;

use HkDevs\CodeForgeStudio\Services\SeederDiscoveryService;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Support\Facades\File;

/**
 * SeederDiscoveryServiceTest
 * 
 * Unit tests for the SeederDiscoveryService class.
 * Tests seeder discovery, validation, and metadata extraction functionality.
 * 
 * @package HkDevs\CodeForgeStudio\Tests\Unit\Services
 */
class SeederDiscoveryServiceTest extends TestCase
{
    protected SeederDiscoveryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SeederDiscoveryService();
    }

    /** @test */
    public function it_can_discover_seeders()
    {
        $seeders = $this->service->discoverSeeders();

        $this->assertIsArray($seeders);
        $this->assertNotEmpty($seeders);

        // Check structure of first seeder
        if (!empty($seeders)) {
            $seeder = $seeders[0];
            $this->assertArrayHasKey('name', $seeder);
            $this->assertArrayHasKey('class_name', $seeder);
            $this->assertArrayHasKey('file_path', $seeder);
            $this->assertArrayHasKey('namespace', $seeder);
            $this->assertArrayHasKey('is_valid', $seeder);
            $this->assertArrayHasKey('relative_path', $seeder);
        }
    }

    /** @test */
    public function it_can_get_seeder_options_for_filament()
    {
        $options = $this->service->getSeederOptions();

        $this->assertIsArray($options);

        // Check that options are properly formatted for Filament select
        foreach ($options as $key => $value) {
            $this->assertIsString($key);   // class name
            $this->assertIsString($value); // display label
        }
    }

    /** @test */
    public function it_can_get_seeder_file_path_by_class_name()
    {
        $seeders = $this->service->discoverSeeders();

        if (!empty($seeders)) {
            $seeder = $seeders[0];
            $className = $seeder['class_name'];
            $expectedPath = $seeder['file_path'];

            $filePath = $this->service->getSeederFilePath($className);

            $this->assertEquals($expectedPath, $filePath);
        }
    }

    /** @test */
    public function it_returns_null_for_non_existent_seeder_class()
    {
        $filePath = $this->service->getSeederFilePath('NonExistentSeeder');

        $this->assertNull($filePath);
    }

    /** @test */
    public function it_validates_seeder_file_patterns()
    {
        $seederContent = '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run()
    {
        //
    }
}';

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('isSeederFile');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, $seederContent);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_extracts_class_name_correctly()
    {
        $content = '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        //
    }
}';

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('extractClassName');
        $method->setAccessible(true);

        $className = $method->invoke($this->service, $content);

        $this->assertEquals('UserSeeder', $className);
    }

    /** @test */
    public function it_extracts_namespace_correctly()
    {
        $content = '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        //
    }
}';

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('extractNamespace');
        $method->setAccessible(true);

        $namespace = $method->invoke($this->service, $content);

        $this->assertEquals('Database\Seeders', $namespace);
    }

    /** @test */
    public function it_handles_files_without_namespace()
    {
        $content = '<?php
use Illuminate\Database\Seeder;

class SimpleSeeder extends Seeder
{
    public function run()
    {
        //
    }
}';

        $reflection = new \ReflectionClass($this->service);
        $namespaceMethod = $reflection->getMethod('extractNamespace');
        $namespaceMethod->setAccessible(true);

        $namespace = $namespaceMethod->invoke($this->service, $content);

        $this->assertNull($namespace);
    }

    /** @test */
    public function it_deduplicates_seeders_correctly()
    {
        $seeders = [
            ['class_name' => 'UserSeeder', 'name' => 'UserSeeder'],
            ['class_name' => 'PostSeeder', 'name' => 'PostSeeder'],
            ['class_name' => 'UserSeeder', 'name' => 'UserSeeder'], // Duplicate
        ];

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('deduplicateAndSort');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, $seeders);

        $this->assertCount(2, $result);
        $this->assertEquals('PostSeeder', $result[0]['name']); // Should be sorted
        $this->assertEquals('UserSeeder', $result[1]['name']);
    }
}
