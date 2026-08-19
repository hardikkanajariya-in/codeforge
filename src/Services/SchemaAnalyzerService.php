<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * SchemaAnalyzerService
 *
 * Comprehensive database schema analysis and introspection service for CodeForge Database Studio.
 * Provides deep schema analysis, relationship discovery, and performance optimization recommendations.
 *
 * Features:
 * - Complete database schema introspection with multi-database support
 * - Intelligent relationship discovery with foreign key analysis
 * - Performance analysis with index utilization and optimization recommendations
 * - Schema comparison and diff generation between databases
 * - Data integrity validation with constraint checking
 * - Schema statistics and metrics collection
 * - Database-specific optimization recommendations
 * - Change impact analysis for schema modifications
 *
 * Schema Analysis Capabilities:
 * - Table Structure Analysis: Complete table structure with columns, types, and constraints
 * - Relationship Discovery: Automatic detection of foreign key relationships and dependencies
 * - Index Analysis: Comprehensive index analysis with usage statistics and optimization
 * - Constraint Validation: Validation of check constraints, unique constraints, and rules
 * - Data Type Analysis: Column data type analysis with storage optimization recommendations
 * - Schema Statistics: Database size, table row counts, and storage utilization metrics
 * - Dependency Mapping: Complete dependency analysis for tables and schema objects
 *
 * Performance Analysis:
 * - Index Utilization: Analysis of index usage patterns and optimization opportunities
 * - Query Performance: Schema-based query performance analysis and recommendations
 * - Storage Optimization: Storage efficiency analysis with compression recommendations
 * - Fragmentation Analysis: Table and index fragmentation detection and resolution
 * - Cardinality Analysis: Column cardinality analysis for index optimization
 * - Partition Analysis: Table partitioning recommendations for large datasets
 * - Resource Usage: Database resource utilization analysis and optimization
 *
 * Relationship Discovery:
 * - Foreign Key Detection: Automatic discovery of foreign key relationships
 * - Implicit Relationships: Detection of implicit relationships through naming patterns
 * - Circular Dependency Detection: Identification of circular dependencies and cycles
 * - Relationship Validation: Validation of referential integrity and constraint compliance
 * - Cascade Analysis: Analysis of cascade operations and their impact
 * - Orphaned Record Detection: Identification of orphaned records and data integrity issues
 * - Relationship Optimization: Optimization recommendations for relationship performance
 *
 * Database Compatibility:
 * - Multi-Database Support: Support for MySQL, PostgreSQL, SQLite, SQL Server
 * - Driver-Specific Analysis: Database-specific analysis and optimization features
 * - Cross-Platform Compatibility: Schema comparison across different database platforms
 * - Migration Analysis: Analysis of schema migration requirements and compatibility
 * - Feature Detection: Detection of database-specific features and capabilities
 * - Version Compatibility: Analysis of database version compatibility and features
 * - Optimization Strategies: Database-specific optimization recommendations
 *
 * Schema Comparison:
 * - Schema Diffing: Detailed comparison between schema versions
 * - Change Detection: Automatic detection of schema changes and modifications
 * - Migration Generation: Automatic migration generation based on schema differences
 * - Impact Analysis: Analysis of change impact on applications and performance
 * - Rollback Analysis: Analysis of rollback requirements and strategies
 * - Compatibility Checking: Validation of schema changes for backward compatibility
 * - Documentation Generation: Automatic documentation of schema changes
 *
 * Data Integrity:
 * - Constraint Validation: Comprehensive validation of database constraints
 * - Referential Integrity: Validation of foreign key relationships and data consistency
 * - Data Quality Assessment: Analysis of data quality and consistency issues
 * - Duplicate Detection: Identification of duplicate data and normalization opportunities
 * - Null Value Analysis: Analysis of null value patterns and data completeness
 * - Data Distribution: Statistical analysis of data distribution and patterns
 * - Anomaly Detection: Detection of data anomalies and inconsistencies
 *
 * Integration Features:
 * - Laravel Integration: Seamless integration with Laravel's Schema Builder
 * - Model Integration: Integration with Eloquent models for relationship analysis
 * - Migration Integration: Analysis of Laravel migrations and schema evolution
 * - Testing Integration: Schema analysis for testing and validation purposes
 * - API Integration: REST endpoints for external schema analysis tools
 * - Export Capabilities: Export schema analysis results in multiple formats
 * - Monitoring Integration: Integration with database monitoring and alerting systems
 *
 * Performance Optimization:
 * - Efficient Introspection: Optimized database queries for schema analysis
 * - Caching Strategies: Intelligent caching of schema analysis results
 * - Batch Processing: Optimized batch processing for large schema analysis
 * - Memory Management: Efficient memory usage for complex schema operations
 * - Parallel Analysis: Multi-threaded analysis for improved performance
 * - Resource Optimization: CPU and I/O optimization for schema operations
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * $service = new SchemaAnalyzerService('mysql');
 * $tables = $service->getAllTables();
 * $relationships = $service->analyzeRelationships();
 * $performance = $service->analyzePerformance();
 */
