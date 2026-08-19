# CodeForge Database Studio

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hkdevs/codeforge-database-studio.svg?style=flat-square)](https://packagist.org/packages/hkdevs/codeforge-database-studio)
[![MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-12%20|%2013-red.svg)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-4%20|%205-yellow.svg)](https://filamentphp.com)

Open source Filament panel plugin for database management, schema design, migration tracking, health monitoring, smart seeding, documentation export, and code generation.

**Maintainer:** [Hardik Kanajariya](https://hardikkanajariya.in) · **Docs:** [GitHub Pages](https://hardikkanajariya-in.github.io/codeforge/) · **Issues:** [GitHub](https://github.com/hardikkanajariya-in/codeforge/issues)

## Features

- **Database overview** — stats, quick actions, connection health
- **Visual schema designer** — tables, relationships, ERD-style views
- **Migration management** — history, batch migrate, rollback safety
- **Health monitoring** — slow queries, metrics, performance charts
- **Smart seeding** — templates, relationship-aware bulk data
- **Documentation generator** — Markdown, HTML, PDF, JSON, snapshots
- **Code generation** — migrations, models, factories, seeders, Filament resources

## Requirements

| Dependency | Version |
|------------|---------|
| PHP | 8.3+ |
| Laravel | 12.x, 13.x |
| Filament | 4.x, 5.x |

## Installation

```bash
composer require hkdevs/codeforge-database-studio

php artisan vendor:publish --tag=codeforge-database-studio-config
php artisan vendor:publish --tag=codeforge-database-studio-migrations
php artisan migrate
php artisan codeforge:install
```

Register in your Filament panel provider:

```php
use Filament\Panel;
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            CodeForgeStudioPlugin::make()
                ->enableSchemaDesigner()
                ->enableMigrationManager()
                ->enableHealthMonitoring()
                ->enableSmartSeeding()
                ->enableDocumentationGenerator()
                ->enableCodeGeneration(),
        ]);
}
```

### Custom theme (Filament v4/v5)

Add to your panel theme CSS:

```css
@source '../../../../vendor/hkdevs/codeforge-database-studio/resources/**/*.blade.php';
```

### Local development

```json
{
    "repositories": [
        { "type": "path", "url": "../codeforge" }
    ],
    "require": {
        "hkdevs/codeforge-database-studio": "@dev"
    }
}
```

## Configuration

Published to `config/codeforge-database-studio.php`. Toggle features:

```php
CodeForgeStudioPlugin::make()
    ->enableSchemaDesigner(false)
    ->enableCodeGeneration(true);
```

## Filament plugin store

Ready for submission to the [Filament plugin directory](https://filamentphp.com/plugins). See [FILAMENT_STORE.md](FILAMENT_STORE.md) for listing metadata, banner specs, and checklist.

## Testing

```bash
composer test
```

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md). Security reports: [SECURITY.md](SECURITY.md).

## License

MIT — see [LICENSE](LICENSE). Copyright (c) Hardik Kanajariya.
