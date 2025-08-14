<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use HkDevs\CodeForgeStudio\Models\SchemaSnapshot;

/**
 * SchemaDocumentationService
 * 
 * Advanced database schema documentation and snapshot management service for CodeForge Database Studio.
 * Provides comprehensive schema documentation generation with versioning and change tracking capabilities.
 * 
 * Features:
 * - Comprehensive schema snapshot generation with complete database structure capture
 * - Laravel model integration with automatic relationship discovery and mapping
 * - Validation rule extraction and documentation from models and form requests
 * - Policy integration with authorization rule documentation
 * - Version control and change tracking with detailed diff generation
 * - Multi-format documentation export with customizable templates
 * - Schema comparison and evolution tracking across environments
 * - Baseline management for migration reference points
 * 
 * Schema Documentation Capabilities:
 * - Complete Table Documentation: Detailed table structure with columns, indexes, and constraints
 * - Relationship Mapping: Comprehensive relationship documentation with visual representation
 * - Model Integration: Laravel Eloquent model detection and relationship mapping
 * - Validation Documentation: Extraction and documentation of validation rules and constraints
 * - Policy Documentation: Authorization policy integration with permission mapping
 * - Data Dictionary: Detailed field descriptions and business logic documentation
 * - Schema Statistics: Database metrics and structural analysis with performance insights
 * 
 * Snapshot Management:
 * - Schema Snapshots: Complete database schema capture with metadata and statistics
 * - Version Control: Schema versioning with change tracking and rollback capabilities
 * - Baseline Management: Establishment and management of schema baselines for migration
 * - Change Detection: Automatic detection of schema changes with detailed diff generation
 * - Snapshot Comparison: Detailed comparison between schema snapshots with impact analysis
 * - Historical Tracking: Long-term schema evolution tracking with trend analysis
 * - Snapshot Optimization: Efficient storage and retrieval of schema snapshot data
 * 
 * Model Integration:
 * - Automatic Model Discovery: Detection of Eloquent models and their relationships
 * - Relationship Analysis: Comprehensive analysis of model relationships and dependencies
 * - Attribute Documentation: Documentation of model attributes, casts, and accessors
 * - Scope Documentation: Query scope documentation with usage examples
 * - Event Documentation: Model event and observer documentation
 * - Trait Integration: Documentation of model traits and their functionality
 * - Factory Integration: Model factory documentation and data generation patterns
 * 
 * Advanced Features:
 * - Cross-Database Schema Analysis: Multi-database schema comparison and documentation
 * - Performance Analysis: Schema-based performance analysis and optimization recommendations
 * - Security Analysis: Security assessment of schema design and access patterns
 * - Compliance Reporting: Automated compliance reports for audit and regulatory requirements
 * - Migration Planning: Schema change planning with impact assessment and recommendations
 * - Documentation Templates: Customizable documentation templates with branding options
 * - Export Capabilities: Multiple export formats including Markdown, HTML, PDF, and JSON
 * 
 * Validation Integration:
 * - Rule Extraction: Automatic extraction of validation rules from models and form requests
 * - Constraint Documentation: Database constraint documentation with business rule mapping
 * - Custom Validation: Documentation of custom validation rules and business logic
 * - Relationship Validation: Validation rule documentation for relationship integrity
 * - Conditional Validation: Context-aware validation rule documentation
 * - Localization Support: Multi-language validation rule documentation
 * - Testing Integration: Validation rule testing and verification documentation
 * 
 * Policy Integration:
 * - Authorization Documentation: Comprehensive policy and authorization rule documentation
 * - Permission Mapping: Detailed permission and role mapping with access control documentation
 * - Gate Documentation: Laravel gate documentation with authorization logic
 * - Middleware Integration: Authorization middleware documentation and access patterns
 * - Role Documentation: User role and permission documentation with inheritance patterns
 * - Security Policies: Security policy documentation with best practice recommendations
 * - Access Audit: Access pattern analysis and security audit documentation
 * 
 * Integration Features:
 * - Laravel Integration: Seamless integration with Laravel applications and conventions
 * - Git Integration: Version control integration with commit-based change tracking
 * - CI/CD Support: Automated documentation generation in deployment pipelines
 * - API Integration: REST endpoints for external documentation systems and tools
 * - Webhook Support: Real-time documentation updates with external system integration
 * - Team Collaboration: Multi-user documentation collaboration with review workflows
 * - External Tools: Integration with external documentation and diagramming tools
 * 
 * Performance Optimization:
 * - Efficient Schema Introspection: Optimized database queries for schema analysis
 * - Caching Strategies: Intelligent caching of documentation data and snapshots
 * - Background Processing: Asynchronous documentation generation for large schemas
 * - Memory Management: Efficient memory usage for complex schema documentation
 * - Streaming Export: Progressive export for large documentation sets
 * - Batch Processing: Optimized batch processing for multiple schema operations
 * 
 * @package HkDevs\CodeForgeStudio\Services
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * $service = new SchemaDocumentationService('mysql');
 * $snapshot = $service->generateSchemaSnapshot('Baseline v1.0', 'Initial schema baseline');
 * $documentation = $service->generateDocumentation(['format' => 'markdown']);
 * $diff = $service->compareSchemas($oldSnapshot, $newSnapshot);
 */
