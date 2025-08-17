# Troubleshooting Seeder Execution Issues

This guide helps diagnose and resolve common seeder execution problems in CodeForge Database Studio.

## Understanding the Error: "Auto Seeders Completed with Issues: 0 successful, 1 failed"

This error indicates that one or more seeders failed to execute properly during the auto-seeder run. Here's how to diagnose and fix the issue.

## Quick Fix for "Auto Seeders Completed with Issues"

If you see "Auto Seeders Completed with Issues: 0 successful, 1 failed", follow these steps:

### Step 1: Quick Diagnosis
```bash
# Check what's wrong with auto-run seeders
php artisan codeforge:diagnose-seeders --auto
```

### Step 2: Fix Common Issues
```bash
# Fix incorrect file paths automatically
php artisan codeforge:fix-seeder-paths --cleanup

# Or preview what will be changed first
php artisan codeforge:fix-seeder-paths --dry-run
```

### Step 3: Verify the Fix
```bash
# Check if issues are resolved
php artisan codeforge:diagnose-seeders --auto
```

### Alternative: Using Filament UI
1. Go to **Data Seeders** in Filament Admin
2. Click **"Discover Seeders"** to fix file paths
3. Click **"Cleanup Invalid Seeders"** to remove broken entries
4. Try running auto-seeders again

## Quick Diagnostic Command

Use the built-in diagnostic command to quickly identify issues:

```bash
# Check all failed seeders from the last 7 days
php artisan codeforge:diagnose-seeders --failed

# Check all auto-run seeders
php artisan codeforge:diagnose-seeders --auto

# Check a specific seeder
php artisan codeforge:diagnose-seeders --seeder=YourSeederName

# Check all seeders
php artisan codeforge:diagnose-seeders
```

## Common Issues and Solutions

### 1. Seeder File Not Found

**Error:** "Seeder file not found at path: /path/to/SeederFile.php"

**Causes:**
- File was moved or deleted
- Incorrect file path in the database
- File permissions issue

**Solutions:**
```bash
# Re-discover seeders to update file paths
php artisan codeforge:run-seeders --dry-run

# Check if file exists manually
ls -la database/seeders/YourSeeder.php

# Update the file path in the database if the file was moved
```

### 2. Class Not Found

**Error:** "Seeder class 'App\\Database\\Seeders\\YourSeeder' not found"

**Causes:**
- Syntax errors in the seeder file
- Incorrect namespace
- Class name doesn't match filename
- Autoloading issues

**Solutions:**
```bash
# Check for syntax errors
php -l database/seeders/YourSeeder.php

# Regenerate autoload files
composer dump-autoload

# Verify the class namespace and name match the file structure
```

**Example of correct seeder structure:**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class YourSeeder extends Seeder
{
    public function run(): void
    {
        // Your seeding logic here
    }
}
```

### 3. Invalid Seeder Class

**Error:** "Class 'YourSeeder' is not a valid seeder class"

**Causes:**
- Class doesn't extend `Illuminate\Database\Seeder`
- Abstract class marked as instantiable
- Missing required methods

**Solutions:**
```php
// Ensure your seeder extends the base Seeder class
use Illuminate\Database\Seeder;

class YourSeeder extends Seeder
{
    public function run(): void
    {
        // Implementation required
    }
}
```

### 4. Database Connection Issues

**Error:** Database connection errors during seeding

**Causes:**
- Database not accessible
- Invalid database credentials
- Database server down

**Solutions:**
```bash
# Test database connection
php artisan db:show

# Check database configuration
php artisan config:cache

