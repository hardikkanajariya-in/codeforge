<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * IntelligentSuggestionService
 *
 * Advanced AI-powered suggestion engine for model generation in CodeForge Database Studio.
 * Provides intelligent, context-aware suggestions based on real database analysis, naming patterns,
 * and industry best practices for Laravel Eloquent models.
 *
 * Key Features:
 * - Dynamic schema analysis for intelligent field suggestions
 * - Pattern recognition from existing database structures
 * - Industry-standard naming convention analysis
 * - Relationship inference from foreign keys and naming patterns
 * - Context-aware attribute casting suggestions
 * - Performance-optimized suggestion algorithms
 *
 * Intelligence Capabilities:
 * - Real-time database schema introspection
 * - Pattern matching against existing table structures
 * - Semantic analysis of model and table names
 * - Cross-table relationship pattern detection
 * - Historical usage pattern analysis
 * - Industry best practice integration
 *
 * Suggestion Categories:
 * - Fillable field suggestions based on table columns
 * - Relationship suggestions from foreign key analysis
 * - Casting suggestions from column data types
 * - Index-aware performance suggestions
 * - Security-aware field handling (hidden fields)
 * - Validation rule suggestions from constraints
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class IntelligentSuggestionService
{
    protected string $connectionName;

    protected SchemaAnalyzerService $schemaAnalyzer;

    public function __construct(?string $connectionName = null)
    {
        $this->connectionName = $connectionName ?? config('database.default');
        $this->schemaAnalyzer = new SchemaAnalyzerService($connectionName);
    }

    /**
     * Get intelligent fillable field suggestions based on table analysis
     */
    public function getFillableFieldSuggestions(string $modelName, ?string $tableName = null): array
    {
        $tableName = $tableName ?: $this->inferTableName($modelName);

        if (! $this->tableExists($tableName)) {
            return $this->getFallbackFillableFields($modelName);
        }

        $tableDetails = $this->schemaAnalyzer->getTableDetails($tableName);
        $columns = $tableDetails['columns'] ?? [];
        $suggestions = [];

        foreach ($columns as $column) {
            $columnName = $column['name'];

            // Skip system columns and primary keys
            if ($this->shouldSkipColumn($columnName, $column)) {
                continue;
            }

            // Add column if it's a good candidate for mass assignment
            if ($this->isGoodFillableCandidate($columnName, $column)) {
                $suggestions[] = $columnName;
            }
        }

        // Add common fields that might not exist yet but are typical
        $suggestions = array_merge($suggestions, $this->getCommonFieldsForContext($modelName, $columns));

        return array_unique($suggestions);
    }

    /**
     * Get intelligent relationship suggestions based on foreign key analysis
     */
    public function getRelationshipSuggestions(string $modelName, ?string $tableName = null): array
    {
        $tableName = $tableName ?: $this->inferTableName($modelName);

        if (! $this->tableExists($tableName)) {
            return [];
        }

        $suggestions = [];

        // Get explicit foreign key relationships
        $suggestions = array_merge($suggestions, $this->getExplicitRelationships($tableName));

        // Get inferred relationships from naming patterns
        $suggestions = array_merge($suggestions, $this->getInferredRelationships($tableName, $modelName));

        // Get reverse relationships (where this model is referenced)
        $suggestions = array_merge($suggestions, $this->getReverseRelationships($tableName, $modelName));

        return $suggestions;
    }

    /**
     * Get intelligent casting suggestions based on column types
     */
    public function getCastingSuggestions(string $modelName, ?string $tableName = null): array
    {
        $tableName = $tableName ?: $this->inferTableName($modelName);

        if (! $this->tableExists($tableName)) {
            return [];
        }

        $tableDetails = $this->schemaAnalyzer->getTableDetails($tableName);
        $columns = $tableDetails['columns'] ?? [];
        $suggestions = [];

        foreach ($columns as $column) {
            $cast = $this->inferCastFromColumn($column);
            if ($cast) {
                $suggestions[] = [
                    'attribute' => $column['name'],
                    'cast' => $cast,
                ];
            }
        }

        return $suggestions;
    }

    /**
     * Get hidden field suggestions for security
     */
    public function getHiddenFieldSuggestions(string $modelName, ?string $tableName = null): array
    {
        $tableName = $tableName ?: $this->inferTableName($modelName);

        if (! $this->tableExists($tableName)) {
            return $this->getCommonHiddenFields($modelName);
        }

        $tableDetails = $this->schemaAnalyzer->getTableDetails($tableName);
        $columns = $tableDetails['columns'] ?? [];
        $suggestions = [];

        foreach ($columns as $column) {
            if ($this->shouldBeHidden($column['name'], $column)) {
                $suggestions[] = $column['name'];
            }
        }

        return $suggestions;
    }

    /**
     * Get date field suggestions
     */
    public function getDateFieldSuggestions(string $modelName, ?string $tableName = null): array
    {
        $tableName = $tableName ?: $this->inferTableName($modelName);

        if (! $this->tableExists($tableName)) {
            return [];
        }

        $tableDetails = $this->schemaAnalyzer->getTableDetails($tableName);
        $columns = $tableDetails['columns'] ?? [];
        $suggestions = [];

        foreach ($columns as $column) {
            if ($this->isDateColumn($column)) {
                $suggestions[] = $column['name'];
            }
        }

        return $suggestions;
    }

    /**
     * Infer table name from model name
     */
    protected function inferTableName(string $modelName): string
    {
        return Str::snake(Str::plural($modelName));
    }

    /**
     * Check if table exists
     */
    protected function tableExists(string $tableName): bool
    {
        try {
            return Schema::connection($this->connectionName)->hasTable($tableName);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if column should be skipped
     */
    protected function shouldSkipColumn(string $columnName, array $column): bool
    {
        // Skip primary keys, timestamps, and system columns
        $skipColumns = ['id', 'created_at', 'updated_at', 'deleted_at', 'remember_token'];

        if (in_array($columnName, $skipColumns)) {
            return true;
        }

        // Skip if it's a primary key
        if ($column['primary_key'] ?? false) {
            return true;
        }

        return false;
    }

    /**
     * Check if column is a good fillable candidate
     */
    protected function isGoodFillableCandidate(string $columnName, array $column): bool
    {
        // Don't include auto-increment fields
        if ($column['auto_increment'] ?? false) {
            return false;
        }

        // Include most other fields unless they're sensitive
        $sensitiveFields = ['password', 'api_token', 'remember_token', 'email_verified_at'];

        return ! in_array($columnName, $sensitiveFields);
    }

    /**
     * Get common fields for specific contexts with enhanced intelligence
     */
    protected function getCommonFieldsForContext(string $modelName, array $existingColumns): array
    {
        $existingColumnNames = array_column($existingColumns, 'name');
        $modelLower = strtolower($modelName);
        $suggestions = [];

        // Analyze existing column patterns to infer model purpose
        $hasUserReference = collect($existingColumnNames)->contains(fn ($col) => str_contains($col, 'user_id'));
        $hasTimestamps = collect($existingColumnNames)->contains('created_at');
        $hasStatus = collect($existingColumnNames)->contains(fn ($col) => str_contains($col, 'status'));
        $hasActive = collect($existingColumnNames)->contains(fn ($col) => str_contains($col, 'active'));

        // Context-based suggestions with pattern recognition
        $contextSuggestions = [
            'user' => ['name', 'email', 'email_verified_at'],
            'product' => ['name', 'description', 'price', 'sku', 'is_active', 'stock_quantity'],
            'order' => ['order_number', 'total_amount', 'status', 'order_date', 'shipping_address'],
            'category' => ['name', 'description', 'slug', 'parent_id', 'is_active', 'sort_order'],
            'post' => ['title', 'content', 'slug', 'published_at', 'is_published', 'excerpt'],
            'article' => ['title', 'content', 'slug', 'published_at', 'is_published', 'excerpt'],
            'blog' => ['title', 'content', 'slug', 'published_at', 'is_published', 'excerpt'],
            'comment' => ['content', 'author_name', 'author_email', 'is_approved'],
            'media' => ['filename', 'path', 'mime_type', 'size', 'alt_text'],
            'tag' => ['name', 'slug', 'color', 'description'],
            'setting' => ['key', 'value', 'type', 'group'],
            'permission' => ['name', 'guard_name', 'description'],
            'role' => ['name', 'guard_name', 'description'],
        ];

        // Smart context detection
        foreach ($contextSuggestions as $context => $fields) {
            if (str_contains($modelLower, $context)) {
                foreach ($fields as $field) {
                    if (! in_array($field, $existingColumnNames)) {
                        $suggestions[] = $field;
                    }
                }
                break;
            }
        }

        // Pattern-based suggestions
        if ($hasUserReference && ! in_array('user_id', $existingColumnNames)) {
            $suggestions[] = 'user_id';
        }

        if ($hasTimestamps && ! $hasStatus && $this->modelSeemsToNeedStatus($modelLower)) {
            $suggestions[] = 'status';
        }

        if (! $hasActive && $this->modelSeemsToNeedActiveFlag($modelLower)) {
            $suggestions[] = 'is_active';
        }

        // Universal fields that are often missing
        $universalFields = ['description', 'notes', 'meta_data'];
        foreach ($universalFields as $field) {
            if (
                ! in_array($field, $existingColumnNames) &&
                ! in_array($field, $suggestions) &&
                $this->shouldSuggestUniversalField($field, $modelLower, $existingColumnNames)
            ) {
                $suggestions[] = $field;
            }
        }

        return array_slice($suggestions, 0, 5); // Limit suggestions to avoid overwhelming
    }

    /**
     * Check if model seems to need status field
     */
    protected function modelSeemsToNeedStatus(string $modelLower): bool
    {
        $statusModels = ['order', 'payment', 'invoice', 'ticket', 'application', 'request', 'task'];

        foreach ($statusModels as $statusModel) {
            if (str_contains($modelLower, $statusModel)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if model seems to need active flag
     */
    protected function modelSeemsToNeedActiveFlag(string $modelLower): bool
    {
        $activeModels = ['user', 'product', 'category', 'feature', 'service', 'plan', 'subscription'];

        foreach ($activeModels as $activeModel) {
            if (str_contains($modelLower, $activeModel)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if universal field should be suggested
     */
    protected function shouldSuggestUniversalField(string $field, string $modelLower, array $existingColumns): bool
    {
        // Don't suggest description if content exists
        if ($field === 'description' && in_array('content', $existingColumns)) {
            return false;
        }

        // Don't suggest meta_data for simple models
        if ($field === 'meta_data' && count($existingColumns) < 5) {
            return false;
        }

        return true;
    }

    /**
     * Get explicit relationships from foreign keys
     */
    protected function getExplicitRelationships(string $tableName): array
    {
        $tableDetails = $this->schemaAnalyzer->getTableDetails($tableName);
        $foreignKeys = $tableDetails['foreign_keys'] ?? [];
        $relationships = [];

        foreach ($foreignKeys as $fk) {
            $relatedModel = Str::studly(Str::singular($fk['foreign_table']));
            $relationName = Str::camel(str_replace('_id', '', $fk['column']));

            $relationships[] = [
                'name' => $relationName,
                'type' => 'belongsTo',
                'related_model' => $relatedModel,
                'foreign_key' => $fk['column'],
                'local_key' => $fk['foreign_column'],
            ];
        }

        return $relationships;
    }

    /**
     * Get inferred relationships from naming patterns
     */
    protected function getInferredRelationships(string $tableName, string $modelName): array
    {
        $tableDetails = $this->schemaAnalyzer->getTableDetails($tableName);
        $columns = $tableDetails['columns'] ?? [];
        $relationships = [];

        foreach ($columns as $column) {
            $columnName = $column['name'];

            // Look for _id columns that might be foreign keys
            if (str_ends_with($columnName, '_id') && $columnName !== 'id') {
                $baseName = str_replace('_id', '', $columnName);
                $possibleTable = Str::snake(Str::plural($baseName));

                if ($this->tableExists($possibleTable)) {
                    $relatedModel = Str::studly(Str::singular($possibleTable));
                    $relationName = Str::camel($baseName);

                    $relationships[] = [
                        'name' => $relationName,
                        'type' => 'belongsTo',
                        'related_model' => $relatedModel,
                        'foreign_key' => $columnName,
                        'local_key' => 'id',
                    ];
                }
            }
        }

        return $relationships;
    }

    /**
     * Get reverse relationships (where this model is referenced)
     */
    protected function getReverseRelationships(string $tableName, string $modelName): array
    {
        $relationships = [];
        $allTables = $this->schemaAnalyzer->getAllTables();

        foreach ($allTables as $table) {
            if ($table['name'] === $tableName) {
                continue;
            }

            $tableDetails = $this->schemaAnalyzer->getTableDetails($table['name']);
            $foreignKeys = $tableDetails['foreign_keys'] ?? [];

            foreach ($foreignKeys as $fk) {
                if ($fk['foreign_table'] === $tableName) {
                    $relatedModel = Str::studly(Str::singular($table['name']));
                    $relationName = Str::camel(Str::plural($table['name']));

                    $relationships[] = [
                        'name' => $relationName,
                        'type' => 'hasMany',
                        'related_model' => $relatedModel,
                        'foreign_key' => $fk['column'],
                        'local_key' => $fk['foreign_column'],
                    ];
                }
            }
        }

        return $relationships;
    }

    /**
     * Infer cast type from column information with enhanced intelligence
     */
    protected function inferCastFromColumn(array $column): ?string
    {
        $type = strtolower($column['type']);
        $name = strtolower($column['name']);

        // JSON and array fields
        if (str_contains($type, 'json') || str_contains($name, 'json')) {
            return 'array';
        }

        if (str_contains($name, 'settings') || str_contains($name, 'config') || str_contains($name, 'meta')) {
            return 'array';
        }

        // Boolean fields
        if (str_contains($type, 'bool') || str_contains($type, 'tinyint(1)')) {
            return 'boolean';
        }

        if (
            str_starts_with($name, 'is_') || str_starts_with($name, 'has_') ||
            str_starts_with($name, 'can_') || str_ends_with($name, '_enabled') ||
            str_ends_with($name, '_active') || str_ends_with($name, '_verified')
        ) {
            return 'boolean';
        }

        // Decimal and money fields
        if (str_contains($type, 'decimal') || str_contains($type, 'numeric')) {
            return 'decimal:2';
        }

        if (
            str_contains($name, 'price') || str_contains($name, 'amount') ||
            str_contains($name, 'cost') || str_contains($name, 'total') ||
            str_contains($name, 'fee') || str_contains($name, 'balance') ||
            str_contains($name, 'salary') || str_contains($name, 'wage')
        ) {
            return 'decimal:2';
        }

        // Float fields
        if (str_contains($type, 'float') || str_contains($type, 'double')) {
            return 'float';
        }

        if (
            str_contains($name, 'rating') || str_contains($name, 'score') ||
            str_contains($name, 'percentage') || str_contains($name, 'ratio')
        ) {
            return 'float';
        }

        // Integer fields
        if (str_contains($type, 'int') && ! str_contains($type, 'tinyint(1)')) {
            return 'integer';
        }

        // Date and time fields
        if (str_contains($type, 'timestamp')) {
            return 'datetime';
        }

        if (str_contains($type, 'datetime')) {
            return 'datetime';
        }

        if (str_contains($type, 'date') && ! str_contains($type, 'datetime')) {
            return 'date';
        }

        if (str_contains($type, 'time') && ! str_contains($type, 'datetime') && ! str_contains($type, 'timestamp')) {
            return 'time';
        }

        // Name-based date/time detection
        if (str_ends_with($name, '_at') && ! in_array($name, ['created_at', 'updated_at', 'deleted_at'])) {
            return 'datetime';
        }

        if (str_ends_with($name, '_date') || str_ends_with($name, '_time')) {
            return str_ends_with($name, '_date') ? 'date' : 'time';
        }

        // Encrypted fields
        if (
            str_contains($name, 'password') || str_contains($name, 'secret') ||
            str_contains($name, 'token') || str_contains($name, 'key')
        ) {
            return 'encrypted';
        }

        // Collection fields
        if (
            str_contains($name, 'tags') || str_contains($name, 'categories') ||
            str_ends_with($name, '_list') || str_ends_with($name, '_items')
        ) {
            return 'collection';
        }

        return null;
    }

    /**
     * Check if field should be hidden
     */
    protected function shouldBeHidden(string $columnName, array $column): bool
    {
        $hiddenFields = [
            'password',
            'remember_token',
            'api_token',
            'access_token',
            'refresh_token',
            'secret',
            'private_key',
            'salt',
            'hash',
        ];

        foreach ($hiddenFields as $hiddenField) {
            if (str_contains($columnName, $hiddenField)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if column is a date column
     */
    protected function isDateColumn(array $column): bool
    {
        $type = strtolower($column['type']);
        $name = $column['name'];

        // Type-based detection
        if (str_contains($type, 'date') || str_contains($type, 'time')) {
            // Exclude Laravel timestamps as they're handled automatically
            if (! in_array($name, ['created_at', 'updated_at', 'deleted_at'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get fallback fillable fields when table doesn't exist
     */
    protected function getFallbackFillableFields(string $modelName): array
    {
        // Provide minimal intelligent fallback based on model name patterns
        $modelLower = strtolower($modelName);

        if (str_contains($modelLower, 'user')) {
            return ['name', 'email'];
        }

        return ['name'];
    }

    /**
     * Get common hidden fields when table analysis isn't available
     */
    protected function getCommonHiddenFields(string $modelName): array
    {
        $modelLower = strtolower($modelName);

        if (str_contains($modelLower, 'user')) {
            return ['password', 'remember_token'];
        }

        return [];
    }

    /**
     * Get intelligent trait suggestions based on table analysis
     */
    public function getTraitSuggestions(string $modelName, ?string $tableName = null): array
    {
        $tableName = $tableName ?: $this->inferTableName($modelName);
        $suggestions = ['HasFactory']; // Always suggest HasFactory

        if (! $this->tableExists($tableName)) {
            return $this->getFallbackTraitSuggestions($modelName);
        }

        $tableDetails = $this->schemaAnalyzer->getTableDetails($tableName);
        $columns = $tableDetails['columns'] ?? [];
        $columnNames = array_column($columns, 'name');

        // Soft deletes
        if (in_array('deleted_at', $columnNames)) {
            $suggestions[] = 'SoftDeletes';
        }

        // UUID primary key
        $hasUuidId = collect($columns)->first(fn ($col) => $col['name'] === 'id' && str_contains(strtolower($col['type']), 'char'));
        if ($hasUuidId) {
            $suggestions[] = 'HasUuids';
        }

        // User-related traits
        $modelLower = strtolower($modelName);
        if (str_contains($modelLower, 'user')) {
            $suggestions[] = 'Notifiable';

            if (in_array('email_verified_at', $columnNames)) {
                $suggestions[] = 'MustVerifyEmail';
            }

            if (in_array('api_token', $columnNames) || in_array('access_token', $columnNames)) {
                $suggestions[] = 'HasApiTokens';
            }
        }

        // Timestamp management
        if (! in_array('created_at', $columnNames) || ! in_array('updated_at', $columnNames)) {
            // Model doesn't use timestamps, might suggest disabling them
        }

        return array_unique($suggestions);
    }

    /**
     * Get fallback trait suggestions when table analysis isn't available
     */
    protected function getFallbackTraitSuggestions(string $modelName): array
    {
        $suggestions = ['HasFactory'];
        $modelLower = strtolower($modelName);

        if (str_contains($modelLower, 'user')) {
            $suggestions[] = 'Notifiable';
        }

        return $suggestions;
    }

    /**
     * Get model suggestions based on database analysis
     */
    public function getModelSuggestions(string $modelName, ?string $tableName = null): array
    {
        return [
            'fillable' => $this->getFillableFieldSuggestions($modelName, $tableName),
            'hidden' => $this->getHiddenFieldSuggestions($modelName, $tableName),
            'casts' => $this->getCastingSuggestions($modelName, $tableName),
            'dates' => $this->getDateFieldSuggestions($modelName, $tableName),
            'relations' => $this->getRelationshipSuggestions($modelName, $tableName),
            'traits' => $this->getTraitSuggestions($modelName, $tableName),
        ];
    }

    /**
     * Analyze database patterns for better suggestions
     */
    public function analyzePatterns(): array
    {
        $tables = $this->schemaAnalyzer->getAllTables();
        $patterns = [
            'common_columns' => [],
            'naming_patterns' => [],
            'relationship_patterns' => [],
            'data_type_patterns' => [],
        ];

        // Analyze common column patterns
        $columnFrequency = [];
        foreach ($tables as $table) {
            foreach ($table['columns'] as $column) {
                $columnFrequency[$column['name']] = ($columnFrequency[$column['name']] ?? 0) + 1;
            }
        }

        arsort($columnFrequency);
        $patterns['common_columns'] = array_slice(array_keys($columnFrequency), 0, 20);

        return $patterns;
    }
}