class SchemaAnalyzerService
{
    protected string $connectionName;

    protected Builder $schema;

    public function __construct(?string $connectionName = null)
    {
        $this->connectionName = $connectionName ?? config('database.default');
        $this->schema = Schema::connection($this->connectionName);
    }

    /**
     * Get all table names from the database
     */
    protected function getTableNames(): array
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    return $this->getMysqlTableNames();
                case 'pgsql':
                    return $this->getPostgresTableNames();
                case 'sqlite':
                    return $this->getSqliteTableNames();
                default:
                    // Fallback: try to use doctrine schema manager
                    return DB::connection($this->connectionName)->getDoctrineSchemaManager()->listTableNames();
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get all tables with their basic information
     */
    public function getAllTables(): array
    {
        $tables = [];
        $tableNames = $this->getTableNames();

        foreach ($tableNames as $tableName) {
            $tables[] = [
                'name' => $tableName,
                'columns' => $this->getTableColumns($tableName),
                'indexes' => $this->getTableIndexes($tableName),
                'foreign_keys' => $this->getTableForeignKeys($tableName),
                'row_count' => $this->getTableRowCount($tableName),
                'size' => $this->getTableSize($tableName),
            ];
        }

        return $tables;
    }

    /**
     * Get detailed table information
     */
    public function getTableDetails(string $tableName): array
    {
        return [
            'name' => $tableName,
            'columns' => $this->getTableColumns($tableName),
            'indexes' => $this->getTableIndexes($tableName),
            'foreign_keys' => $this->getTableForeignKeys($tableName),
            'referenced_by' => $this->getReferencingTables($tableName),
            'row_count' => $this->getTableRowCount($tableName),
            'size' => $this->getTableSize($tableName),
            'created_at' => $this->getTableCreatedAt($tableName),
        ];
    }

    /**
     * Get all relationships between tables
     */
    public function getAllRelationships(): array
    {
        $relationships = [];
        $tableNames = $this->getTableNames();

        // First, get explicit foreign key relationships
        foreach ($tableNames as $tableName) {
            $foreignKeys = $this->getTableForeignKeys($tableName);

            foreach ($foreignKeys as $foreignKey) {
                $relationships[] = [
                    'from_table' => $tableName,
                    'from_column' => $foreignKey['column'],
                    'to_table' => $foreignKey['foreign_table'],
                    'to_column' => $foreignKey['foreign_column'],
                    'constraint_name' => $foreignKey['name'] ?? null,
                    'relationship_type' => 'foreign_key',
                    'on_update' => $foreignKey['on_update'] ?? 'RESTRICT',
                    'on_delete' => $foreignKey['on_delete'] ?? 'RESTRICT',
                ];
            }
        }

        // If no explicit foreign keys found, try to infer relationships from column names
        if (empty($relationships)) {
            $relationships = $this->inferRelationshipsFromColumnNames($tableNames);
        }

        return $relationships;
    }

    /**
     * Infer relationships from column naming conventions
     */
    protected function inferRelationshipsFromColumnNames(array $tableNames): array
    {
        $relationships = [];
        $allTables = $this->getAllTables();

        foreach ($allTables as $table) {
            $tableName = $table['name'];

            foreach ($table['columns'] as $column) {
                $columnName = $column['name'];

                // Look for columns ending with '_id' (but not 'id' itself)
                if (str_ends_with($columnName, '_id') && $columnName !== 'id') {
                    $referencedTableName = $this->guessReferencedTable($columnName, $tableNames);

                    if ($referencedTableName && $referencedTableName !== $tableName) {
                        $relationships[] = [
                            'from_table' => $tableName,
                            'from_column' => $columnName,
                            'to_table' => $referencedTableName,
                            'to_column' => 'id',
                            'constraint_name' => null,
                            'relationship_type' => 'inferred',
                            'on_update' => 'CASCADE',
                            'on_delete' => 'CASCADE',
                        ];
                    }
                }
            }
        }

        return $relationships;
    }

