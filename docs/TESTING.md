# Testing Phase 1 - Plugin Foundation

## Prerequisites
- Laravel 10+ project
- Filament 3+ installed
- PHP 8.1+

## Installation Steps

### 1. Install the Plugin
```bash
# If you're developing locally, add to composer.json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/codeforge-database-studio"
        }
    ],
    "require": {
        "HkDevs/codeforge-database-studio": "*"
    }
}

# Then run
composer update

php artisan vendor:publish --provider="HkDevs\CodeForgeStudio\FilamentDatabaseManagerServiceProvider"