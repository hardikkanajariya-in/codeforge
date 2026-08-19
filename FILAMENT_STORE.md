# Filament Plugin Store Listing

Use this checklist when submitting at [filamentphp.com/author](https://filamentphp.com/author).

## Package metadata

| Field | Value |
|-------|-------|
| **Name** | CodeForge Database Studio |
| **Slug** | `hkdevs-codeforge-database-studio` |
| **Composer** | `hkdevs/codeforge-database-studio` |
| **Repository** | https://github.com/hardikkanajariya-in/codeforge |
| **License** | MIT (free & open source) |
| **Filament** | v4.x, v5.x |
| **Laravel** | 12.x, 13.x |
| **PHP** | 8.3+ |

## Suggested categories

Pick valid categories from the Filament plugin directory (e.g. **Panel**, **Developer tools**, **Database**).

## Short description (store)

> Open source Filament panel plugin for database overview, schema design, migration tracking, health monitoring, smart seeding, documentation export, and code generation (models, migrations, factories, seeders, Filament resources).

## Long description highlights

- Visual schema designer with relationship mapping
- Migration history and enhanced migrate/rollback tooling
- Query performance and database health dashboards
- Relationship-aware smart seeding and templates
- Schema documentation export (Markdown, HTML, PDF, JSON)
- Generators for migrations, models, factories, seeders, and Filament resources
- Feature toggles per panel via `CodeForgeStudioPlugin::make()`

## Installation snippet (for listing)

```bash
composer require hkdevs/codeforge-database-studio

php artisan vendor:publish --tag=codeforge-database-studio-config
php artisan vendor:publish --tag=codeforge-database-studio-migrations
php artisan migrate
php artisan codeforge:install
```

Register in your panel provider:

```php
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

->plugins([
    CodeForgeStudioPlugin::make(),
])
```

## Custom theme (Filament v4/v5)

If you use a custom Filament theme, add to your theme CSS:

```css
@source '../../../../vendor/hkdevs/codeforge-database-studio/resources/**/*.blade.php';
```

## Required media assets

Create and upload these before submitting:

### Plugin banner (required)

- **Aspect ratio:** 16:9
- **Minimum size:** 2560×1440 px
- **Format:** JPEG preferred
- **Content:** Highlight core features (not a full admin screenshot). Crop sidebar/header.
- **Tool:** [Beyond Code banner generator](https://banners.beyondco.de)

Save as: `resources/images/filament-store/banner.jpg`

### README screenshot (optional, for GitHub)

- Use absolute URLs in README if images should embed on filamentphp.com
- Add class `filament-hidden` to images that should not duplicate on the plugin page

### Author avatar

- **Aspect ratio:** 1:1
- **Minimum size:** 1000×1000 px
- **Format:** JPEG preferred
- Link: https://hardikkanajariya.in

### Author bio (short)

Hardik Kanajariya is an indie Laravel and Filament developer. Maintainer of CodeForge Database Studio and other open source tools for database workflows and developer productivity.

## Documentation requirements

- [x] README with installation, configuration, and usage
- [x] MIT LICENSE
- [x] CONTRIBUTING.md and SECURITY.md
- [x] Clear feature list with screenshots (add to README when available)
- [x] In-app docs at `/codeforge/docs` when routes are enabled

## Review guidelines (common rejections)

- Use **Filament** / **FilamentPHP** capitalization in docs
- Plugin image must focus on functionality, not full panel chrome
- README images must use **absolute URLs** for website embedding
- Choose **valid plugin categories** only
- Enable **“Allow edits by maintainers”** on any GitHub PRs

## Post-submission

1. Request author access at [filamentphp.com/author](https://filamentphp.com/author)
2. Submit plugin with banner, categories, and repository URL
3. Ensure package is on Packagist: `hkdevs/codeforge-database-studio`
4. Monitor GitHub Issues for community support