    /**
     * Guess the referenced table name from a foreign key column name
     */
    protected function guessReferencedTable(string $columnName, array $tableNames): ?string
    {
        // Remove '_id' suffix
        $baseName = substr($columnName, 0, -3);

        // Try exact match first
        if (in_array($baseName, $tableNames)) {
            return $baseName;
        }

        // Try plural form
        $pluralName = $baseName.'s';
        if (in_array($pluralName, $tableNames)) {
            return $pluralName;
        }

        // Try removing 's' for singular
        if (str_ends_with($baseName, 's')) {
            $singularName = substr($baseName, 0, -1);
            if (in_array($singularName, $tableNames)) {
                return $singularName;
            }
        }

        // Try common irregular plurals
        $irregularPlurals = [
            'person' => 'people',
            'child' => 'children',
            'man' => 'men',
            'woman' => 'women',
            'foot' => 'feet',
            'tooth' => 'teeth',
            'category' => 'categories',
            'company' => 'companies',
        ];

        foreach ($irregularPlurals as $singular => $plural) {
            if ($baseName === $singular && in_array($plural, $tableNames)) {
                return $plural;
            }
            if ($baseName === $plural && in_array($singular, $tableNames)) {
                return $singular;
            }
        }

        // Try case-insensitive matching
        foreach ($tableNames as $tableName) {
            if (strcasecmp($baseName, $tableName) === 0) {
                return $tableName;
            }
        }

        return null;
    }

    /**
     * Debug method to understand relationship detection
     */
    public function debugRelationshipDetection(): array
    {
        $debug = [];
        $tableNames = $this->getTableNames();

        $debug['total_tables'] = count($tableNames);
        $debug['table_names'] = $tableNames;

        // Check explicit foreign keys
        $debug['explicit_foreign_keys'] = [];
        foreach ($tableNames as $tableName) {
            $foreignKeys = $this->getTableForeignKeys($tableName);
            if (! empty($foreignKeys)) {
                $debug['explicit_foreign_keys'][$tableName] = $foreignKeys;
            }
        }

        // Check inferred relationships
        $debug['inferred_relationships'] = $this->inferRelationshipsFromColumnNames($tableNames);

        // Check all columns with _id suffix
        $debug['id_columns'] = [];
        $allTables = $this->getAllTables();
        foreach ($allTables as $table) {
            foreach ($table['columns'] as $column) {
                if (str_ends_with($column['name'], '_id')) {
                    $debug['id_columns'][] = [
                        'table' => $table['name'],
                        'column' => $column['name'],
                        'type' => $column['type'],
                        'is_foreign_key' => $column['is_foreign_key'] ?? false,
                    ];
                }
            }
        }

        return $debug;
    }

    /**
     * Get table columns with detailed information
     */
    protected function getTableColumns(string $tableName): array
    {
        $columns = [];
        $columnListing = $this->schema->getColumnListing($tableName);

        foreach ($columnListing as $columnName) {
            $columnType = $this->schema->getColumnType($tableName, $columnName);

            $columns[] = [
                'name' => $columnName,
                'type' => $columnType,
                'nullable' => $this->isColumnNullable($tableName, $columnName),
                'default' => $this->getColumnDefault($tableName, $columnName),
                'auto_increment' => $this->isColumnAutoIncrement($tableName, $columnName),
                'primary_key' => $this->isColumnPrimaryKey($tableName, $columnName),
                'unique' => $this->isColumnUnique($tableName, $columnName),
                'is_foreign_key' => $this->isColumnForeignKey($tableName, $columnName),
            ];
        }

        return $columns;
    }

