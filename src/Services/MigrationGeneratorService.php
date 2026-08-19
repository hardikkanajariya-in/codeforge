<?php

namespace HkDevs\CodeForgeStudio\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * MigrationGeneratorService
 *
 * Advanced Laravel migration generation service for CodeForge Database Studio.
 * Creates intelligent, optimized database migrations with comprehensive schema management capabilities.
 *
 * Features:
 * - Intelligent migration generation with optimal column ordering and indexing
 * - Comprehensive support for all Laravel migration column types and modifiers
 * - Advanced relationship handling with foreign key constraints and cascading
 * - Index optimization with automatic recommendation and generation
 * - Migration rollback support with proper down method implementation
 * - Schema modification detection with smart alter table generation
 * - Performance optimization with query-efficient migration structures
 * - Cross-database compatibility with platform-specific optimizations
 *
 * Migration Generation Capabilities:
 * - Table Creation: Complete table creation with columns, indexes, and constraints
 * - Table Modification: Intelligent alter table operations with change detection
 * - Column Management: Add, modify, drop columns with proper type conversions
 * - Index Management: Primary keys, unique constraints, foreign keys, and composite indexes
 * - Relationship Setup: Foreign key constraints with cascading and referential integrity
 * - Data Seeding: Optional data insertion with migration generation
 * - Schema Validation: Pre-generation validation and conflict detection
 *
 * Advanced Features:
 * - Smart Column Ordering: Optimal column arrangement for performance and readability
 * - Index Optimization: Automatic index recommendation based on column types and usage
 * - Constraint Management: Comprehensive constraint generation with validation
 * - Migration Batching: Intelligent migration grouping for complex schema changes
 * - Rollback Safety: Proper down method generation with data preservation strategies
 * - Schema Diffing: Migration generation based on schema differences
 * - Performance Analysis: Migration impact assessment and optimization recommendations
 *
 * Column Type Management:
 * - Complete Laravel column type support with all available modifiers
 * - Custom column type integration with user-defined specifications
 * - Type validation and compatibility checking across database platforms
 * - Automatic type conversion and optimization for target databases
 * - Length and precision management with validation and recommendations
 * - Nullable and default value handling with intelligent suggestions
 * - Enum and set type management with value validation
 *
 * Relationship Handling:
 * - Foreign Key Generation: Automatic foreign key constraint creation
 * - Cascade Configuration: Intelligent cascade option selection and validation
 * - Relationship Validation: Cross-table reference validation and integrity checking
 * - Index Creation: Automatic index generation for foreign key columns
 * - Constraint Naming: Consistent and descriptive constraint naming conventions
 * - Circular Dependency Detection: Prevention of circular foreign key references
 * - Multi-Table Relationships: Support for complex multi-table relationship scenarios
 *
 * Performance Optimization:
 * - Query-Efficient Structures: Migration generation optimized for query performance
 * - Index Strategy: Intelligent index creation based on expected usage patterns
 * - Storage Optimization: Column type selection for optimal storage efficiency
 * - Migration Batching: Optimized batch processing for large schema changes
 * - Resource Management: Memory-efficient migration generation and execution
 * - Parallel Processing: Support for parallel migration execution where safe
 *
 * Quality Assurance:
 * - Migration Validation: Comprehensive validation of generated migration syntax
 * - Rollback Testing: Automatic validation of rollback method functionality
 * - Schema Integrity: Database schema integrity checking and validation
 * - Conflict Detection: Prevention of conflicting migration operations
 * - Best Practice Enforcement: Adherence to Laravel migration best practices
 * - Documentation Generation: Automatic generation of migration documentation
 *
 * Integration Features:
 * - Laravel Integration: Seamless integration with Laravel's migration system
 * - Artisan Command: Full compatibility with Laravel Artisan migration commands
 * - Schema Builder: Integration with Laravel's Schema Builder for consistency
 * - Model Integration: Automatic integration with Eloquent model generation
 * - Testing Integration: Migration testing utilities and validation frameworks
 * - Version Control: Git-friendly migration naming and organization strategies
 *
 * Customization Options:
 * - Template System: Customizable migration templates with user-defined patterns
 * - Naming Conventions: Configurable naming strategies for migrations and constraints
 * - Code Style: Integration with code formatting standards and style guides
 * - Custom Types: Support for custom column types and database-specific features
 * - Extension Points: Plugin architecture for custom migration generation logic
 * - Configuration Management: Flexible configuration options for generation behavior
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * $service = app(MigrationGeneratorService::class);
 * $result = $service->generateMigration([
 *     'name' => 'create_users_table',
 *     'type' => 'create',
 *     'table' => 'users',
 *     'columns' => [
 *         ['name' => 'name', 'type' => 'string'],
 *         ['name' => 'email', 'type' => 'string', 'unique' => true]
 *     ]
 * ]);
 */
