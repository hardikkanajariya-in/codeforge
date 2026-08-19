# Filament plugin directory notes

Reference for maintainers when updating the listing on [filamentphp.com/plugins](https://filamentphp.com/plugins).

## Package metadata

| Field | Value |
|-------|-------|
| **Name** | CodeForge Database Studio |
| **Slug** | `hkdevs-codeforge-database-studio` |
| **Composer** | `hkdevs/codeforge-database-studio` |
| **Repository** | https://github.com/hardikkanajariya-in/codeforge |
| **License** | MIT |
| **Filament** | v4.x, v5.x |
| **Laravel** | 12.x, 13.x |
| **PHP** | 8.3+ |

## Categories

Examples: **Panel**, **Developer tools**, **Database** (use values from the directory form).

## Short description

> Open source Filament panel plugin for database overview, visual schema design, migration tracking, health monitoring, smart seeding, documentation export (Markdown, HTML, PDF), and code generation for models, migrations, factories, seeders, and Filament resources.

## Feature summary

- Visual schema designer with relationship mapping
- Migration history and migrate/rollback tooling
- Query performance and database health views
- Relationship-aware seeding and templates
- Schema documentation export (Markdown, HTML, PDF)
- Generators for migrations, models, factories, seeders, and Filament resources
- Feature toggles per panel via `CodeForgeStudioPlugin::make()`

## Installation snippet

```bash
composer require hkdevs/codeforge-database-studio

php artisan vendor:publish --tag=codeforge-database-studio-config
php artisan vendor:publish --tag=codeforge-database-studio-migrations
php artisan migrate
php artisan codeforge:install
```

```php
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

->plugins([
    CodeForgeStudioPlugin::make(),
])
```

## Custom theme (Filament v4/v5)

```css
@source '../../../../vendor/hkdevs/codeforge-database-studio/resources/**/*.blade.php';
```

## Banner image

- **Aspect ratio:** 16:9
- **Minimum size:** 2560×1440 px
- **Format:** JPEG
- **Path:** `resources/images/filament-store/banner.jpg`
- **Tool:** [Beyond Code banner generator](https://banners.beyondco.de)

Focus on product functionality rather than a full admin panel screenshot.

## Author profile

- **Avatar:** 1:1, minimum 1000×1000 px, JPEG
- **Link:** https://hardikkanajariya.in
- **Bio:** Hardik Kanajariya maintains CodeForge Database Studio and other open source Laravel/Filament tools.

## Directory guidelines

- Use **Filament** / **FilamentPHP** capitalization in copy
- README images on the plugin page need **absolute URLs**
- Package must be on Packagist with auto-update from GitHub tags
- Community support via [GitHub Issues](https://github.com/hardikkanajariya-in/codeforge/issues)
