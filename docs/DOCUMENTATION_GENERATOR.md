# Documentation Generator - Phase 6

The Documentation Generator is a comprehensive feature that automatically generates professional database documentation from your Laravel application's schema, migrations, models, and relationships.

## Features

### 🚀 Auto-Generated Schema Documentation
- **Complete Database Analysis**: Extracts table structures, column types, constraints, and relationships
- **Model Discovery**: Automatically finds and analyzes Eloquent models
- **Relationship Mapping**: Documents foreign keys and model relationships
- **Statistics Inclusion**: Row counts, table sizes, and performance metrics

### 📄 Multiple Output Formats
- **Markdown (.md)**: Perfect for documentation portals, wikis, and developer documentation
- **HTML (.html)**: Styled web pages ready for sharing and viewing
- **PDF (.pdf)**: Professional documents for reports and presentations

### 🎯 Flexible Documentation Scope
- **Full Schema**: Complete database documentation
- **Selected Tables**: Choose specific tables to document
- **Single Table**: Focused documentation for one table
- **Models Only**: Document only tables that have Eloquent models

### 📸 Schema Snapshots
- **Version Control**: Track schema changes over time
- **Baseline Comparisons**: Mark snapshots as baselines for change tracking
- **Change Detection**: Automatically detect schema differences
- **Historical Analysis**: Compare any two snapshots to see what changed

### 🔄 Export & Sharing
- **Download Links**: Direct download of generated documentation
- **Preview Mode**: View documentation in browser before downloading
- **Batch Generation**: Generate multiple formats simultaneously
- **CLI Commands**: Automate documentation generation

## Quick Start

### 1. Generate Documentation (UI)
Navigate to **Database Manager → Documentation Generator** in your Filament admin panel:

1. **Quick Generate**: Use predefined settings for instant documentation
2. **Advanced Settings**: Customize title, format, scope, and options
3. **Download**: Get your documentation immediately after generation

### 2. CLI Commands

#### Generate Documentation
```bash
# Quick markdown documentation
php artisan db-manager:generate-docs

# Custom format and scope
php artisan db-manager:generate-docs --format=html --scope=models_only --title="API Documentation"

# Selected tables only
php artisan db-manager:generate-docs --scope=selected_tables --tables=users --tables=posts --tables=comments

# Auto-download generated file
php artisan db-manager:generate-docs --format=pdf --auto-download --output=./database-docs.pdf
```

#### Create Schema Snapshots
```bash
# Create a snapshot
php artisan db-manager:create-snapshot --name="Pre-deployment snapshot"

# Create baseline snapshot
php artisan db-manager:create-snapshot --name="Production baseline" --baseline
```

#### Cleanup Old Files
```bash
# Remove files older than 30 days
php artisan db-manager:cleanup-docs

# Dry run to see what would be deleted
php artisan db-manager:cleanup-docs --dry-run

# Remove only failed generations
php artisan db-manager:cleanup-docs --failed-only --days=7
```

## Configuration

Add these settings to your `config/codeforge-database-studio.php`:

```php
'documentation_generator' => [
    'enabled' => true,
    'auto_create_snapshots' => false,
    'snapshot_retention_days' => 90,
    'default_format' => 'markdown',
    'include_statistics' => true,
    'include_model_methods' => true,
    'include_validation_rules' => false,
    'include_policy_information' => false,
    'max_file_size_mb' => 50,
    'storage_disk' => 'local',
    'storage_path' => 'documentation-generations',
],
```

## What's Included in Documentation

### 📊 Database Tables
- Table and column information
- Data types, lengths, and constraints
- Primary keys and indexes
- Foreign key relationships
- Table statistics (row counts, sizes)

### 🔗 Relationships
- Foreign key constraints
- Referenced tables and columns
- Relationship diagrams and descriptions

### 🏗️ Eloquent Models
- Model class information
- Fillable and guarded fields
- Cast definitions
- Model relationships (hasMany, belongsTo, etc.)
- Custom model methods

### 📈 Statistics & Metadata
- Database connection information
- Generation timestamp and version
- Schema hash for change detection
- Performance statistics

## Advanced Features

### Schema Comparison
Compare any two schema snapshots to see:
- Added/removed tables
- Modified table structures
- Column changes
- New/deleted relationships

### Batch Operations
- Generate multiple formats simultaneously
- Bulk cleanup of old documentation
- Mass snapshot creation