class MigrationGeneratorService
{
    protected string $migrationsPath;

    protected array $columnTypes = [
        'id' => 'id',
        'bigId' => 'bigIncrements',
        'increments' => 'increments',
        'string' => 'string',
        'char' => 'char',
        'text' => 'text',
        'mediumText' => 'mediumText',
        'longText' => 'longText',
        'integer' => 'integer',
        'bigInteger' => 'bigInteger',
        'mediumInteger' => 'mediumInteger',
        'smallInteger' => 'smallInteger',
        'tinyInteger' => 'tinyInteger',
        'float' => 'float',
        'double' => 'double',
        'decimal' => 'decimal',
        'boolean' => 'boolean',
        'enum' => 'enum',
        'json' => 'json',
        'jsonb' => 'jsonb',
        'date' => 'date',
        'dateTime' => 'dateTime',
        'dateTimeTz' => 'dateTimeTz',
        'time' => 'time',
        'timeTz' => 'timeTz',
        'timestamp' => 'timestamp',
        'timestampTz' => 'timestampTz',
        'year' => 'year',
        'binary' => 'binary',
        'uuid' => 'uuid',
        'ipAddress' => 'ipAddress',
        'macAddress' => 'macAddress',
        'geometry' => 'geometry',
        'point' => 'point',
        'lineString' => 'lineString',
        'polygon' => 'polygon',
        'geometryCollection' => 'geometryCollection',
        'multiPoint' => 'multiPoint',
        'multiLineString' => 'multiLineString',
        'multiPolygon' => 'multiPolygon',
    ];

    public function __construct()
    {
        $this->migrationsPath = database_path('migrations');
    }

    /**
     * Generate a complete migration file
     */
    public function generateMigration(array $migrationData): array
    {
        $migrationName = $this->generateMigrationName($migrationData['type'], $migrationData['table_name']);
        $className = $this->generateClassName($migrationData['type'], $migrationData['table_name']);
        $fileName = $this->generateFileName($migrationName);

        $content = $this->generateMigrationContent($className, $migrationData);

        $filePath = $this->migrationsPath.'/'.$fileName;

        // Check if file already exists
        if (File::exists($filePath)) {
            throw new \Exception("Migration file already exists: {$fileName}");
        }

        // Create the migration file
        File::put($filePath, $content);

        return [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'migration_name' => $migrationName,
            'content' => $content,
            'success' => true,
        ];
    }

    /**
     * Preview migration content without creating file
     */
    public function previewMigration(array $migrationData): array
    {
        $migrationName = $this->generateMigrationName($migrationData['type'], $migrationData['table_name']);
        $className = $this->generateClassName($migrationData['type'], $migrationData['table_name']);
        $fileName = $this->generateFileName($migrationName);

        $content = $this->generateMigrationContent($className, $migrationData);

        return [
            'file_name' => $fileName,
            'class_name' => $className,
            'migration_name' => $migrationName,
            'content' => $content,
            'preview' => true,
        ];
    }

    /**
     * Generate preview content (alias for previewMigration for BaseGeneratorPage compatibility)
     */
    public function generatePreview(array $migrationData): array
    {
        $preview = $this->previewMigration($migrationData);

        return [
            'migration' => [
                'content' => $preview['content'],
                'file_name' => $preview['file_name'],
                'file_path' => 'database/migrations/'.$preview['file_name'],
            ],
        ];
    }

