# CodeForge Database Studio - User Documentation

## 📖 Table of Contents

1. [Installation Guide](#installation-guide)
2. [Getting Started](#getting-started)
3. [Feature Overview](#feature-overview)
4. [Database Overview](#database-overview)
5. [Migration Manager](#migration-manager)
6. [Schema Designer](#schema-designer)
7. [Health Monitoring](#health-monitoring)
8. [Smart Data Seeding](#smart-data-seeding)
9. [Documentation Generator](#documentation-generator)
10. [Code Generation Suite](#code-generation-suite)
11. [Configuration](#configuration)
12. [Troubleshooting](#troubleshooting)
13. [Support](#support)

## 🚀 Installation Guide

### Prerequisites

Before installing CodeForge Database Studio, ensure your system meets these requirements:

- PHP 8.1 or higher
- Laravel 10.x or higher
- FilamentPHP 3.x
- MySQL 5.7+, PostgreSQL 11+, SQLite 3.8+, or SQL Server 2017+
- Composer 2.0+

⚠️ **Important**: Manual installation is currently required as the package is not yet available via Composer/Packagist.

### Step 1: Download Plugin Files

Download the plugin files from your purchase source or obtain them from the development repository.

### Step 2: Manual Installation

Choose one of the following installation methods:

**Method A: Direct Copy Installation**

```bash
# Navigate to your Laravel project root
cd /path/to/your/laravel/project

# Create vendor directory structure
mkdir -p vendor/hkdevs

# Copy the plugin files to the vendor directory
cp -r /path/to/codeforge-database-studio vendor/hkdevs/

# Windows PowerShell equivalent:
# New-Item -ItemType Directory -Force -Path "vendor\hkdevs"
# Copy-Item -Path "C:\path\to\codeforge-database-studio" -Destination "vendor\hkdevs\" -Recurse
```

**Method B: Local Composer Path (Recommended)**

1. Place the plugin files in a `packages` directory in your project root:
```bash
mkdir packages
cp -r /path/to/codeforge-database-studio packages/
```

2. Add the local repository to your `composer.json`:
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/codeforge-database-studio"
        }
    ],
    "require": {
        "hkdevs/codeforge-database-studio": "@dev"
    }
}
```

3. Install via Composer:
```bash
composer install
```

### Step 3: Publish Configuration and Run Migrations

```bash
# Publish the configuration file
php artisan vendor:publish --tag="codeforge-database-studio-config" --force

# Publish and run migrations
php artisan vendor:publish --tag="codeforge-database-studio-migrations" --force
php artisan migrate

# Optional: Publish assets if needed
php artisan vendor:publish --tag="codeforge-database-studio-assets" --force
```

### Step 4: Register the Plugin

Add the plugin to your Filament panel provider:

```php
// app/Providers/Filament/AdminPanelProvider.php
use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->plugins([
            CodeForgeStudioPlugin::make()
        ]);
}
```

### Step 5: Clear Cache and Verify

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Step 6: Verify Installation

1. Navigate to your Filament admin panel
2. Check that the following navigation groups appear:
   - **Database Overview** - Main dashboard and statistics
   - **Database Tools** - Migration manager and schema designer  
   - **Database Management** - Health monitoring and seeding tools
   - **Database Docs** - Documentation generator

### Troubleshooting Installation

If you encounter issues during installation:

1. **Plugin not appearing**: Ensure the plugin is properly registered and cache is cleared
2. **Migration errors**: Check database permissions and connection settings
3. **File permissions**: Ensure proper file permissions for the vendor directory
4. **Autoloader issues**: Run `composer dump-autoload` to refresh the autoloader

### Future Composer Installation

Once the package is published to Packagist, installation will be simplified to:

```bash
composer require hkdevs/codeforge-database-studio
php artisan codeforge-database-studio:install
```

We'll update this documentation when Composer installation becomes available.

## 🎯 Getting Started

### First Login

After installation, navigate to your Filament admin panel. You'll see new navigation groups:

- **Database Overview** - Main dashboard and statistics
- **Database Tools** - Migration manager and schema designer
- **Database Management** - Health monitoring and seeding tools
- **Database Docs** - Documentation generator

### Initial Configuration

1. **Database Connections**: Verify your database connections in the overview dashboard
2. **Health Monitoring**: Enable monitoring for performance tracking
3. **Permissions**: Configure user permissions for different features
4. **Backup Settings**: Set up automated backup schedules

## 🔍 Feature Overview

### Navigation Structure

CodeForge Database Studio organizes its features into logical groups:

#### Database Overview
- **Dashboard**: Real-time statistics and quick actions
- **Connection Status**: Monitor database health across environments
- **Performance Metrics**: Visual charts and analytics

#### Database Tools
- **Migration Manager**: Control and track database migrations
- **Schema Designer**: Visual database schema builder
- **Query Builder**: Advanced query construction tools

#### Database Management
- **Health Monitoring**: Performance tracking and alerts
- **Data Seeding**: Intelligent test data generation
- **Backup Manager**: Automated backup and restore

#### Database Documentation
- **Schema Documentation**: Automated documentation generation
- **API Documentation**: Database API reference
- **Code Examples**: Generated code samples

## 📊 Database Overview

### Dashboard Features

The main dashboard provides:

- **Live Statistics**: Table counts, row counts, database size
- **Performance Metrics**: Query performance, connection status
- **Quick Actions**: Direct links to common operations
- **Recent Activity**: Latest migrations, queries, and changes

### Connection Monitoring

- **Multi-Environment Support**: Monitor development, staging, production
- **Real-Time Status**: Live connection health indicators
- **Performance Tracking**: Response times and query performance
- **Alert System**: Notifications for connection issues

### Statistics Widgets

- **Table Information**: Detailed table statistics and metadata
- **Storage Usage**: Database size tracking and optimization suggestions
- **Performance Trends**: Historical performance data
- **User Activity**: Track database usage patterns

## 🔄 Migration Manager

### Migration History

- **Complete Timeline**: View all executed migrations with timestamps
- **Rollback Capabilities**: Safe rollback with data preservation
- **Batch Information**: Track migration batches and dependencies
- **Status Indicators**: Visual status for each migration

### Enhanced Migration Commands

```bash
# Enhanced migration with safety checks
php artisan db-manager:migrate --analyze

# Rollback with confirmation
php artisan db-manager:rollback --batch=5 --confirm

# Migration status with detailed information
php artisan db-manager:status --verbose
```

### Migration Analysis

- **Impact Assessment**: Analyze migration effects before execution
- **Dependency Mapping**: Visual representation of migration dependencies
- **Risk Evaluation**: Identify potentially destructive operations
- **Performance Impact**: Estimate execution time and resource usage

### Safety Features

- **Backup Before Migration**: Automatic backup creation
- **Confirmation Dialogs**: User confirmation for destructive operations
- **Rollback Points**: Create restore points before major changes
- **Error Recovery**: Automatic recovery from failed migrations

## 🎨 Schema Designer

### Visual Schema Builder

- **Drag-and-Drop Interface**: Build database schemas visually
- **Table Designer**: Create and modify tables with visual tools
- **Relationship Mapping**: Visual foreign key and relationship definition
- **Real-Time Validation**: Instant feedback on schema conflicts

### Relationship Management

- **Foreign Key Visualization**: See relationships between tables
- **Constraint Management**: Define and manage database constraints
- **Index Optimization**: Visual index management and suggestions
- **Normalization Tools**: Database normalization assistance

### Export Capabilities

- **Migration Generation**: Create Laravel migrations from visual designs
- **SQL Export**: Generate SQL scripts for different database systems
- **Documentation Export**: Create schema documentation
- **ERD Generation**: Export entity relationship diagrams

### Schema Analysis

- **Optimization Suggestions**: Recommendations for schema improvements
- **Performance Analysis**: Identify potential performance bottlenecks
- **Compliance Checking**: Verify schema follows best practices
- **Version Comparison**: Compare schema versions and changes

## 💖 Health Monitoring

### Performance Tracking

- **Real-Time Monitoring**: Live performance metrics and statistics
- **Query Analysis**: Track slow queries and optimization opportunities
- **Resource Usage**: Monitor CPU, memory, and disk usage
- **Connection Pooling**: Track database connection efficiency

### Metrics Collection

```bash
# Manual metrics collection
php artisan database-manager:collect-metrics

# Automated collection (via cron)
* * * * * php artisan database-manager:collect-metrics
```

### Alert System

- **Performance Thresholds**: Configure custom performance alerts
- **Email Notifications**: Automated email alerts for issues
- **Dashboard Alerts**: Visual alerts in the admin interface
- **Historical Tracking**: Maintain performance history for analysis

### Optimization Suggestions

- **Query Optimization**: Recommendations for slow queries
- **Index Suggestions**: Automated index creation recommendations
- **Schema Optimization**: Database structure improvement suggestions
- **Configuration Tuning**: Database configuration optimization

## 🌱 Smart Data Seeding

### Intelligent Data Generation

- **Context-Aware Generation**: Smart data based on field types and names
- **Relationship Handling**: Automatic foreign key relationship management
- **Realistic Data**: Generate believable test data using Faker
- **Custom Patterns**: Define custom data generation patterns

### Seeding Templates

- **Reusable Templates**: Save and reuse seeding configurations
- **Template Library**: Pre-built templates for common scenarios
- **Custom Factories**: Integration with Laravel model factories
- **Data Validation**: Ensure generated data meets constraints

### Bulk Operations

- **Large Dataset Generation**: Efficient generation of thousands of records
- **Memory Optimization**: Chunked processing for large datasets
- **Progress Tracking**: Real-time progress indicators
- **Performance Monitoring**: Track seeding performance

### Seeder Management

- **Execution History**: Track when and how seeders were run
- **Rollback Capabilities**: Remove seeded data when needed
- **Environment-Specific**: Different seeding strategies per environment
- **Data Dependencies**: Manage seeding order based on relationships

## 📚 Documentation Generator

### Automated Documentation

- **Schema Documentation**: Complete database schema documentation
- **Table Descriptions**: Detailed table and column descriptions
- **Relationship Documentation**: Visual and textual relationship descriptions
- **Constraint Documentation**: Document all database constraints

### Multiple Export Formats

- **Markdown**: Clean, readable documentation format
- **HTML**: Web-ready documentation with navigation
- **PDF**: Professional PDF reports for sharing
- **JSON**: Machine-readable schema definitions

### ERD Generation

- **Visual Diagrams**: Create entity relationship diagrams
- **Multiple Formats**: SVG, PNG, PDF diagram exports
- **Customizable Layouts**: Adjust diagram appearance and layout
- **Interactive Diagrams**: Clickable web-based diagrams

### Documentation Features

- **Version Control**: Track documentation changes over time
- **Template Customization**: Customize documentation templates
- **Automated Updates**: Keep documentation in sync with schema changes
- **Search Functionality**: Full-text search within documentation

## ⚡ Code Generation Suite

### Migration Generation

- **Smart Migration Creation**: Generate migrations from schema changes
- **Rollback Migration**: Automatic rollback migration generation
- **Foreign Key Handling**: Proper foreign key constraint management
- **Index Generation**: Automatic index creation for optimization

### Model Generation

- **Eloquent Models**: Generate complete Laravel Eloquent models
- **Relationship Definition**: Automatic relationship method generation
- **Attribute Casting**: Smart attribute casting based on field types
- **Model Documentation**: PHPDoc comments for all generated methods

### Factory Generation

- **Model Factories**: Create Laravel model factories with realistic data
- **Relationship Factories**: Handle complex relationship scenarios
- **Custom Generators**: Define custom data generation logic
- **State Management**: Create factory states for different scenarios

### Filament Resource Generation

- **Complete Resources**: Generate full Filament resources with CRUD operations
- **Form Builder**: Automatic form field generation based on model attributes
- **Table Builder**: Smart table column configuration
- **Filter Generation**: Automatic filter creation for search functionality

### Code Templates

- **Customizable Stubs**: Modify code generation templates
- **Coding Standards**: Follow PSR-12 and Laravel conventions
- **Best Practices**: Generate code following Laravel best practices
- **Custom Namespaces**: Support for custom namespace structures

## ⚙️ Configuration

### Plugin Configuration

```php
// config/codeforge-database-studio.php

return [
    // Enable/disable features
    'features' => [
        'migration_manager' => true,
        'schema_designer' => true,
        'health_monitoring' => true,
        'data_seeding' => true,
        'documentation' => true,
        'code_generation' => true,
    ],

    // Health monitoring settings
    'monitoring' => [
        'enabled' => true,
        'collect_interval' => 60, // seconds
        'retention_days' => 30,
        'slow_query_threshold' => 1000, // milliseconds
    ],

    // Code generation settings
    'code_generation' => [
        'namespace' => 'App',
        'model_path' => 'Models',
        'migration_path' => 'database/migrations',
        'factory_path' => 'database/factories',
    ],

    // Documentation settings
    'documentation' => [
        'default_format' => 'markdown',
        'include_indexes' => true,
        'include_foreign_keys' => true,
        'include_constraints' => true,
    ],
];
```

### Database Configuration

Ensure your database configuration supports the plugin's features:

```php
// config/database.php

'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
        // Enable query logging for health monitoring
        'options' => [
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],
],
```

### Permission Configuration

Configure user permissions for different features:

```php
// In your User model or authorization logic

public function canAccessCodeForgeStudio(): bool
{
    return $this->hasRole('admin') || $this->hasPermission('access-database-tools');
}

public function canManageMigrations(): bool
{
    return $this->hasRole('developer') || $this->hasPermission('manage-migrations');
}
```

## 🔧 Troubleshooting

### Common Issues

#### Plugin Not Appearing in Navigation

**Problem**: Plugin features don't appear in the Filament navigation.

**Solution**:
1. Ensure the plugin is properly registered in your panel provider
2. Clear cache: `php artisan config:clear && php artisan cache:clear`
3. Verify user permissions for accessing the plugin

#### Migration Manager Not Working

**Problem**: Migration commands fail or don't show proper status.

**Solution**:
1. Ensure database connection is properly configured
2. Run `php artisan migrate:status` to check Laravel migration status
3. Verify database user has proper permissions for schema modifications

#### Health Monitoring Data Missing

**Problem**: Performance metrics are not being collected.

**Solution**:
1. Enable health monitoring in configuration
2. Run metrics collection manually: `php artisan database-manager:collect-metrics`
3. Set up cron job for automated collection
4. Check database permissions for query log access

#### Schema Designer Issues

**Problem**: Schema designer fails to load or save changes.

**Solution**:
1. Verify database connection has sufficient permissions
2. Ensure JavaScript is enabled in browser
3. Check browser console for error messages
4. Clear browser cache and reload

### Performance Optimization

#### Large Database Optimization

For databases with many tables or large datasets:

1. **Enable Query Caching**: Configure database query caching
2. **Limit Data Display**: Use pagination for large table views
3. **Optimize Metrics Collection**: Adjust collection frequency
4. **Index Optimization**: Ensure proper database indexing

#### Memory Usage

If experiencing memory issues:

1. **Increase PHP Memory Limit**: Set `memory_limit` in php.ini
2. **Use Chunked Processing**: Enable chunked processing for large operations
3. **Optimize Queries**: Review and optimize database queries
4. **Clear Cache Regularly**: Implement cache clearing strategies

### Debug Mode

Enable debug mode for detailed error information:

```php
// .env
APP_DEBUG=true
LOG_LEVEL=debug

// config/codeforge-database-studio.php
'debug' => env('CODEFORGE_DEBUG', false),
```

## 📞 Support

### Getting Help

If you encounter issues or need assistance:

1. **Documentation**: Check this comprehensive documentation first
2. **Email Support**: Contact support@hardikkanajariya.in
3. **Community Forum**: Visit our community forum for peer support
4. **Video Tutorials**: Watch our YouTube channel for visual guides

### Support Channels

- **Email**: support@hardikkanajariya.in (Priority support for Extended License holders)
- **Website**: https://hardikkanajariya.in/support
- **Documentation**: https://docs.hardikkanajariya.in/codeforge-database-studio
- **Community**: https://community.hardikkanajariya.in

### Before Contacting Support

Please include the following information when contacting support:

1. **License Type**: Regular or Extended
2. **Laravel Version**: Output of `php artisan --version`
3. **PHP Version**: Output of `php -v`
4. **Error Messages**: Complete error messages or stack traces
5. **Steps to Reproduce**: Detailed steps to reproduce the issue
6. **Environment**: Development, staging, or production
7. **Database Type**: MySQL, PostgreSQL, SQLite, or SQL Server

### Response Times

- **Extended License**: 24-48 hours
- **Regular License**: 48-72 hours
- **Community Forum**: Community-driven, varies

## 🎓 Best Practices

### Security

1. **User Permissions**: Implement proper user permissions for database operations
2. **Backup Before Changes**: Always backup before major schema changes
3. **Environment Separation**: Use different configurations for different environments
4. **Access Control**: Limit access to production database tools

### Performance

1. **Monitor Regularly**: Set up automated health monitoring
2. **Optimize Queries**: Regularly review and optimize slow queries
3. **Index Management**: Maintain proper database indexes
4. **Resource Monitoring**: Monitor server resources during operations

### Development Workflow

1. **Version Control**: Track schema changes in version control
2. **Testing**: Test database changes in development environment first
3. **Documentation**: Maintain up-to-date database documentation
4. **Code Review**: Review generated code before committing

## 📈 Updates and Changelog

### Staying Updated

- **Automatic Updates**: Enable automatic update notifications
- **Changelog**: Review the CHANGELOG.md for new features
- **Breaking Changes**: Check for breaking changes before updating
- **Backup Before Update**: Always backup before major updates

### Update Process

```bash
# Update the plugin
composer update hkdevs/codeforge-database-studio

# Run any new migrations
php artisan migrate

# Clear cache
php artisan config:clear && php artisan cache:clear
```

---

**Thank you for choosing CodeForge Database Studio! We're committed to providing you with the best database management experience for Laravel and FilamentPHP.**
