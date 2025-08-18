#!/usr/bin/env php
<?php
/**
 * Manual Asset Publisher for CodeForge Database Studio
 * This script manually copies assets from package to the correct locations
 */

echo "🚀 CodeForge Database Studio - Manual Asset Publisher\n";
echo "====================================================\n\n";

// Define paths
$sourceBasePath = __DIR__;
$cssSource = $sourceBasePath . '/resources/css';
$jsSource = $sourceBasePath . '/resources/js';

// For testing in package directory
$testCssDestination = $sourceBasePath . '/public/vendor/codeforge/css';
$testJsDestination = $sourceBasePath . '/public/vendor/codeforge/js';

// Function to copy directory recursively
function copyDirectory($source, $destination)
{
    if (!is_dir($source)) {
        echo "❌ Source directory does not exist: $source\n";
        return false;
    }

    if (!is_dir($destination)) {
        if (!mkdir($destination, 0755, true)) {
            echo "❌ Failed to create destination directory: $destination\n";
            return false;
        }
        echo "✅ Created directory: $destination\n";
    }

    $files = scandir($source);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;

        $sourcePath = $source . '/' . $file;
        $destPath = $destination . '/' . $file;

        if (is_dir($sourcePath)) {
            copyDirectory($sourcePath, $destPath);
        } else {
            if (copy($sourcePath, $destPath)) {
                echo "✅ Copied: $file\n";
            } else {
                echo "❌ Failed to copy: $file\n";
            }
        }
    }

    return true;
}

// Copy CSS files
echo "📁 Publishing CSS assets...\n";
if (copyDirectory($cssSource, $testCssDestination)) {
    echo "✅ CSS assets published successfully!\n\n";
} else {
    echo "❌ Failed to publish CSS assets!\n\n";
}

// Copy JS files  
echo "📁 Publishing JS assets...\n";
if (copyDirectory($jsSource, $testJsDestination)) {
    echo "✅ JS assets published successfully!\n\n";
} else {
    echo "❌ Failed to publish JS assets!\n\n";
}

echo "🎉 Asset publishing completed!\n";
echo "Assets are now available at:\n";
echo "- CSS: $testCssDestination\n";
echo "- JS: $testJsDestination\n";
echo "\n";
echo "For Laravel applications, copy these to:\n";
echo "- public/vendor/codeforge/css/\n";
echo "- public/vendor/codeforge/js/\n";
