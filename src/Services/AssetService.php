<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/**
 * Asset Service for CodeForge Studio
 * 
 * Handles asset serving with fallback to package directory
 * if assets are not published to public directory.
 * 
 * @package HkDevs\CodeForgeStudio\Services
 * @author hardikkanajariya.in
 */
class AssetService
{
    /**
     * Get the asset URL with fallback to package route
     */
    public static function asset(string $path): string
    {
        $publicPath = public_path("vendor/codeforge/{$path}");

        // If asset exists in published location, use it
        if (File::exists($publicPath)) {
            return asset("vendor/codeforge/{$path}");
        }

        // Fallback to package route
        return route('codeforge.asset', ['path' => $path]);
    }

    /**
     * Serve asset from package directory
     */
    public static function serveAsset(string $path)
    {
        $packagePath = __DIR__ . "/../../resources/{$path}";

        if (!File::exists($packagePath)) {
            abort(404, 'Asset not found');
        }

        $mimeType = self::getMimeType($path);
        $content = File::get($packagePath);

        return Response::make($content, 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000', // 1 year cache
            'Expires' => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        ]);
    }

    /**
     * Get MIME type for asset
     */
    protected static function getMimeType(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            default => 'application/octet-stream',
        };
    }

    /**
     * Check if assets are published
     */
    public static function areAssetsPublished(): bool
    {
        return File::exists(public_path('vendor/codeforge/css/schema-designer-v2.css')) &&
            File::exists(public_path('vendor/codeforge/js/schema-designer-v2.js'));
    }

    /**
     * Get asset paths for debugging
     */
    public static function getAssetPaths(): array
    {
        return [
            'published_css' => public_path('vendor/codeforge/css/schema-designer-v2.css'),
            'published_js' => public_path('vendor/codeforge/js/schema-designer-v2.js'),
            'package_css' => __DIR__ . '/../../resources/css/schema-designer-v2.css',
            'package_js' => __DIR__ . '/../../resources/js/schema-designer-v2.js',
            'published_exists' => self::areAssetsPublished(),
            'package_exists' => File::exists(__DIR__ . '/../../resources/css/schema-designer-v2.css') &&
                File::exists(__DIR__ . '/../../resources/js/schema-designer-v2.js'),
        ];
    }
}
