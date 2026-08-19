<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit;

use HkDevs\CodeForgeStudio\Services\AssetService;
use HkDevs\CodeForgeStudio\Tests\TestCase;
use Illuminate\Support\Facades\File;

/**
 * Asset Service Test
 *
 * Tests the asset serving functionality with fallbacks
 */
class AssetServiceTest extends TestCase
{
    /**
     * Test asset path detection
     */
    public function test_asset_path_detection(): void
    {
        $paths = AssetService::getAssetPaths();

        $this->assertArrayHasKey('published_css', $paths);
        $this->assertArrayHasKey('published_js', $paths);
        $this->assertArrayHasKey('package_css', $paths);
        $this->assertArrayHasKey('package_js', $paths);
        $this->assertArrayHasKey('published_exists', $paths);
        $this->assertArrayHasKey('package_exists', $paths);
    }

    /**
     * Test MIME type detection
     */
    public function test_mime_type_detection(): void
    {
        $reflection = new \ReflectionClass(AssetService::class);
        $method = $reflection->getMethod('getMimeType');
        $method->setAccessible(true);

        $this->assertEquals('text/css', $method->invoke(null, 'test.css'));
        $this->assertEquals('application/javascript', $method->invoke(null, 'test.js'));
        $this->assertEquals('image/png', $method->invoke(null, 'test.png'));
        $this->assertEquals('font/woff2', $method->invoke(null, 'test.woff2'));
    }

    /**
     * Test asset URL generation
     */
    public function test_asset_url_generation(): void
    {
        $cssUrl = AssetService::asset('css/schema-designer-v2.css');
        $jsUrl = AssetService::asset('js/schema-designer-v2.js');

        $this->assertStringContainsString('css/schema-designer-v2.css', $cssUrl);
        $this->assertStringContainsString('js/schema-designer-v2.js', $jsUrl);
    }

    /**
     * Test package asset existence
     */
    public function test_package_asset_existence(): void
    {
        $packageRoot = dirname(__DIR__, 2);
        $packageCssPath = $packageRoot.'/resources/css/schema-designer-v2.css';
        $packageJsPath = $packageRoot.'/resources/js/schema-designer-v2.js';

        $this->assertTrue(File::exists($packageCssPath), 'Package CSS file should exist');
        $this->assertTrue(File::exists($packageJsPath), 'Package JS file should exist');
    }
}
