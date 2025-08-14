<?php

namespace HkDevs\CodeForgeStudio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * SchemaSnapshot
 * 
 * Eloquent model for capturing and managing comprehensive database schema
 * snapshots with versioning, relationships, and change tracking capabilities.
 * 
 * Key Features:
 * - Complete database schema capture with table structure and relationships
 * - Version management with baseline and incremental snapshot support
 * - Model mapping integration for Laravel Eloquent relationships
 * - Validation rules and policy information preservation
 * - Hash-based change detection for efficient comparison
 * - Multi-connection support for complex database architectures
 * - Documentation generation integration with schema history
 * 
 * Database Fields:
 * - name: Snapshot identifier and descriptive name
 * - description: Snapshot purpose and context information
 * - version: Schema version for tracking evolution
 * - database_connection: Connection identifier for multi-database support
 * - schema_data: Complete table structure and column definitions
 * - table_relationships: Foreign key and relationship mappings
 * - model_mappings: Laravel Eloquent model to table associations
 * - validation_rules: Database constraints and validation logic
 * - policy_information: Access control and security policies
 * - tables_count, relationships_count, models_count: Summary statistics
 * - hash: Content hash for change detection and comparison
 * - is_baseline: Baseline snapshot flag for versioning reference
 * - captured_at: Timestamp of schema capture
 * - captured_by: User attribution for audit trails
 * 
 * Relationships:
 * - DocumentationGeneration: HasMany for schema-based documentation
 * - Change tracking and comparison with other snapshots
 * - Version history for schema evolution analysis
 * 
 * @package HkDevs\CodeForgeStudio\Models
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class SchemaSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'version',
        'database_connection',
        'schema_data',
        'table_relationships',
        'model_mappings',
        'validation_rules',
        'policy_information',
        'tables_count',
        'relationships_count',
        'models_count',
        'hash',
        'is_baseline',
        'captured_at',
        'captured_by',
    ];

    protected $casts = [
        'schema_data' => 'array',
        'table_relationships' => 'array',
        'model_mappings' => 'array',
        'validation_rules' => 'array',
        'policy_information' => 'array',
        'is_baseline' => 'boolean',
        'captured_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'version' => '1.0.0',
        'database_connection' => 'mysql',
        'tables_count' => 0,
        'relationships_count' => 0,
        'models_count' => 0,
        'is_baseline' => false,
    ];

    public function documentationGenerations(): HasMany
    {
        return $this->hasMany(DocumentationGeneration::class);
    }

    public function scopeBaseline($query)
    {
        return $query->where('is_baseline', true);
    }

    public function scopeByConnection($query, string $connection)
    {
        return $query->where('database_connection', $connection);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('captured_at', '>=', now()->subDays($days));
    }

    public function getTableNamesAttribute(): array
    {
        return array_keys($this->schema_data ?? []);
    }

    public function getTablesWithRelationshipsAttribute(): array
    {
        $tables = [];
        foreach ($this->schema_data ?? [] as $tableName => $tableData) {
            $relationshipCount = 0;

            // Count foreign keys in this table
            if (isset($tableData['foreign_keys'])) {
                $relationshipCount += count($tableData['foreign_keys']);
            }

            // Count foreign keys pointing to this table
            foreach ($this->table_relationships ?? [] as $relationship) {
                if (isset($relationship['referenced_table']) && $relationship['referenced_table'] === $tableName) {
                    $relationshipCount++;
                }
            }

            $tables[$tableName] = [
                'name' => $tableName,
                'columns_count' => count($tableData['columns'] ?? []),
                'relationships_count' => $relationshipCount,
                'has_model' => isset($this->model_mappings[$tableName]),
                'model_class' => isset($this->model_mappings[$tableName])
                    ? ($this->model_mappings[$tableName]['class'] ?? null)
                    : null,
            ];
        }

        return $tables;
    }

    public function getChangesFromPrevious(): ?array
    {
        $previous = static::where('database_connection', $this->database_connection)
            ->where('captured_at', '<', $this->captured_at)
            ->orderBy('captured_at', 'desc')
            ->first();

        if (!$previous) {
            return null;
        }

        return $this->compareSchemas($previous->schema_data, $this->schema_data);
    }

    public function markAsBaseline(): void
    {
        // Remove baseline flag from other snapshots
        static::where('database_connection', $this->database_connection)
            ->where('is_baseline', true)
            ->update(['is_baseline' => false]);

        $this->update(['is_baseline' => true]);
    }

    public function generateHash(): string
    {
        return hash('sha256', json_encode([
            'schema_data' => $this->schema_data,
            'table_relationships' => $this->table_relationships,
            'model_mappings' => $this->model_mappings,
        ]));
    }

    public function compareSchemas(array $oldSchema, array $newSchema): array
    {
        $changes = [
            'added_tables' => [],
            'removed_tables' => [],
            'modified_tables' => [],
        ];

        $oldTables = array_keys($oldSchema);
        $newTables = array_keys($newSchema);

        $changes['added_tables'] = array_diff($newTables, $oldTables);
        $changes['removed_tables'] = array_diff($oldTables, $newTables);

        // Check for modified tables
        foreach (array_intersect($oldTables, $newTables) as $tableName) {
            $oldTable = $oldSchema[$tableName];
            $newTable = $newSchema[$tableName];

            if ($this->tablesAreDifferent($oldTable, $newTable)) {
                $changes['modified_tables'][] = [
                    'table' => $tableName,
                    'changes' => $this->getTableChanges($oldTable, $newTable),
                ];
            }
        }

        return $changes;
    }

    protected function tablesAreDifferent(array $oldTable, array $newTable): bool
    {
        return hash('sha256', json_encode($oldTable)) !== hash('sha256', json_encode($newTable));
    }

    protected function getTableChanges(array $oldTable, array $newTable): array
    {
        $changes = [];

        // Compare columns
        $oldColumns = array_keys($oldTable['columns'] ?? []);
        $newColumns = array_keys($newTable['columns'] ?? []);

        if ($addedColumns = array_diff($newColumns, $oldColumns)) {
            $changes['added_columns'] = $addedColumns;
        }

        if ($removedColumns = array_diff($oldColumns, $newColumns)) {
            $changes['removed_columns'] = $removedColumns;
        }

        // Check for modified columns
        foreach (array_intersect($oldColumns, $newColumns) as $columnName) {
            $oldColumn = $oldTable['columns'][$columnName];
            $newColumn = $newTable['columns'][$columnName];

            if ($oldColumn !== $newColumn) {
                $changes['modified_columns'][$columnName] = [
                    'old' => $oldColumn,
                    'new' => $newColumn,
                ];
            }
        }

        return $changes;
    }
}