### Integration Ready
- RESTful download URLs
- Filament admin integration
- Artisan command support
- Event-driven architecture

## File Management

### Storage Structure
```
storage/app/documentation-generations/
├── 2024/
│   ├── 01/
│   │   ├── database-docs_2024-01-15_10-30-00.md
│   │   ├── api-documentation_2024-01-15_14-45-30.html
│   │   └── schema-export_2024-01-15_16-20-15.pdf
│   └── 02/
└── 2025/
```

### Automatic Cleanup
- Configurable retention periods
- Size-based cleanup policies
- Failed generation cleanup
- Baseline snapshot protection

## Security & Permissions

### Access Control
- Filament policy integration
- Role-based documentation access
- Secure download URLs
- File access validation

### Data Protection
- No sensitive data exposure
- Configurable field filtering
- Safe HTML generation
- PDF security headers

## Troubleshooting

### Common Issues

#### PDF Generation Requires dompdf
```bash
composer require dompdf/dompdf
```

#### Large Database Timeouts
Increase PHP execution time for large databases:
```php
// In your documentation generation
ini_set('max_execution_time', 300); // 5 minutes
```

#### Storage Permission Issues
Ensure Laravel can write to storage:
```bash
chmod -R 755 storage/
chown -R www-data:www-data storage/
```

### Performance Tips

1. **Use Schema Snapshots**: Pre-generate snapshots for faster documentation
2. **Scope Appropriately**: Don't document unnecessary tables
3. **Batch Operations**: Generate multiple formats from same snapshot
4. **Regular Cleanup**: Remove old files to save disk space

## API Reference

### Models

#### DocumentationGeneration
```php
// Create new generation
$generation = DocumentationGeneration::create([
    'title' => 'My Documentation',
    'format' => 'markdown',
    'scope' => 'full_schema',
]);

// Generate documentation
$service = new DocumentationGenerationService($generation);
$service->generate();
```

#### SchemaSnapshot
```php
// Create snapshot
$service = new SchemaDocumentationService();
$snapshot = $service->generateSchemaSnapshot('Snapshot Name');

// Compare snapshots
$changes = $currentSnapshot->getChangesFromPrevious();
```

### Services

#### DocumentationGenerationService
- `generate()`: Generate documentation file
- `filterDataByScope()`: Filter schema data by scope
- `generateMarkdown()`: Create markdown output
- `generateHtml()`: Create HTML output
- `generatePdf()`: Create PDF output

#### SchemaDocumentationService
- `generateSchemaSnapshot()`: Create complete schema snapshot
- `analyzeDatabase()`: Analyze database structure
- `discoverModels()`: Find Eloquent models
- `extractRelationships()`: Map table relationships

## Examples

### Basic Documentation Generation
```php
use HkDevs\CodeForgeStudio\Models\DocumentationGeneration;
use HkDevs\CodeForgeStudio\Services\DocumentationGenerationService;

$generation = DocumentationGeneration::create([
    'title' => 'User Management Documentation',
    'description' => 'Documentation for user-related tables',
    'format' => 'html',
    'scope' => 'selected_tables',
    'included_tables' => ['users', 'roles', 'permissions'],
]);

$service = new DocumentationGenerationService($generation);
$service->generate();

echo "Documentation available at: " . $generation->download_url;
```

### Schema Comparison
```php
use HkDevs\CodeForgeStudio\Services\SchemaDocumentationService;

$service = new SchemaDocumentationService();

// Create baseline
$baseline = $service->generateSchemaSnapshot('Baseline', 'Initial schema');
$baseline->markAsBaseline();

// Later, create new snapshot and compare
$current = $service->generateSchemaSnapshot('Current', 'After changes');
$changes = $current->getChangesFromPrevious();

foreach ($changes['added_tables'] as $table) {
    echo "Added table: {$table}\n";
}
```

## Professional Support

The Documentation Generator is part of the HkDevs Filament Database Manager premium package.

### Getting Support
- **Professional Support**: Contact [hardikkanajariya@yahoo.com](mailto:hardikkanajariya@yahoo.com)
- **Priority Response**: Licensed customers receive priority support
- **Documentation**: Comprehensive documentation available in your admin panel

### Premium Features
- Enhanced PDF styling options
- Custom documentation templates
- Professional consultation available
- Advanced model analysis features

---

**Premium Plugin by HkDevs** - Professional support available at [hardikkanajariya@yahoo.com](mailto:hardikkanajariya@yahoo.com)