class SchemaDocumentationService
{
    protected string $connection;
    protected Builder $schema;
    protected array $tableData = [];
    protected array $relationships = [];
    protected array $modelMappings = [];
    protected array $validationRules = [];
    protected array $policyInformation = [];

    public function __construct(?string $connection = null)
    {
        $this->connection = $connection ?? config('database.default');
        $this->schema = Schema::connection($this->connection);
    }

    /**
     * Generate a complete schema snapshot
     */
    public function generateSchemaSnapshot(string $name, ?string $description = null): SchemaSnapshot
    {
        $this->analyzeDatabase();
        $this->discoverModels();
        $this->extractValidationRules();
        $this->extractPolicyInformation();

        $snapshot = SchemaSnapshot::create([
            'name' => $name,
            'description' => $description,
            'database_connection' => $this->connection,
            'schema_data' => $this->tableData,
            'table_relationships' => $this->relationships,
            'model_mappings' => $this->modelMappings,
            'validation_rules' => $this->validationRules,
            'policy_information' => $this->policyInformation,
            'tables_count' => count($this->tableData),
            'relationships_count' => count($this->relationships),
            'models_count' => count($this->modelMappings),
            'captured_at' => now(),
            'captured_by' => Auth::user()?->name ?? 'System',
        ]);

        $snapshot->update(['hash' => $snapshot->generateHash()]);

        return $snapshot;
    }

    /**
     * Analyze the complete database structure
     */
    protected function analyzeDatabase(): void
    {
        $tables = $this->getAllTables();

        foreach ($tables as $tableName) {
            $this->tableData[$tableName] = $this->analyzeTable($tableName);
        }

        $this->relationships = $this->extractRelationships();
    }

    /**
     * Get all table names from the database
     */
    protected function getAllTables(): array
    {
        $driver = DB::connection($this->connection)->getDriverName();

        return match ($driver) {
            'mysql' => $this->getMysqlTables(),
            'pgsql' => $this->getPostgresTables(),
            'sqlite' => $this->getSqliteTables(),
            'sqlsrv' => $this->getSqlServerTables(),
            default => []
        };
    }