# Verify database credentials in .env file
```

### 5. Seeder Status Issues

**Error:** "Seeder 'YourSeeder' is not active"

**Causes:**
- Seeder status is set to 'inactive' or 'draft'
- Seeder was manually disabled

**Solutions:**
1. **Via Filament Admin:**
   - Go to Data Seeders resource
   - Find your seeder
   - Change status to 'active'

2. **Via Database:**
   ```sql
   UPDATE data_seeders 
   SET status = 'active' 
   WHERE name = 'YourSeeder';
   ```

### 6. Memory or Timeout Issues

**Error:** Memory exhausted or execution timeout

**Causes:**
- Large dataset generation
- Inefficient seeding logic
- Insufficient memory limits

**Solutions:**
```php
// In your seeder, use chunking for large datasets
public function run(): void
{
    $chunkSize = 1000;
    $totalRecords = 100000;
    
    for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
        $this->createChunk($chunkSize);
        
        // Free memory
        if ($i % 10000 === 0) {
            gc_collect_cycles();
        }
    }
}
```

**PHP Configuration:**
```ini
# Increase memory limit in php.ini
memory_limit = 512M

# Increase execution time
max_execution_time = 300
```

## Viewing Detailed Error Information

### 1. Check Execution Logs in Filament

1. Go to **Seeder Manager** → **Execution Logs**
2. Filter by failed status
3. Click the eye icon to view detailed output and error messages

### 2. Using the Diagnostic Command

```bash
# Get detailed information about a specific failed seeder
php artisan codeforge:diagnose-seeders --seeder=YourSeederName
```

### 3. Check Laravel Logs

```bash
# View recent Laravel logs
tail -f storage/logs/laravel.log

# Search for seeder-related errors
grep -i "seeder" storage/logs/laravel.log
```

## Prevention Best Practices

### 1. Seeder Development Guidelines

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExampleSeeder extends Seeder
{
    public function run(): void
    {
        // Use database transactions for safety
        DB::transaction(function () {
            // Disable foreign key checks if needed
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            try {
                // Your seeding logic here
                $this->seedData();
                
            } finally {
                // Re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        });
    }
    
    private function seedData(): void
    {
        // Implement your seeding logic
        // Use Model::factory() for large datasets
        // Use chunking for memory efficiency
    }
}
```

### 2. Testing Seeders

```bash
# Test individual seeders before adding to auto-run
php artisan db:seed --class=YourSeeder

# Use dry-run mode to validate configuration
php artisan codeforge:run-seeders --seeder=YourSeeder --dry-run
```

### 3. Monitoring and Maintenance

1. **Regular Diagnostics:**
   ```bash
   # Weekly check of all auto-run seeders
   php artisan codeforge:diagnose-seeders --auto
   ```

2. **Log Cleanup:**
   ```bash
   # Clean old execution logs
   php artisan codeforge:cleanup-logs
   ```

3. **Performance Monitoring:**
   - Monitor execution times in the Execution Logs
   - Set up alerts for consistently failing seeders
   - Review memory usage in seeder metadata

## Getting Additional Help

If you continue to experience issues:

1. **Check the execution logs** in Filament for detailed error messages
2. **Run the diagnostic command** for comprehensive issue analysis
3. **Review Laravel logs** for framework-level errors
4. **Contact support** at support@hardikkanajariya.in with:
   - Error messages from execution logs
   - Output from diagnostic command
   - Laravel version and environment details

## Related Commands

```bash
# Fix seeder paths and cleanup invalid entries
php artisan codeforge:fix-seeder-paths --cleanup

# Preview changes without applying them
php artisan codeforge:fix-seeder-paths --dry-run

# Debug discovery process
php artisan codeforge:debug-discovery

# Run specific seeder
php artisan codeforge:run-seeders --seeder=YourSeeder

# Run all auto-seeders
php artisan codeforge:run-seeders --auto

# Run all active seeders
php artisan codeforge:run-seeders

# Diagnose seeder issues
php artisan codeforge:diagnose-seeders

# Check failed seeders
php artisan codeforge:diagnose-seeders --failed

# Check auto-run seeders
php artisan codeforge:diagnose-seeders --auto

# Clean up old logs
php artisan codeforge:cleanup-logs
```