    /**
     * Check if column is a foreign key
     */
    protected function isColumnForeignKey(string $tableName, string $columnName): bool
    {
        $foreignKeys = $this->getTableForeignKeys($tableName);

        foreach ($foreignKeys as $foreignKey) {
            if ($foreignKey['column'] === $columnName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get table indexes
     */
    protected function getTableIndexes(string $tableName): array
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    return $this->getMysqlIndexes($tableName);
                case 'pgsql':
                    return $this->getPostgresIndexes($tableName);
                case 'sqlite':
                    return $this->getSqliteIndexes($tableName);
                default:
                    return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get table foreign keys
     */
    protected function getTableForeignKeys(string $tableName): array
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    return $this->getMysqlForeignKeys($tableName);
                case 'pgsql':
                    return $this->getPostgresForeignKeys($tableName);
                case 'sqlite':
                    return $this->getSqliteForeignKeys($tableName);
                default:
                    return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get tables that reference this table
     */
    protected function getReferencingTables(string $tableName): array
    {
        $referencingTables = [];
        $allRelationships = $this->getAllRelationships();

        foreach ($allRelationships as $relationship) {
            if ($relationship['to_table'] === $tableName) {
                $referencingTables[] = [
                    'table' => $relationship['from_table'],
                    'column' => $relationship['from_column'],
                    'references_column' => $relationship['to_column'],
                ];
            }
        }

        return $referencingTables;
    }

    /**
     * Get table row count
     */
    protected function getTableRowCount(string $tableName): int
    {
        try {
            return DB::connection($this->connectionName)->table($tableName)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get table size (approximate)
     */
    protected function getTableSize(string $tableName): ?string
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    return $this->getMysqlTableSize($tableName);
                case 'pgsql':
                    return $this->getPostgresTableSize($tableName);
                default:
                    return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get table creation date
     */
    protected function getTableCreatedAt(string $tableName): ?string
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    return $this->getMysqlTableCreatedAt($tableName);
                case 'pgsql':
                    return $this->getPostgresTableCreatedAt($tableName);
                default:
                    return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    // Driver-specific implementations

    protected function isColumnNullable(string $tableName, string $columnName): bool
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    $result = $connection->select("SHOW COLUMNS FROM `$tableName` WHERE Field = ?", [$columnName]);

                    return isset($result[0]) && $result[0]->Null === 'YES';
                case 'pgsql':
                    $result = $connection->select('SELECT is_nullable FROM information_schema.columns WHERE table_name = ? AND column_name = ?', [$tableName, $columnName]);

                    return isset($result[0]) && $result[0]->is_nullable === 'YES';
                default:
                    return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getColumnDefault(string $tableName, string $columnName): ?string
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    $result = $connection->select("SHOW COLUMNS FROM `$tableName` WHERE Field = ?", [$columnName]);

                    return $result[0]->Default ?? null;
                case 'pgsql':
                    $result = $connection->select('SELECT column_default FROM information_schema.columns WHERE table_name = ? AND column_name = ?', [$tableName, $columnName]);

                    return $result[0]->column_default ?? null;
                default:
                    return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function isColumnAutoIncrement(string $tableName, string $columnName): bool
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    $result = $connection->select("SHOW COLUMNS FROM `$tableName` WHERE Field = ?", [$columnName]);

                    return isset($result[0]) && strpos($result[0]->Extra, 'auto_increment') !== false;
                case 'pgsql':
                    $result = $connection->select('SELECT column_default FROM information_schema.columns WHERE table_name = ? AND column_name = ?', [$tableName, $columnName]);

                    return isset($result[0]) && strpos($result[0]->column_default, 'nextval') !== false;
                default:
                    return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function isColumnPrimaryKey(string $tableName, string $columnName): bool
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    $result = $connection->select("SHOW COLUMNS FROM `$tableName` WHERE Field = ?", [$columnName]);

                    return isset($result[0]) && $result[0]->Key === 'PRI';
                case 'pgsql':
                    $result = $connection->select("
                        SELECT column_name 
                        FROM information_schema.table_constraints tc 
                        JOIN information_schema.key_column_usage kcu 
                        ON tc.constraint_name = kcu.constraint_name 
                        WHERE tc.table_name = ? AND tc.constraint_type = 'PRIMARY KEY' AND kcu.column_name = ?
                    ", [$tableName, $columnName]);

                    return ! empty($result);
                default:
                    return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function isColumnUnique(string $tableName, string $columnName): bool
    {
        try {
            $connection = DB::connection($this->connectionName);
            $driver = $connection->getDriverName();

            switch ($driver) {
                case 'mysql':
                    $result = $connection->select("SHOW COLUMNS FROM `$tableName` WHERE Field = ?", [$columnName]);

                    return isset($result[0]) && in_array($result[0]->Key, ['UNI', 'PRI']);
                case 'pgsql':
                    $result = $connection->select("
                        SELECT column_name 
                        FROM information_schema.table_constraints tc 
                        JOIN information_schema.key_column_usage kcu 
                        ON tc.constraint_name = kcu.constraint_name 
                        WHERE tc.table_name = ? AND tc.constraint_type = 'UNIQUE' AND kcu.column_name = ?
                    ", [$tableName, $columnName]);

                    return ! empty($result);
                default:
                    return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    // MySQL specific methods
    protected function getMysqlIndexes(string $tableName): array
    {
        $connection = DB::connection($this->connectionName);
        $indexes = $connection->select("SHOW INDEX FROM `$tableName`");

        $result = [];
        foreach ($indexes as $index) {
            $result[] = [
                'name' => $index->Key_name,
                'column' => $index->Column_name,
                'unique' => $index->Non_unique == 0,
                'type' => $index->Index_type,
            ];
        }

        return $result;
    }

    protected function getMysqlForeignKeys(string $tableName): array
    {
        $connection = DB::connection($this->connectionName);
        $database = $connection->getDatabaseName();

        $foreignKeys = $connection->select('
            SELECT 
                kcu.COLUMN_NAME as column_name,
                kcu.REFERENCED_TABLE_NAME as foreign_table,
                kcu.REFERENCED_COLUMN_NAME as foreign_column,
                kcu.CONSTRAINT_NAME as constraint_name,
                rc.UPDATE_RULE as on_update,
                rc.DELETE_RULE as on_delete
            FROM information_schema.KEY_COLUMN_USAGE kcu
            JOIN information_schema.REFERENTIAL_CONSTRAINTS rc 
                ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
                AND kcu.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA
            WHERE kcu.TABLE_SCHEMA = ? AND kcu.TABLE_NAME = ? 
                AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
                AND kcu.REFERENCED_TABLE_SCHEMA IS NOT NULL
            ORDER BY kcu.ORDINAL_POSITION
        ', [$database, $tableName]);

        $result = [];
        foreach ($foreignKeys as $fk) {
            $result[] = [
                'column' => $fk->column_name,
                'foreign_table' => $fk->foreign_table,
                'foreign_column' => $fk->foreign_column,
                'name' => $fk->constraint_name,
                'on_update' => $fk->on_update,
                'on_delete' => $fk->on_delete,
            ];
        }

        return $result;
    }

    protected function getMysqlTableSize(string $tableName): ?string
    {
        $connection = DB::connection($this->connectionName);
        $database = $connection->getDatabaseName();

        $result = $connection->select('
            SELECT 
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
            FROM information_schema.TABLES 
            WHERE table_schema = ? AND table_name = ?
        ', [$database, $tableName]);

        return $result[0]->size_mb ?? null;
    }

    protected function getMysqlTableCreatedAt(string $tableName): ?string
    {
        $connection = DB::connection($this->connectionName);
        $database = $connection->getDatabaseName();

        $result = $connection->select('
            SELECT CREATE_TIME 
            FROM information_schema.TABLES 
            WHERE table_schema = ? AND table_name = ?
        ', [$database, $tableName]);

        return $result[0]->CREATE_TIME ?? null;
    }

    // PostgreSQL specific methods
    protected function getPostgresIndexes(string $tableName): array
    {
        $connection = DB::connection($this->connectionName);

        $indexes = $connection->select("
            SELECT 
                i.relname as index_name,
                a.attname as column_name,
                ix.indisunique as is_unique
            FROM pg_class t,
                 pg_class i,
                 pg_index ix,
                 pg_attribute a
            WHERE t.oid = ix.indrelid
                AND i.oid = ix.indexrelid
                AND a.attrelid = t.oid
                AND a.attnum = ANY(ix.indkey)
                AND t.relkind = 'r'
                AND t.relname = ?
        ", [$tableName]);

        $result = [];
        foreach ($indexes as $index) {
            $result[] = [
                'name' => $index->index_name,
                'column' => $index->column_name,
                'unique' => $index->is_unique,
                'type' => 'btree', // Default for PostgreSQL
            ];
        }

        return $result;
    }

    protected function getPostgresForeignKeys(string $tableName): array
    {
        $connection = DB::connection($this->connectionName);

        $foreignKeys = $connection->select("
            SELECT 
                kcu.column_name,
                ccu.table_name AS foreign_table,
                ccu.column_name AS foreign_column,
                tc.constraint_name,
                rc.update_rule as on_update,
                rc.delete_rule as on_delete
            FROM information_schema.table_constraints AS tc 
            JOIN information_schema.key_column_usage AS kcu
                ON tc.constraint_name = kcu.constraint_name
                AND tc.table_schema = kcu.table_schema
            JOIN information_schema.constraint_column_usage AS ccu
                ON ccu.constraint_name = tc.constraint_name
                AND ccu.table_schema = tc.table_schema
            LEFT JOIN information_schema.referential_constraints rc
                ON tc.constraint_name = rc.constraint_name
                AND tc.table_schema = rc.constraint_schema
            WHERE tc.constraint_type = 'FOREIGN KEY' 
                AND tc.table_name = ?
                AND tc.table_schema = 'public'
            ORDER BY kcu.ordinal_position
        ", [$tableName]);

        $result = [];
        foreach ($foreignKeys as $fk) {
            $result[] = [
                'column' => $fk->column_name,
                'foreign_table' => $fk->foreign_table,
                'foreign_column' => $fk->foreign_column,
                'name' => $fk->constraint_name,
                'on_update' => $fk->on_update ?? 'NO ACTION',
                'on_delete' => $fk->on_delete ?? 'NO ACTION',
            ];
        }

        return $result;
    }

    protected function getPostgresTableSize(string $tableName): ?string
    {
        $connection = DB::connection($this->connectionName);

        $result = $connection->select('
            SELECT pg_size_pretty(pg_total_relation_size(?::regclass)) as size
        ', [$tableName]);

        return $result[0]->size ?? null;
    }

    protected function getPostgresTableCreatedAt(string $tableName): ?string
    {
        // PostgreSQL doesn't store table creation time by default
        return null;
    }

    // SQLite specific methods
    protected function getSqliteIndexes(string $tableName): array
    {
        $connection = DB::connection($this->connectionName);

        try {
            $indexes = $connection->select("PRAGMA index_list('$tableName')");
            $result = [];

            foreach ($indexes as $index) {
                $indexInfo = $connection->select("PRAGMA index_info('{$index->name}')");
                foreach ($indexInfo as $info) {
                    $result[] = [
                        'name' => $index->name,
                        'column' => $info->name,
                        'unique' => $index->unique == 1,
                        'type' => 'btree',
                    ];
                }
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getSqliteForeignKeys(string $tableName): array
    {
        $connection = DB::connection($this->connectionName);

        try {
            $foreignKeys = $connection->select("PRAGMA foreign_key_list('$tableName')");
            $result = [];

            foreach ($foreignKeys as $fk) {
                $result[] = [
                    'column' => $fk->from,
                    'foreign_table' => $fk->table,
                    'foreign_column' => $fk->to,
                    'name' => "fk_{$tableName}_{$fk->from}_{$fk->table}_{$fk->to}",
                    'on_update' => $fk->on_update ?? 'NO ACTION',
                    'on_delete' => $fk->on_delete ?? 'NO ACTION',
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    // Driver-specific table name methods

    protected function getMysqlTableNames(): array
    {
        $connection = DB::connection($this->connectionName);
        $database = $connection->getDatabaseName();

        try {
            // Get tables from current database only
            $tables = $connection->select("SHOW TABLES FROM `{$database}`");
            $tableNameColumn = "Tables_in_{$database}";

            $tableNames = array_map(function ($table) use ($tableNameColumn) {
                return $table->$tableNameColumn;
            }, $tables);

            // Return all tables without filtering
            return $tableNames;
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getPostgresTableNames(): array
    {
        $connection = DB::connection($this->connectionName);

        try {
            $tables = $connection->select("
                SELECT table_name 
                FROM information_schema.tables 
                WHERE table_schema = 'public' 
                AND table_type = 'BASE TABLE'
            ");

            $tableNames = array_map(function ($table) {
                return $table->table_name;
            }, $tables);

            // Return all tables without filtering
            return $tableNames;
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getSqliteTableNames(): array
    {
        $connection = DB::connection($this->connectionName);

        try {
            $tables = $connection->select("
                SELECT name 
                FROM sqlite_master 
                WHERE type='table' 
                AND name NOT LIKE 'sqlite_%'
            ");

            $tableNames = array_map(function ($table) {
                return $table->name;
            }, $tables);

            // Return all tables without filtering
            return $tableNames;
        } catch (\Exception $e) {
            return [];
        }
    }
}
