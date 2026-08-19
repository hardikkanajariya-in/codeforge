# CodeForge Database Studio

[![MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-10+-red.svg)](https://laravel.com)
[![FilamentPHP](https://img.shields.io/badge/FilamentPHP-3.x-yellow.svg)](https://filamentphp.com)

A free, open source database management and code generation suite for Laravel applications using FilamentPHP.

**Maintainer:** [Hardik Kanajariya](https://hardikkanajariya.in) · **Website:** [hardikkanajariya-in.github.io/codeforge](https://hardikkanajariya-in.github.io/codeforge/) · **Issues:** [GitHub Issues](https://github.com/hardikkanajariya-in/codeforge/issues)

## Features

- **Database overview & analytics** — live stats, performance dashboards, connection health
- **Migration management** — history tracking, enhanced migrate commands, safe rollbacks
- **Health monitoring** — slow query detection, metrics collection, performance alerts
- **Visual schema designer** — interactive schema exploration and relationship mapping
- **Smart data seeding** — relationship-aware test data with templates and bulk operations
- **Documentation generator** — Markdown, HTML, PDF, and JSON exports with schema snapshots
- **Code generation** — migrations, models, factories, seeders, and Filament resources

## Requirements

- PHP 8.1+
- Laravel 10.x+
- FilamentPHP 3.x
- MySQL 5.7+, PostgreSQL 11+, SQLite 3.8+, or SQL Server 2017+

## Installation

```bash
composer require hkdevs/codeforge-database-studio

php artisan vendor:publish --tag="codeforge-database-studio-config"
php artisan vendor:publish --tag="codeforge-database-studio-migrations"
php artisan migrate
```

Register the plugin in your Filament panel provider:

```php
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            CodeForgeStudioPlugin::make(),
        ]);
}
```

Run the install command (optional):

```bash
php artisan codeforge:install
php artisan config:clear && php artisan cache:clear
```

### Local development

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../codeforge"
        }
    ],
    "require": {
        "hkdevs/codeforge-database-studio": "@dev"
    }
}
```

## Configuration

Published to `config/codeforge-database-studio.php`. Enable or disable features:

```php
CodeForgeStudioPlugin::make()
    ->enableSchemaDesigner()
    ->enableMigrationManager()
    ->enableHealthMonitoring()
    ->enableSmartSeeding()
    ->enableDocumentationGenerator()
    ->enableCodeGeneration();
```

## Documentation

- **In-app docs:** available at `/codeforge/docs` when routes are registered
- **GitHub README:** this file
- **Project website:** [GitHub Pages](https://hardikkanajariya-in.github.io/codeforge/)

## Contributing

Contributions are welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) before opening a pull request.

## Security

Report security vulnerabilities privately — see [SECURITY.md](SECURITY.md).

## License

MIT License. See [LICENSE](LICENSE).

Copyright (c) 2025 Hardik Kanajariya

## Credits

Built with [FilamentPHP](https://filamentphp.com) and [Laravel](https://laravel.com).
