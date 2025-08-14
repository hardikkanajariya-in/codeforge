# Multi-Database Connection Testing Guide

## Overview
This CodeForge Database Studio installation has been configured with multiple database connections to test the plugin's multi-database functionality, particularly the **"Test All Connections"** feature in the Database Health Dashboard.

## Configured Database Connections

### ✅ Working Connections
These connections should show as **Connected** with response times:

1. **`mysql`** - Main MySQL database (codeforge)
2. **`mariadb`** - Same as MySQL but using MariaDB driver
3. **`sqlite_test`** - SQLite database with test users data
4. **`sqlite_reports`** - SQLite database with reports data
5. **`sqlite_memory`** - In-memory SQLite for temporary data

### ❌ Failing Connections
These connections are intentionally configured to fail for testing purposes:

1. **`sqlite`** - Wrong database path (demonstrates path errors)
2. **`pgsql`** - PostgreSQL driver not installed
3. **`sqlsrv`** - SQL Server driver not installed
4. **`mysql_analytics`** - MySQL database doesn't exist
5. **`mysql_logs`** - MySQL database doesn't exist
6. **`mysql_failure_test`** - Wrong port (3307 instead of 3306)
7. **`mysql_auth_failure`** - Wrong credentials

## How to Test

### 1. Via Database Health Dashboard (GUI)
1. Navigate to `/admin` and login
2. Go to **Database Health** → **Health Monitor**
3. Click **"Test All Connections"** button in the header
4. Click **"Refresh Metrics"** to see updated results
5. Observe the different connection statuses in the widgets

### 2. Via Command Line
```bash
# Test all connections and see a summary table
php artisan db:test-connections

# Run the seeder to populate test data
php artisan db:seed --class=MultiDatabaseTestSeeder
```

### 3. Via Plugin's Built-in Commands
```bash
# Collect health metrics manually
php artisan database-manager:collect-metrics

# View available database manager commands
php artisan list database-manager
```

## Expected Results

When testing the **"Test All Connections"** feature, you should see:

- **5 successful connections** with response times (usually < 50ms)
- **7 failed connections** with various error messages:
  - Database not found errors
  - Driver not found errors
  - Authentication failures
  - Network connection failures

This demonstrates the plugin's ability to:
- Handle multiple database types (MySQL, SQLite, PostgreSQL, SQL Server)
- Properly report connection failures with meaningful error messages
- Measure response times for healthy connections
- Gracefully handle various error scenarios

## Database Contents

### SQLite Test Database (`sqlite_test`)
- **Table**: `test_users`
- **Data**: 3 sample users (John Doe, Jane Smith, Bob Johnson)

### SQLite Reports Database (`sqlite_reports`)
- **Table**: `reports`
- **Data**: 3 sample reports with different statuses

### In-Memory SQLite (`sqlite_memory`)
- **Table**: `temp_data`
- **Data**: JSON-based temporary metrics and cache data

## Plugin Configuration

The plugin is configured in `AdminPanelProvider.php` with:
```php
CodeForgeStudioPlugin::make()
    ->enableSchemaDesigner(false)
    ->enableMigrationManager(true)
    ->enableHealthMonitoring(true)    // ← This enables the health monitoring
    ->enableDevDocs()
    ->enableSmartSeeding(false)
    ->enableDocumentationGenerator(false)
    ->enableCodeGeneration(false)
```

## Troubleshooting

### If connections are not showing up:
1. Check that `config/database.php` has all the connections defined
2. Restart the development server: `php artisan serve`
3. Clear the config cache: `php artisan config:clear`

### If the Health Dashboard is not accessible:
1. Ensure you're logged into `/admin`
2. Check that `enableHealthMonitoring(true)` is set in `AdminPanelProvider.php`
3. Verify the plugin is properly registered

### To add more test connections:
1. Add new connection configs to `config/database.php`
2. Run `php artisan db:test-connections` to verify
3. The plugin will automatically detect and test the new connections

## Development Server
Start the development server with:
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Then visit: `http://127.0.0.1:8000/admin`

---

This setup provides a comprehensive testing environment for the CodeForge Database Studio's multi-database functionality! 🚀
