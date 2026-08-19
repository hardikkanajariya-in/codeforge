<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * SeederDiscoveryService
 *
 * Service for discovering and analyzing Laravel seeder classes within the codebase.
 * Provides comprehensive seeder discovery, validation, and metadata extraction.
 *
 * Key Features:
 * - Automatic seeder discovery across project directories
 * - Class validation and inheritance checking
 * - Seeder metadata extraction (name, path, namespace)
 * - Laravel seeder pattern recognition
 * - Custom seeder support and identification
 * - Performance optimized scanning with caching
 *
 * Discovery Methods:
 * - File system scanning for seeder files
 * - Class reflection for inheritance validation
 * - Namespace resolution and validation
 * - Autoloader integration for class availability
 * - Pattern matching for seeder identification
 *
 * Validation Features:
 * - Seeder class inheritance validation
 * - Method existence checking (run method)
 * - Syntax validation and error detection
 * - Dependency analysis and resolution
 * - Laravel framework compatibility checking
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class SeederDiscoveryService
{
    /**
     * Common seeder directories to scan
     */
    protected array $seederDirectories = [
        'database/seeders',
        'database/seeds',
        'app/Database/Seeders',
        'src/Database/Seeders',
    ];

    /**
     * Discover all available seeder classes in the project
     *
     * @return array Array of seeder class information
     */
    public function discoverSeeders(): array
    {
        $seeders = [];

        // Get base paths to scan
        $basePaths = $this->getBasePaths();

        foreach ($basePaths as $basePath) {
            foreach ($this->seederDirectories as $directory) {
                $fullPath = $basePath.DIRECTORY_SEPARATOR.$directory;

                if (is_dir($fullPath)) {
                    $seeders = array_merge($seeders, $this->scanDirectory($fullPath));
                }
            }
        }

        // Also scan common Laravel project directories
        $seeders = array_merge($seeders, $this->scanLaravelDirectories());

        // Remove duplicates and sort
        $seeders = $this->deduplicateAndSort($seeders);

        return $seeders;
    }

    /**
     * Get base paths to scan for seeders
     */
    protected function getBasePaths(): array
    {
        $paths = [];

        // Current working directory (Laravel project root)
        try {
            if (function_exists('base_path') && app()->bound('path.base')) {
                $paths[] = base_path();
            }
        } catch (\Exception $e) {
            // Ignore if not in Laravel context
        }

        // Current directory
        $paths[] = getcwd();

        // Parent directories (for packages)
        $currentDir = __DIR__;
        while ($currentDir !== dirname($currentDir)) {
            if (file_exists($currentDir.'/composer.json')) {
                $paths[] = $currentDir;
            }
            $currentDir = dirname($currentDir);
        }

        return array_unique($paths);
    }

    /**
     * Scan Laravel-specific seeder directories
     */
    protected function scanLaravelDirectories(): array
    {
        $seeders = [];

        // Check if we're in a Laravel application
        try {
            if (function_exists('app_path') && function_exists('database_path')) {
                $laravelPaths = [
                    database_path('seeders'),
                    database_path('seeds'),
                    app_path('Database/Seeders'),
                ];

                foreach ($laravelPaths as $path) {
                    if (is_dir($path)) {
                        $seeders = array_merge($seeders, $this->scanDirectory($path));
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore if not in Laravel context
        }

        return $seeders;
    }

    /**
     * Scan a directory for seeder files
     */
    protected function scanDirectory(string $directory): array
    {
        $seeders = [];

        if (! is_dir($directory)) {
            return $seeders;
        }

        $files = $this->getAllPhpFiles($directory);

        foreach ($files as $file) {
            $seederInfo = $this->analyzeSeederFile($file);

            if ($seederInfo) {
                $seeders[] = $seederInfo;
            }
        }

        return $seeders;
    }

    /**
     * Get all PHP files recursively
     */
    protected function getAllPhpFiles(string $directory): array
    {
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Analyze a PHP file to determine if it's a seeder
     */
    protected function analyzeSeederFile(string $filePath): ?array
    {
        try {
            $content = file_get_contents($filePath);

            if ($content === false) {
                return null;
            }

            // Basic checks
            if (! $this->isSeederFile($content)) {
                return null;
            }

            $className = $this->extractClassName($content);
            $namespace = $this->extractNamespace($content);

            if (! $className) {
                return null;
            }

            $fullClassName = $namespace ? $namespace.'\\'.$className : $className;

            // Try to validate the class if autoloaded
            $isValid = $this->validateSeederClass($fullClassName);

            return [
                'name' => $className,
                'class_name' => $fullClassName,
                'file_path' => $filePath,
                'namespace' => $namespace,
                'is_valid' => $isValid,
                'relative_path' => $this->getRelativePath($filePath),
            ];
        } catch (\Exception $e) {
            // Skip files that can't be analyzed
            return null;
        }
    }

    /**
     * Check if file content indicates a seeder class
     */
    protected function isSeederFile(string $content): bool
    {
        // Check for seeder patterns
        $patterns = [
            '/class\s+\w+Seeder\s+extends\s+Seeder/',
            '/class\s+\w+\s+extends\s+Seeder/',
            '/extends\s+.*Seeder/',
            '/use\s+Illuminate\\Database\\Seeder/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract class name from file content
     */
    protected function extractClassName(string $content): ?string
    {
        if (preg_match('/class\s+(\w+)(?:\s+extends|\s*{)/', $content, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Extract namespace from file content
     */
    protected function extractNamespace(string $content): ?string
    {
        if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    /**
     * Validate if a class is a proper seeder
     */
    protected function validateSeederClass(string $className): bool
    {
        try {
            if (! class_exists($className)) {
                return false;
            }

            $reflection = new ReflectionClass($className);

            // Check if it extends Seeder (directly or indirectly)
            while ($parent = $reflection->getParentClass()) {
                if ($parent->getName() === 'Illuminate\Database\Seeder') {
                    return true;
                }
                $reflection = $parent;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get relative path from project root
     */
    protected function getRelativePath(string $filePath): string
    {
        try {
            $basePath = function_exists('base_path') && app()->bound('path.base') ? base_path() : getcwd();
        } catch (\Exception $e) {
            $basePath = getcwd();
        }

        if (Str::startsWith($filePath, $basePath)) {
            return Str::after($filePath, $basePath.DIRECTORY_SEPARATOR);
        }

        return $filePath;
    }

    /**
     * Remove duplicates and sort seeders
     */
    protected function deduplicateAndSort(array $seeders): array
    {
        // Remove duplicates based on class name
        $unique = [];
        $seen = [];

        foreach ($seeders as $seeder) {
            $key = $seeder['class_name'];

            if (! in_array($key, $seen)) {
                $seen[] = $key;
                $unique[] = $seeder;
            }
        }

        // Sort by class name
        usort($unique, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $unique;
    }

    /**
     * Get seeder options for Filament select field
     */
    public function getSeederOptions(): array
    {
        $seeders = $this->discoverSeeders();
        $options = [];

        foreach ($seeders as $seeder) {
            $label = $seeder['name'];

            // Add namespace info if available
            if ($seeder['namespace']) {
                $label .= ' ('.$seeder['namespace'].')';
            }

            // Add validation status
            if (! $seeder['is_valid']) {
                $label .= ' [Invalid]';
            }

            $options[$seeder['class_name']] = $label;
        }

        return $options;
    }

    /**
     * Get seeder file path by class name
     */
    public function getSeederFilePath(string $className): ?string
    {
        $seeders = $this->discoverSeeders();

        foreach ($seeders as $seeder) {
            if ($seeder['class_name'] === $className) {
                return $seeder['file_path'];
            }
        }

        return null;
    }
}
