# CodeForge Studio Asset Management

This document explains the asset management system in CodeForge Studio that provides seamless fallback functionality for CSS and JavaScript files.

## Overview

CodeForge Studio implements a smart asset management system that:

1. **First tries to load from published assets** in `public/vendor/codeforge/`
2. **Falls back to package directory** via route if assets aren't published
3. **Works out-of-the-box** without requiring asset publishing
4. **Supports asset publishing** for production optimization

## How It Works

### Asset Service (`AssetService`)

The `AssetService` class provides the core functionality:

```php
// Get asset URL with automatic fallback
$cssUrl = AssetService::asset('css/schema-designer-v2.css');
$jsUrl = AssetService::asset('js/schema-designer-v2.js');

// Check if assets are published
$published = AssetService::areAssetsPublished();

// Get debug information
$paths = AssetService::getAssetPaths();
```

### Automatic Fallback Logic

1. **Check Published Location**: First checks if the asset exists in `public/vendor/codeforge/`
2. **Use Published Asset**: If found, returns standard `asset()` URL
3. **Use Package Route**: If not found, returns route to serve from package directory
4. **Proper Headers**: Sets appropriate MIME types and cache headers

### Route-Based Asset Serving

When assets aren't published, they're served via the route:

```
GET /codeforge/assets/{path}
```

This route:
- Serves files directly from the package `resources/` directory
- Sets proper MIME types based on file extension
- Includes cache headers for performance
- Returns 404 for non-existent files

## Usage in Blade Templates

### Schema Designer Page

The Schema Designer page uses helper methods for asset URLs:

```blade
@push('styles')
    <link rel="stylesheet" href="{{ $this->getSchemaDesignerCssUrl() }}">
@endpush

@push('scripts')
    <script src="{{ $this->getSchemaDesignerJsUrl() }}"></script>
@endpush
```

### Helper Methods

The page class provides convenient helper methods:

```php
// Get any asset URL
$url = $this->getAssetUrl('css/custom-styles.css');

// Get specific asset URLs
$cssUrl = $this->getSchemaDesignerCssUrl();
$jsUrl = $this->getSchemaDesignerJsUrl();
```

## Asset Publishing

### Optional Publishing

Assets can be published for production optimization:

```bash
# Publish all CodeForge Studio assets
php artisan vendor:publish --tag=codeforge-studio-assets

# Publish specific assets only
php artisan vendor:publish --tag=codeforge-studio-assets --force
```

### Published Asset Structure

When published, assets are copied to:

```
public/vendor/codeforge/
├── css/
│   └── schema-designer-v2.css
└── js/
    └── schema-designer-v2.js
```

## Debugging

### Asset Debug Command

Use the debug command to check asset status:

```bash
php artisan codeforge:asset-debug
```

This command shows:
- Asset file locations
- Existence status for each location
- Current asset URLs being used
- Recommendations for optimization

### Example Output

```
🔍 CodeForge Studio Asset Debug Information

📂 Asset Locations:
+---------------+--------------------------------------------------+--------+
| Type          | Path                                             | Exists |
+---------------+--------------------------------------------------+--------+
| Published CSS | /path/to/public/vendor/codeforge/css/schema...  | ❌ No  |
| Published JS  | /path/to/public/vendor/codeforge/js/schema...   | ❌ No  |
| Package CSS   | /path/to/vendor/hkdevs/resources/css/schema...  | ✅ Yes |
| Package JS    | /path/to/vendor/hkdevs/resources/js/schema...   | ✅ Yes |
+---------------+--------------------------------------------------+--------+

📊 Status:
✅ Published Assets: Not Available
✅ Package Assets: Available

🌐 Asset URLs:
CSS: http://localhost/codeforge/assets/css/schema-designer-v2.css
JS: http://localhost/codeforge/assets/js/schema-designer-v2.js

⚠️  Assets are not published but available in package directory.
💡 Assets will be served directly from package via route.
🚀 To publish assets, run: php artisan vendor:publish --tag=codeforge-studio-assets
```

## Performance Considerations

### Development vs Production

- **Development**: Package-served assets work great and don't require publishing
- **Production**: Published assets are recommended for better performance
- **CDN**: Published assets can be served via CDN for optimal performance

### Caching

- Package-served assets include cache headers (1 year expiration)
- Published assets can use web server caching configurations
- Both approaches support browser caching

### HTTP/2 Benefits

Published assets benefit from:
- HTTP/2 server push capabilities
- Better compression at web server level
- Reduced PHP overhead

## File Structure

```
package/codeforge/
├── src/
│   ├── Services/
│   │   └── AssetService.php          # Core asset management
│   ├── Pages/
│   │   └── SchemaDesigner.php        # Page with asset helpers
│   └── Commands/
│       └── AssetDebugCommand.php     # Debug command
├── resources/
│   ├── css/
│   │   └── schema-designer-v2.css    # Source CSS file
│   ├── js/
│   │   └── schema-designer-v2.js     # Source JS file
│   └── views/
│       └── pages/
│           └── schema-designer.blade.php  # Template using assets
└── routes/
    └── web.php                       # Asset serving route
```

## Benefits

1. **Zero Configuration**: Works immediately after package installation
2. **Development Friendly**: No need to publish assets during development
3. **Production Ready**: Can optimize with asset publishing when needed
4. **Backward Compatible**: Existing published assets continue to work
5. **Automatic Fallback**: Gracefully handles missing published assets
6. **Cache Optimized**: Proper cache headers for both serving methods

## Troubleshooting

### Common Issues

1. **Assets not loading**: Run `php artisan codeforge:asset-debug`
2. **404 errors**: Check if route is registered in `web.php`
3. **Wrong MIME type**: Verify file extension in `AssetService::getMimeType()`
4. **Cache issues**: Clear browser cache or check cache headers

### Support

For issues related to asset management, use the debug command first and check the console output for detailed information about asset locations and status.