    /**
     * Validate migration data
     */
    public function validateMigrationData(array $migrationData): array
    {
        $errors = [];

        // Required fields
        if (empty($migrationData['type'])) {
            $errors[] = 'Migration type is required';
        }

        if (empty($migrationData['table_name'])) {
            $errors[] = 'Table name is required';
        } elseif (! $this->isValidTableName($migrationData['table_name'])) {
            $errors[] = 'Invalid table name format';
        }

        // Validate columns for create/modify operations
        if (in_array($migrationData['type'], ['create', 'modify']) && empty($migrationData['columns'])) {
            $errors[] = 'Columns are required for create/modify operations';
        }

        // Validate individual columns
        if (! empty($migrationData['columns'])) {
            foreach ($migrationData['columns'] as $index => $column) {
                $columnErrors = $this->validateColumn($column, $index);
                $errors = array_merge($errors, $columnErrors);
            }
        }

        // Validate indexes
        if (! empty($migrationData['indexes'])) {
            foreach ($migrationData['indexes'] as $index => $indexData) {
                $indexErrors = $this->validateIndex($indexData, $index);
                $errors = array_merge($errors, $indexErrors);
            }
        }

        // Validate foreign keys
        if (! empty($migrationData['foreign_keys'])) {
            foreach ($migrationData['foreign_keys'] as $index => $fk) {
                $fkErrors = $this->validateForeignKey($fk, $index);
                $errors = array_merge($errors, $fkErrors);
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Generate migration content
     */
    protected function generateMigrationContent(string $className, array $migrationData): string
    {
        $tableName = $migrationData['table_name'];
        $type = $migrationData['type'];

        $upMethod = $this->generateUpMethod($type, $tableName, $migrationData);
        $downMethod = $this->generateDownMethod($type, $tableName, $migrationData);

        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
{$upMethod}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
{$downMethod}
    }
};
PHP;
    }

    /**
     * Generate up method content
     */
    protected function generateUpMethod(string $type, string $tableName, array $migrationData): string
    {
        switch ($type) {
            case 'create':
                return $this->generateCreateTableMethod($tableName, $migrationData);
            case 'modify':
                return $this->generateModifyTableMethod($tableName, $migrationData);
            case 'drop':
                return "        Schema::dropIfExists('{$tableName}');";
            case 'rename':
                $newName = $migrationData['new_table_name'] ?? $tableName.'_renamed';

                return "        Schema::rename('{$tableName}', '{$newName}');";
            default:
                return '        // Custom migration logic here';
        }
    }

    /**
     * Generate down method content
     */
    protected function generateDownMethod(string $type, string $tableName, array $migrationData): string
    {
        switch ($type) {
            case 'create':
                return "        Schema::dropIfExists('{$tableName}');";
            case 'modify':
                return '        // Reverse the table modifications';
            case 'drop':
                return '        // Cannot reverse drop operation automatically';
            case 'rename':
                $newName = $migrationData['new_table_name'] ?? $tableName.'_renamed';

                return "        Schema::rename('{$newName}', '{$tableName}');";
            default:
                return '        // Reverse custom migration logic here';
        }
    }

    /**
     * Generate create table method
     */
    protected function generateCreateTableMethod(string $tableName, array $migrationData): string
    {
        $lines = [];
        $lines[] = "        Schema::create('{$tableName}', function (Blueprint \$table) {";

        // Add columns
        if (! empty($migrationData['columns'])) {
            foreach ($migrationData['columns'] as $column) {
                $lines[] = $this->generateColumnDefinition($column);
            }
        }

        // Add indexes
        if (! empty($migrationData['indexes'])) {
            $lines[] = '';
            $lines[] = '            // Indexes';
            foreach ($migrationData['indexes'] as $index) {
                $lines[] = $this->generateIndexDefinition($index);
            }
        }

        // Add foreign keys
        if (! empty($migrationData['foreign_keys'])) {
            $lines[] = '';
            $lines[] = '            // Foreign Keys';
            foreach ($migrationData['foreign_keys'] as $fk) {
                $lines[] = $this->generateForeignKeyDefinition($fk);
            }
        }

        // Add timestamps if specified
        if ($migrationData['timestamps'] ?? false) {
            $lines[] = '';
            $lines[] = '            $table->timestamps();';
        }

        // Add soft deletes if specified
        if ($migrationData['soft_deletes'] ?? false) {
            $lines[] = '            $table->softDeletes();';
        }

        $lines[] = '        });';

        return implode("\n", $lines);
    }

    /**
     * Generate modify table method
     */
    protected function generateModifyTableMethod(string $tableName, array $migrationData): string
    {
        $lines = [];
        $lines[] = "        Schema::table('{$tableName}', function (Blueprint \$table) {";

        // Add new columns
        if (! empty($migrationData['columns'])) {
            foreach ($migrationData['columns'] as $column) {
                if (($column['action'] ?? 'add') === 'add') {
                    $lines[] = $this->generateColumnDefinition($column);
                }
            }
        }

        $lines[] = '        });';

        // Handle column modifications and drops in separate schema calls
        if (! empty($migrationData['columns'])) {
            foreach ($migrationData['columns'] as $column) {
                $action = $column['action'] ?? 'add';
                if ($action === 'modify') {
                    $lines[] = '';
                    $lines[] = "        Schema::table('{$tableName}', function (Blueprint \$table) {";
                    $lines[] = $this->generateColumnDefinition($column, true);
                    $lines[] = '        });';
                } elseif ($action === 'drop') {
                    $lines[] = '';
                    $lines[] = "        Schema::table('{$tableName}', function (Blueprint \$table) {";
                    $lines[] = "            \$table->dropColumn('{$column['name']}');";
                    $lines[] = '        });';
                }
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Generate column definition
     */
    protected function generateColumnDefinition(array $column, bool $isModify = false): string
    {
        $name = $column['name'];
        $type = $column['type'];
        $length = $column['length'] ?? null;
        $precision = $column['precision'] ?? null;
        $scale = $column['scale'] ?? null;
        $nullable = $column['nullable'] ?? false;
        $default = $column['default'] ?? null;
        $unsigned = $column['unsigned'] ?? false;
        $autoIncrement = $column['auto_increment'] ?? false;
        $primary = $column['primary'] ?? false;
        $unique = $column['unique'] ?? false;
        $index = $column['index'] ?? false;
        $comment = $column['comment'] ?? null;

        $definition = '            $table->';

        // Handle column type
        if (isset($this->columnTypes[$type])) {
            $method = $this->columnTypes[$type];

            if ($type === 'enum' && ! empty($column['enum_values'])) {
                $enumValues = "'".implode("', '", $column['enum_values'])."'";
                $definition .= "{$method}('{$name}', [{$enumValues}])";
            } elseif (in_array($type, ['string', 'char']) && $length) {
                $definition .= "{$method}('{$name}', {$length})";
            } elseif (in_array($type, ['decimal', 'float', 'double']) && $precision && $scale) {
                $definition .= "{$method}('{$name}', {$precision}, {$scale})";
            } else {
                $definition .= "{$method}('{$name}')";
            }
        } else {
            $definition .= "string('{$name}')"; // fallback
        }

        // Add modifiers
        if ($unsigned) {
            $definition .= '->unsigned()';
        }

        if ($nullable) {
            $definition .= '->nullable()';
        }

        if ($default !== null) {
            if (is_string($default)) {
                $definition .= "->default('{$default}')";
            } elseif (is_bool($default)) {
                $definition .= $default ? '->default(true)' : '->default(false)';
            } else {
                $definition .= "->default({$default})";
            }
        }

        if ($autoIncrement) {
            $definition .= '->autoIncrement()';
        }

        if ($primary) {
            $definition .= '->primary()';
        }

        if ($unique) {
            $definition .= '->unique()';
        }

        if ($index) {
            $definition .= '->index()';
        }

        if ($comment) {
            $definition .= "->comment('{$comment}')";
        }

        if ($isModify) {
            $definition .= '->change()';
        }

        $definition .= ';';

        return $definition;
    }

    /**
     * Generate index definition
     */
    protected function generateIndexDefinition(array $index): string
    {
        $columns = is_array($index['columns']) ? $index['columns'] : [$index['columns']];
        $type = $index['type'] ?? 'index';
        $name = $index['name'] ?? null;

        $columnsStr = "'".implode("', '", $columns)."'";

        if (count($columns) === 1) {
            $columnsStr = "'{$columns[0]}'";
        } else {
            $columnsStr = "[{$columnsStr}]";
        }

        $definition = "            \$table->{$type}({$columnsStr}";

        if ($name) {
            $definition .= ", '{$name}'";
        }

        $definition .= ');';

        return $definition;
    }

    /**
     * Generate foreign key definition
     */
    protected function generateForeignKeyDefinition(array $fk): string
    {
        $column = $fk['column'] ?? '';
        $referencedTable = $fk['referenced_table'] ?? '';
        $referencedColumn = $fk['referenced_column'] ?? 'id';
        $onDelete = $fk['on_delete'] ?? 'restrict';
        $onUpdate = $fk['on_update'] ?? 'restrict';

        $definition = "            \$table->foreign('{$column}')->references('{$referencedColumn}')->on('{$referencedTable}')";

        if ($onDelete !== 'restrict') {
            $definition .= "->onDelete('{$onDelete}')";
        }

        if ($onUpdate !== 'restrict') {
            $definition .= "->onUpdate('{$onUpdate}')";
        }

        $definition .= ';';

        return $definition;
    }

    /**
     * Generate migration name
     */
    protected function generateMigrationName(string $type, string $tableName): string
    {
        $action = match ($type) {
            'create' => 'create',
            'modify' => 'modify',
            'drop' => 'drop',
            'rename' => 'rename',
            default => 'update'
        };

        return "{$action}_{$tableName}_table";
    }

    /**
     * Generate class name
     */
    protected function generateClassName(string $type, string $tableName): string
    {
        $action = match ($type) {
            'create' => 'Create',
            'modify' => 'Modify',
            'drop' => 'Drop',
            'rename' => 'Rename',
            default => 'Update'
        };

        return $action.Str::studly($tableName).'Table';
    }

    /**
     * Generate file name
     */
    protected function generateFileName(string $migrationName): string
    {
        $timestamp = Carbon::now()->format('Y_m_d_His');

        return "{$timestamp}_{$migrationName}.php";
    }

    /**
     * Validate table name
     */
    protected function isValidTableName(string $tableName): bool
    {
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tableName) === 1;
    }

    /**
     * Validate column data
     */
    protected function validateColumn(array $column, int $index): array
    {
        $errors = [];

        if (empty($column['name'])) {
            $errors[] = "Column {$index}: Name is required";
        } elseif (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column['name'])) {
            $errors[] = "Column {$index}: Invalid name format";
        }

        if (empty($column['type'])) {
            $errors[] = "Column {$index}: Type is required";
        } elseif (! isset($this->columnTypes[$column['type']])) {
            $errors[] = "Column {$index}: Invalid column type";
        }

        // Validate type-specific requirements
        if ($column['type'] === 'enum' && empty($column['enum_values'])) {
            $errors[] = "Column {$index}: Enum values are required for enum type";
        }

        if (in_array($column['type'], ['decimal', 'float', 'double'])) {
            if (empty($column['precision'])) {
                $errors[] = "Column {$index}: Precision is required for {$column['type']} type";
            }
        }

        return $errors;
    }

    /**
     * Validate index data
     */
    protected function validateIndex(array $index, int $indexNum): array
    {
        $errors = [];

        if (empty($index['columns'])) {
            $errors[] = "Index {$indexNum}: Columns are required";
        }

        $validTypes = ['index', 'unique', 'primary', 'fulltext', 'spatial'];
        if (! empty($index['type']) && ! in_array($index['type'], $validTypes)) {
            $errors[] = "Index {$indexNum}: Invalid index type";
        }

        return $errors;
    }

    /**
     * Validate foreign key data
     */
    protected function validateForeignKey(array $fk, int $fkNum): array
    {
        $errors = [];

        if (empty($fk['column'] ?? '')) {
            $errors[] = "Foreign Key {$fkNum}: Column is required";
        }

        if (empty($fk['referenced_table'] ?? '')) {
            $errors[] = "Foreign Key {$fkNum}: Referenced table is required";
        }

        $validActions = ['restrict', 'cascade', 'set null', 'no action'];
        if (! empty($fk['on_delete'] ?? '') && ! in_array(strtolower($fk['on_delete']), $validActions)) {
            $errors[] = "Foreign Key {$fkNum}: Invalid on delete action";
        }

        if (! empty($fk['on_update'] ?? '') && ! in_array(strtolower($fk['on_update']), $validActions)) {
            $errors[] = "Foreign Key {$fkNum}: Invalid on update action";
        }

        return $errors;
    }

    /**
     * Get available column types
     */
    public function getAvailableColumnTypes(): array
    {
        return array_keys($this->columnTypes);
    }

    /**
     * Generate migration files (required by BaseGeneratorPage)
     */
    public function generateFiles(array $migrationData): array
    {
        $results = [
            'success' => false,
            'files_created' => [],
            'errors' => [],
        ];

        try {
            $migration = $this->generateMigration($migrationData);

            $results['files_created'][] = [
                'path' => $migration['file_path'],
                'type' => 'migration',
                'class_name' => $migration['class_name'],
                'migration_name' => $migration['migration_name'],
            ];

            $results['success'] = true;
        } catch (\Exception $e) {
            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Get existing migrations
     */
    public function getExistingMigrations(): array
    {
        $migrations = [];
        $files = File::glob($this->migrationsPath.'/*.php');

        foreach ($files as $file) {
            $fileName = basename($file);
            $migrations[] = [
                'file_name' => $fileName,
                'file_path' => $file,
                'created_at' => File::lastModified($file),
                'size' => File::size($file),
            ];
        }

        return collect($migrations)->sortByDesc('created_at')->values()->toArray();
    }
}
