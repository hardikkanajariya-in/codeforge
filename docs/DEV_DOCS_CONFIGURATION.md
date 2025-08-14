# Developer Documentation Configuration

## Overview

The CodeForge Database Studio plugin now supports configuration-based developer documentation access. The documentation button will only appear in the Database Overview page when explicitly enabled through plugin configuration.

## Configuration Methods

### Method 1: Plugin Configuration (Recommended)

```php
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

->plugins([
    CodeForgeStudioPlugin::make()
        ->enableSchemaDesigner(false)
        ->enableDevDocs(true)  // Enable developer documentation
        ->enableMigrationManager(true)
        ->enableHealthMonitoring(false)
        ->enableSmartSeeding(false)
        ->enableDocumentationGenerator(false)
        ->enableCodeGeneration(false)
])
```

### Method 2: Configuration File

Add or modify the configuration in `config/codeforge-database-studio.php`:

```php
'features' => [
    'schema_designer' => true,
    'migration_manager' => true,
    'health_monitoring' => true,
    'smart_seeding' => true,
    'documentation_generator' => true,
    'code_generation' => true,
    'dev_docs' => true, // Enable developer documentation access
],
```

## Features

### Security
- Documentation access is **disabled by default**
- Must be explicitly enabled by developers
- Only shows when routes are properly registered
- Graceful error handling for missing routes

### User Experience
- Clean integration in the Database Overview header
- Opens in new tab to preserve workflow
- Professional styling with hover effects
- Responsive design for mobile devices

### Technical Implementation
- Configuration-driven visibility
- Route existence validation
- Plugin configuration binding
- Fallback to config file values

## Usage

1. **Enable the feature** using one of the configuration methods above
2. **Ensure documentation routes** are properly loaded
3. **Visit the Database Overview page** to see the documentation button
4. **Click the button** to open the documentation in a new tab

## Button Behavior

- **Visible**: When `enable_dev_docs` is true AND routes exist
- **Hidden**: When `enable_dev_docs` is false OR routes don't exist
- **Style**: Blue gradient background with hover effects
- **Icon**: 📚 (Books emoji) for visual identification

## Implementation Details

### Plugin Configuration Storage
The plugin stores configuration in the Laravel app container as `codeforge-plugin-config`:

```php
app()->singleton('codeforge-plugin-config', function () {
    return [
        'enable_dev_docs' => $this->enableDevDocs,
        // ... other configurations
    ];
});
```

### Blade View Integration
The documentation button is implemented directly in the Blade view with configuration checking:

```php
@php
    $pluginConfig = app()->bound('codeforge-plugin-config') ? app('codeforge-plugin-config') : [];
    $devDocsEnabled = $pluginConfig['enable_dev_docs'] ?? config('codeforge-database-studio.features.dev_docs', false);
@endphp

@if($devDocsEnabled && $docsRouteExists)
    <a href="{{ route('codeforge.docs.home') }}" target="_blank" class="action-button docs-button">
        📚 Documentation
    </a>
@endif
```

## Migration Notes

- Existing installations will have `dev_docs` set to `false` by default
- No breaking changes to existing functionality
- Backward compatible with existing plugin configurations
- Documentation button removed from PHP header actions (moved to Blade view)

## Support

For issues or questions regarding developer documentation configuration:
- Email: hardikkanajariya@yahoo.com
- Documentation: https://hardikkanajariya.in/codeforge-database-studio
