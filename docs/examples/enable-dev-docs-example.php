<?php

/**
 * CodeForge Database Studio - Enable Developer Documentation
 * 
 * This example shows how to enable the developer documentation button
 * in the Database Overview page header.
 */

// Method 1: Plugin Configuration (Recommended)
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

$plugin = CodeForgeStudioPlugin::make()
    ->enableSchemaDesigner(false)
    ->enableDevDocs(true)  // 🎯 Enable developer documentation
    ->enableMigrationManager(true)
    ->enableHealthMonitoring(false)
    ->enableSmartSeeding(false)
    ->enableDocumentationGenerator(false)
    ->enableCodeGeneration(false);

// Use in your panel provider:
// ->plugins([$plugin])

/**
 * Method 2: Configuration File
 * 
 * Modify config/codeforge-database-studio.php
 */
$configExample = [
    'features' => [
        'schema_designer' => true,
        'migration_manager' => true,
        'health_monitoring' => true,
        'smart_seeding' => true,
        'documentation_generator' => true,
        'code_generation' => true,
        'dev_docs' => true, // 🎯 Enable developer documentation
    ],
];

/**
 * Security Note:
 * 
 * Developer documentation is DISABLED by default for security.
 * You must explicitly enable it using one of the methods above.
 * 
 * When enabled:
 * ✅ "📚 Documentation" button appears in Database Overview header
 * ✅ Opens in new tab to preserve workflow
 * ✅ Professional blue styling
 * ✅ Mobile responsive
 * ✅ Route validation for safety
 * 
 * When disabled (default):
 * ❌ Button is completely hidden
 * ❌ No performance impact
 * ❌ Secure by default
 */