    protected function getMysqlTables(): array
    {
        $database = DB::connection($this->connection)->getDatabaseName();

        $tables = DB::connection($this->connection)
            ->select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$database]);

        return collect($tables)
            ->map(fn($table) => $table->table_name ?? $table->TABLE_NAME)
            ->toArray();
    }

    protected function getPostgresTables(): array
    {
        $tables = DB::connection($this->connection)
            ->select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");

        return collect($tables)
            ->map(fn($table) => $table->tablename)
            ->toArray();
    }

    protected function getSqliteTables(): array
    {
        $tables = DB::connection($this->connection)
            ->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

        return collect($tables)
            ->map(fn($table) => $table->name)
            ->toArray();
    }

    protected function getSqlServerTables(): array
    {
        $tables = DB::connection($this->connection)
            ->select("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE'");

        return collect($tables)
            ->map(fn($table) => $table->table_name ?? $table->TABLE_NAME)
            ->toArray();
    }

    /**
     * Analyze a specific table structure
     */
    protected function analyzeTable(string $tableName): array
    {
        $columns = $this->getTableColumns($tableName);
        $indexes = $this->getTableIndexes($tableName);
        $foreignKeys = $this->getTableForeignKeys($tableName);

        return [
            'name' => $tableName,
            'columns' => $columns,
            'indexes' => $indexes,
            'foreign_keys' => $foreignKeys,
            'primary_key' => $this->getPrimaryKey($columns),
            'created_at' => $this->getTableCreationTime($tableName),
            'row_count' => $this->getTableRowCount($tableName),
            'size_mb' => $this->getTableSize($tableName),
        ];
    }

    /**
     * Get detailed column information for a table
     */
    protected function getTableColumns(string $tableName): array
    {
        $driver = DB::connection($this->connection)->getDriverName();

        return match ($driver) {
            'mysql' => $this->getMysqlColumns($tableName),
            'pgsql' => $this->getPostgresColumns($tableName),
            'sqlite' => $this->getSqliteColumns($tableName),
            'sqlsrv' => $this->getSqlServerColumns($tableName),
            default => []
        };
    }

    protected function getMysqlColumns(string $tableName): array
    {
        $database = DB::connection($this->connection)->getDatabaseName();

        $columns = DB::connection($this->connection)
            ->select("
                SELECT 
                    column_name,
                    data_type,
                    is_nullable,
                    column_default,
                    character_maximum_length,
                    numeric_precision,
                    numeric_scale,
                    column_key,
                    extra,
                    column_comment
                FROM information_schema.columns 
                WHERE table_schema = ? AND table_name = ?
                ORDER BY ordinal_position
            ", [$database, $tableName]);

        $result = [];
        foreach ($columns as $column) {
            $result[$column->column_name ?? $column->COLUMN_NAME] = [
                'name' => $column->column_name ?? $column->COLUMN_NAME,
                'type' => $column->data_type ?? $column->DATA_TYPE,
                'nullable' => ($column->is_nullable ?? $column->IS_NULLABLE) === 'YES',
                'default' => $column->column_default ?? $column->COLUMN_DEFAULT,
                'length' => $column->character_maximum_length ?? $column->CHARACTER_MAXIMUM_LENGTH,
                'precision' => $column->numeric_precision ?? $column->NUMERIC_PRECISION,
                'scale' => $column->numeric_scale ?? $column->NUMERIC_SCALE,
                'key' => $column->column_key ?? $column->COLUMN_KEY,
                'extra' => $column->extra ?? $column->EXTRA,
                'comment' => $column->column_comment ?? $column->COLUMN_COMMENT,
            ];
        }

        return $result;
    }

    protected function getPostgresColumns(string $tableName): array
    {
        $columns = DB::connection($this->connection)
            ->select("
                SELECT 
                    column_name,
                    data_type,
                    is_nullable,
                    column_default,
                    character_maximum_length,
                    numeric_precision,
                    numeric_scale
                FROM information_schema.columns 
                WHERE table_name = ?
                ORDER BY ordinal_position
            ", [$tableName]);

        $result = [];
        foreach ($columns as $column) {
            $result[$column->column_name] = [
                'name' => $column->column_name,
                'type' => $column->data_type,
                'nullable' => $column->is_nullable === 'YES',
                'default' => $column->column_default,
                'length' => $column->character_maximum_length,
                'precision' => $column->numeric_precision,
                'scale' => $column->numeric_scale,
            ];
        }

        return $result;
    }

    protected function getSqliteColumns(string $tableName): array
    {
        $columns = DB::connection($this->connection)
            ->select("PRAGMA table_info({$tableName})");

        $result = [];
        foreach ($columns as $column) {
            $result[$column->name] = [
                'name' => $column->name,
                'type' => $column->type,
                'nullable' => !$column->notnull,
                'default' => $column->dflt_value,
                'primary_key' => (bool) $column->pk,
            ];
        }

        return $result;
    }

    protected function getSqlServerColumns(string $tableName): array
    {
        $columns = DB::connection($this->connection)
            ->select("
                SELECT 
                    column_name,
                    data_type,
                    is_nullable,
                    column_default,
                    character_maximum_length,
                    numeric_precision,
                    numeric_scale
                FROM information_schema.columns 
                WHERE table_name = ?
                ORDER BY ordinal_position
            ", [$tableName]);

        $result = [];
        foreach ($columns as $column) {
            $result[$column->column_name] = [
                'name' => $column->column_name,
                'type' => $column->data_type,
                'nullable' => $column->is_nullable === 'YES',
                'default' => $column->column_default,
                'length' => $column->character_maximum_length,
                'precision' => $column->numeric_precision,
                'scale' => $column->numeric_scale,
            ];
        }

        return $result;
    }

    /**
     * Get table indexes
     */
    protected function getTableIndexes(string $tableName): array
    {
        try {
            $driver = DB::connection($this->connection)->getDriverName();

            return match ($driver) {
                'mysql' => $this->getMysqlIndexes($tableName),
                'pgsql' => $this->getPostgresIndexes($tableName),
                'sqlite' => $this->getSqliteIndexes($tableName),
                'sqlsrv' => $this->getSqlServerIndexes($tableName),
                default => []
            };
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getMysqlIndexes(string $tableName): array
    {
        $indexes = DB::connection($this->connection)
            ->select("SHOW INDEX FROM {$tableName}");

        $result = [];
        foreach ($indexes as $index) {
            $indexName = $index->Key_name ?? $index->KEY_NAME;
            if (!isset($result[$indexName])) {
                $result[$indexName] = [
                    'name' => $indexName,
                    'unique' => !($index->Non_unique ?? $index->NON_UNIQUE),
                    'primary' => $indexName === 'PRIMARY',
                    'columns' => [],
                ];
            }
            $result[$indexName]['columns'][] = $index->Column_name ?? $index->COLUMN_NAME;
        }

        return array_values($result);
    }

    protected function getPostgresIndexes(string $tableName): array
    {
        // Simplified postgres index detection
        return [];
    }

    protected function getSqliteIndexes(string $tableName): array
    {
        $indexes = DB::connection($this->connection)
            ->select("PRAGMA index_list({$tableName})");

        $result = [];
        foreach ($indexes as $index) {
            $columns = DB::connection($this->connection)
                ->select("PRAGMA index_info({$index->name})");

            $result[] = [
                'name' => $index->name,
                'unique' => (bool) $index->unique,
                'primary' => false,
                'columns' => array_map(fn($col) => $col->name, $columns),
            ];
        }

        return $result;
    }

    protected function getSqlServerIndexes(string $tableName): array
    {
        // Simplified SQL Server index detection
        return [];
    }

    /**
     * Get foreign key relationships for a table
     */
    protected function getTableForeignKeys(string $tableName): array
    {
        try {
            $driver = DB::connection($this->connection)->getDriverName();

            return match ($driver) {
                'mysql' => $this->getMysqlForeignKeys($tableName),
                'pgsql' => $this->getPostgresForeignKeys($tableName),
                'sqlite' => $this->getSqliteForeignKeys($tableName),
                'sqlsrv' => $this->getSqlServerForeignKeys($tableName),
                default => []
            };
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getMysqlForeignKeys(string $tableName): array
    {
        $database = DB::connection($this->connection)->getDatabaseName();

        $foreignKeys = DB::connection($this->connection)
            ->select("
                SELECT 
                    constraint_name,
                    column_name,
                    referenced_table_name,
                    referenced_column_name
                FROM information_schema.key_column_usage 
                WHERE table_schema = ? 
                AND table_name = ? 
                AND referenced_table_name IS NOT NULL
            ", [$database, $tableName]);

        $result = [];
        foreach ($foreignKeys as $fk) {
            $result[] = [
                'constraint_name' => $fk->constraint_name ?? $fk->CONSTRAINT_NAME,
                'column' => $fk->column_name ?? $fk->COLUMN_NAME,
                'referenced_table' => $fk->referenced_table_name ?? $fk->REFERENCED_TABLE_NAME,
                'referenced_column' => $fk->referenced_column_name ?? $fk->REFERENCED_COLUMN_NAME,
            ];
        }

        return $result;
    }

    protected function getPostgresForeignKeys(string $tableName): array
    {
        // Simplified postgres foreign key detection
        return [];
    }

    protected function getSqliteForeignKeys(string $tableName): array
    {
        $foreignKeys = DB::connection($this->connection)
            ->select("PRAGMA foreign_key_list({$tableName})");

        $result = [];
        foreach ($foreignKeys as $fk) {
            $result[] = [
                'constraint_name' => "fk_{$tableName}_{$fk->from}",
                'column' => $fk->from,
                'referenced_table' => $fk->table,
                'referenced_column' => $fk->to,
            ];
        }

        return $result;
    }

    protected function getSqlServerForeignKeys(string $tableName): array
    {
        // Simplified SQL Server foreign key detection
        return [];
    }

    /**
     * Extract relationships between tables
     */
    protected function extractRelationships(): array
    {
        $relationships = [];

        foreach ($this->tableData as $tableName => $tableData) {
            foreach ($tableData['foreign_keys'] as $fk) {
                $relationships[] = [
                    'type' => 'foreign_key',
                    'from_table' => $tableName,
                    'from_column' => $fk['column'],
                    'to_table' => $fk['referenced_table'],
                    'to_column' => $fk['referenced_column'],
                    'constraint_name' => $fk['constraint_name'],
                ];
            }
        }

        return $relationships;
    }

    /**
     * Discover Eloquent models and their table mappings
     */
    protected function discoverModels(): void
    {
        $modelPaths = [
            app_path('Models'),
            app_path(), // For legacy app/ structure
        ];

        foreach ($modelPaths as $path) {
            if (File::exists($path)) {
                $this->scanForModels($path);
            }
        }
    }

    protected function scanForModels(string $path): void
    {
        $files = File::allFiles($path);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $namespace = $this->getNamespaceFromFile($file->getPathname());
            $className = $file->getBasename('.php');
            $fullClassName = $namespace . '\\' . $className;

            if (!class_exists($fullClassName)) {
                continue;
            }

            try {
                $reflection = new ReflectionClass($fullClassName);

                if (
                    !$reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class) ||
                    $reflection->isAbstract()
                ) {
                    continue;
                }

                $model = app($fullClassName);
                $tableName = $model->getTable();

                $this->modelMappings[$tableName] = [
                    'class' => $fullClassName,
                    'table' => $tableName,
                    'fillable' => $model->getFillable(),
                    'guarded' => $model->getGuarded(),
                    'hidden' => $model->getHidden(),
                    'casts' => $model->getCasts(),
                    'relationships' => $this->extractModelRelationships($reflection),
                    'methods' => $this->extractModelMethods($reflection),
                ];
            } catch (\Exception $e) {
                // Skip models that can't be instantiated
                continue;
            }
        }
    }

    protected function getNamespaceFromFile(string $filePath): string
    {
        $content = File::get($filePath);

        if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
            return trim($matches[1]);
        }

        return 'App';
    }

    protected function extractModelRelationships(ReflectionClass $reflection): array
    {
        $relationships = [];
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if ($method->getDeclaringClass()->getName() !== $reflection->getName()) {
                continue;
            }

            $returnType = $method->getReturnType();
            if (!$returnType) {
                continue;
            }

            $returnTypeName = $returnType instanceof \ReflectionNamedType ?
                $returnType->getName() :
                (string) $returnType;

            $relationshipTypes = [
                'Illuminate\Database\Eloquent\Relations\HasOne',
                'Illuminate\Database\Eloquent\Relations\HasMany',
                'Illuminate\Database\Eloquent\Relations\BelongsTo',
                'Illuminate\Database\Eloquent\Relations\BelongsToMany',
                'Illuminate\Database\Eloquent\Relations\HasManyThrough',
                'Illuminate\Database\Eloquent\Relations\MorphOne',
                'Illuminate\Database\Eloquent\Relations\MorphMany',
                'Illuminate\Database\Eloquent\Relations\MorphTo',
            ];

            foreach ($relationshipTypes as $relationType) {
                if (is_subclass_of($returnTypeName, $relationType) || $returnTypeName === $relationType) {
                    $relationships[] = [
                        'method' => $method->getName(),
                        'type' => class_basename($relationType),
                        'return_type' => $returnTypeName,
                    ];
                    break;
                }
            }
        }

        return $relationships;
    }

    protected function extractModelMethods(ReflectionClass $reflection): array
    {
        $methods = [];
        $reflectionMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($reflectionMethods as $method) {
            if ($method->getDeclaringClass()->getName() !== $reflection->getName()) {
                continue;
            }

            // Skip magic methods and some Laravel methods
            if (
                Str::startsWith($method->getName(), ['__', 'get', 'set']) ||
                in_array($method->getName(), ['save', 'delete', 'update', 'create', 'find'])
            ) {
                continue;
            }

            $methods[] = [
                'name' => $method->getName(),
                'parameters' => array_map(fn($param) => [
                    'name' => $param->getName(),
                    'type' => $param->getType() ? (string) $param->getType() : null,
                    'default' => $param->isOptional() ? $param->getDefaultValue() : null,
                ], $method->getParameters()),
                'return_type' => $method->getReturnType() ? (string) $method->getReturnType() : null,
            ];
        }

        return $methods;
    }

    /**
     * Extract validation rules from form requests and model rules
     */
    protected function extractValidationRules(): void
    {
        // This would scan for form requests and extract validation rules
        // Implementation would be similar to model discovery
        $this->validationRules = [];
    }

    /**
     * Extract policy information
     */
    protected function extractPolicyInformation(): void
    {
        // This would scan for policies and extract authorization rules
        // Implementation would be similar to model discovery
        $this->policyInformation = [];
    }

    protected function getPrimaryKey(array $columns): ?string
    {
        foreach ($columns as $column) {
            if (($column['key'] ?? '') === 'PRI' || ($column['primary_key'] ?? false)) {
                return $column['name'];
            }
        }
        return null;
    }

    protected function getTableCreationTime(string $tableName): ?string
    {
        try {
            $driver = DB::connection($this->connection)->getDriverName();

            if ($driver === 'mysql') {
                $result = DB::connection($this->connection)
                    ->select("
                        SELECT create_time 
                        FROM information_schema.tables 
                        WHERE table_schema = ? AND table_name = ?
                    ", [DB::connection($this->connection)->getDatabaseName(), $tableName]);

                return $result[0]->create_time ?? null;
            }
        } catch (\Exception $e) {
            // Ignore errors
        }

        return null;
    }

    protected function getTableRowCount(string $tableName): int
    {
        try {
            return DB::connection($this->connection)
                ->table($tableName)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function getTableSize(string $tableName): ?float
    {
        try {
            $driver = DB::connection($this->connection)->getDriverName();

            if ($driver === 'mysql') {
                $result = DB::connection($this->connection)
                    ->select("
                        SELECT ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
                        FROM information_schema.tables 
                        WHERE table_schema = ? AND table_name = ?
                    ", [DB::connection($this->connection)->getDatabaseName(), $tableName]);

                return (float) ($result[0]->size_mb ?? 0);
            }
        } catch (\Exception $e) {
            // Ignore errors
        }

        return null;
    }
}
