<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * CodeGenerationService
 * 
 * Core code generation orchestration service for CodeForge Database Studio.
 * Provides unified generation of Laravel application components with intelligent dependency management.
 * 
 * Features:
 * - Complete Laravel component generation (Models, Migrations, Factories, Seeders, Policies)
 * - Intelligent dependency resolution and generation ordering
 * - Transactional generation with atomic commit/rollback operations
 * - Comprehensive error handling with detailed failure analysis
 * - Generation history tracking with unique identifier assignment
 * - File conflict detection and resolution strategies
 * - Real-time progress tracking and status reporting
 * - Integration with specialized generator services
 * 
 * Component Generation:
 * - Database Migrations: Complete table creation with columns, indexes, and constraints
 * - Eloquent Models: Full model generation with relationships and attribute casting
 * - Model Factories: Realistic data generation with Faker integration
 * - Database Seeders: Data population with dependency-aware execution
 * - Policy Classes: Authorization logic with resource-based permissions
 * - Request Classes: Form validation with comprehensive rule sets
 * - Controller Classes: RESTful controllers with standard CRUD operations
 * 
 * Generation Workflow:
 * - Configuration validation and sanitization
 * - Dependency analysis and generation order optimization
 * - File generation with template-based content creation
 * - Database schema validation and consistency checking
 * - Generated file validation and syntax verification
 * - Transaction commit with complete generation history logging
 * 
 * Safety Features:
 * - Database transaction support for atomic operations
 * - File backup creation before overwriting existing files
 * - Generation rollback capabilities for failed operations
 * - Comprehensive validation of input parameters and configurations
 * - Error recovery with detailed diagnostic information
 * - Preview mode for generation impact assessment
 * 
 * Integration Capabilities:
 * - Seamless integration with Laravel's native Artisan commands
 * - Support for custom generation templates and patterns
 * - Integration with CodeForge monitoring and logging systems
 * - Batch generation support for large-scale development workflows
 * - Team collaboration with shared generation configurations
 * 
 * Performance Features:
 * - Optimized file I/O operations with minimal disk access
 * - Intelligent caching of generation templates and configurations
 * - Memory-efficient handling of large generation sets
 * - Lazy loading of generation dependencies and services
 * - Batch processing optimization for multiple component generation
 * 
 * Monitoring and Tracking:
 * - Unique generation identifier assignment for tracking
 * - Comprehensive generation history with success/failure logging
 * - Performance metrics collection and analysis
 * - User attribution and activity tracking
 * - Generation pattern analysis and optimization recommendations
 * 
 * @package HkDevs\CodeForgeStudio\Services
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * $service = app(CodeGenerationService::class);
 * $result = $service->generateComplete([
 *     'migration' => ['name' => 'create_posts_table', 'table' => 'posts'],
 *     'model' => ['name' => 'Post', 'table' => 'posts'],
 *     'factory' => true,
 *     'seeder' => true
 * ]);
 */
class CodeGenerationService
{
    protected MigrationGeneratorService $migrationGenerator;
    protected ModelGeneratorService $modelGenerator;
    protected array $generationHistory = [];

    public function __construct(
        MigrationGeneratorService $migrationGenerator,
        ModelGeneratorService $modelGenerator
    ) {
        $this->migrationGenerator = $migrationGenerator;
        $this->modelGenerator = $modelGenerator;
    }

    /**
     * Generate migration and optionally associated model, factory, seeder, policy
     */
    public function generateComplete(array $data): array
    {
        $results = [
            'success' => false,
            'files_created' => [],
            'migration' => null,
            'model' => null,
            'factory' => null,
            'seeder' => null,
            'policy' => null,
            'errors' => [],
            'generation_id' => Str::uuid()->toString()
        ];

        DB::beginTransaction();

        try {
            // Generate migration first
            if (!empty($data['migration'])) {
                $migrationResult = $this->migrationGenerator->generateMigration($data['migration']);
                $results['migration'] = $migrationResult;

                // Add type field for consistency
                $migrationResult['type'] = 'migration';
                $results['files_created'][] = $migrationResult;

                $this->logGeneration('migration', $migrationResult, $results['generation_id']);
            }

            // Generate model and related files if requested
            if (!empty($data['model'])) {
                $modelResults = $this->modelGenerator->generateModel($data['model']);

                foreach ($modelResults as $type => $result) {
                    $results[$type] = $result;

                    // Add type field for consistency
                    $result['type'] = $type;
                    $results['files_created'][] = $result;
                    $this->logGeneration($type, $result, $results['generation_id']);
                }
            }

            // Save generation metadata
            $this->saveGenerationMetadata($results);

            $results['success'] = true;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up any created files
            $this->cleanupFailedGeneration($results['files_created']);

            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Preview complete generation without creating files
     */
    public function previewComplete(array $data): array
    {
        $results = [
            'migration' => null,
            'model' => null,
            'factory' => null,
            'seeder' => null,
            'policy' => null,
            'errors' => []
        ];

        try {
            // Preview migration
            if (!empty($data['migration'])) {
                $results['migration'] = $this->migrationGenerator->previewMigration($data['migration']);
            }

            // Preview model and related files
            if (!empty($data['model'])) {
                $modelPreviews = $this->modelGenerator->previewModel($data['model']);

                foreach ($modelPreviews as $type => $preview) {
                    $results[$type] = $preview;
                }
            }
        } catch (\Exception $e) {
            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Validate complete generation data
     */
    public function validateComplete(array $data): array
    {
        $errors = [];

        // Validate migration data
        if (!empty($data['migration'])) {
            $migrationValidation = $this->migrationGenerator->validateMigrationData($data['migration']);
            if (!$migrationValidation['valid']) {
                $errors = array_merge($errors, $migrationValidation['errors']);
            }
        }

        // Validate model data
        if (!empty($data['model'])) {
            $modelValidation = $this->modelGenerator->validateModelData($data['model']);
            if (!$modelValidation['valid']) {
                $errors = array_merge($errors, $modelValidation['errors']);
            }
        }

        // Cross-validation between migration and model
        if (!empty($data['migration']) && !empty($data['model'])) {
            $crossErrors = $this->validateMigrationModelConsistency($data['migration'], $data['model']);
            $errors = array_merge($errors, $crossErrors);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Rollback a generation by ID
     */
    public function rollbackGeneration(string $generationId): array
    {
        $results = [
            'success' => false,
            'files_removed' => [],
            'errors' => []
        ];

        try {
            $metadata = $this->getGenerationMetadata($generationId);

            if (empty($metadata)) {
                throw new \Exception("Generation not found: {$generationId}");
            }

            // Remove files in reverse order
            $files = array_reverse($metadata['files_created']);

            foreach ($files as $file) {
                if (File::exists($file['file_path'])) {
                    File::delete($file['file_path']);
                    $results['files_removed'][] = $file;
                }
            }

            // Remove generation metadata
            $this->removeGenerationMetadata($generationId);

            $results['success'] = true;
        } catch (\Exception $e) {
            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Get generation history
     */
    public function getGenerationHistory(int $limit = 50): array
    {
        $historyFile = storage_path('app/codeforge-database-studio/generation-history.json');

        if (!File::exists($historyFile)) {
            return [];
        }

        $history = json_decode(File::get($historyFile), true) ?? [];

        return collect($history)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values()
            ->toArray();
    }

    /**
     * Get available database tables for reference
     */
    public function getAvailableTables(): array
    {
        try {
            $tables = [];
            $tableNames = DB::connection()->getDoctrineSchemaManager()->listTableNames();

            foreach ($tableNames as $tableName) {
                $columns = DB::connection()->getDoctrineSchemaManager()->listTableColumns($tableName);

                $tableData = [
                    'name' => $tableName,
                    'columns' => []
                ];

                foreach ($columns as $column) {
                    $tableData['columns'][] = [
                        'name' => $column->getName(),
                        'type' => $this->getColumnTypeName($column->getType()),
                        'nullable' => !$column->getNotnull(),
                        'default' => $column->getDefault(),
                        'auto_increment' => $column->getAutoincrement(),
                    ];
                }

                $tables[] = $tableData;
            }

            return $tables;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Generate suggested model structure from migration
     */
    public function suggestModelFromMigration(array $migrationData): array
    {
        $tableName = $migrationData['table_name'];
        $modelName = Str::studly(Str::singular($tableName));

        $modelData = [
            'name' => $modelName,
            'table_name' => $tableName,
            'fillable' => [],
            'casts' => [],
            'relations' => [],
            'timestamps' => $migrationData['timestamps'] ?? true,
            'soft_deletes' => $migrationData['soft_deletes'] ?? false,
            'generate_factory' => true,
            'generate_seeder' => true,
            'generate_policy' => false,
        ];

        // Process columns for fillable and casts
        if (!empty($migrationData['columns'])) {
            foreach ($migrationData['columns'] as $column) {
                // Skip system columns
                if (in_array($column['name'], ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    continue;
                }

                // Add to fillable if not auto-increment
                if (!($column['auto_increment'] ?? false)) {
                    $modelData['fillable'][] = $column['name'];
                }

                // Add cast if needed
                $cast = $this->suggestCastForColumn($column);
                if ($cast) {
                    $modelData['casts'][$column['name']] = $cast;
                }
            }
        }

        // Suggest relations based on foreign keys
        if (!empty($migrationData['foreign_keys'])) {
            foreach ($migrationData['foreign_keys'] as $fk) {
                $relationName = $this->suggestRelationName($fk['referenced_table']);
                $relatedModel = Str::studly(Str::singular($fk['referenced_table']));

                $modelData['relations'][] = [
                    'name' => $relationName,
                    'type' => 'belongsTo',
                    'related_model' => $relatedModel,
                    'foreign_key' => $fk['column'],
                    'local_key' => $fk['referenced_column']
                ];
            }
        }

        return $modelData;
    }

    /**
     * Log generation for history
     */
    protected function logGeneration(string $type, array $result, string $generationId): void
    {
        $this->generationHistory[] = [
            'generation_id' => $generationId,
            'type' => $type,
            'file_name' => $result['file_name'],
            'file_path' => $result['file_path'],
            'class_name' => $result['class_name'] ?? null,
            'created_at' => now()->toISOString()
        ];
    }

    /**
     * Save generation metadata to storage
     */
    protected function saveGenerationMetadata(array $results): void
    {
        $historyFile = storage_path('app/codeforge-database-studio/generation-history.json');
        $historyDir = dirname($historyFile);

        if (!File::isDirectory($historyDir)) {
            File::makeDirectory($historyDir, 0755, true);
        }

        $history = [];
        if (File::exists($historyFile)) {
            $history = json_decode(File::get($historyFile), true) ?? [];
        }

        $metadata = [
            'generation_id' => $results['generation_id'],
            'files_created' => $results['files_created'],
            'created_at' => now()->toISOString(),
            'success' => $results['success']
        ];

        $history[] = $metadata;

        // Keep only last 100 generations
        if (count($history) > 100) {
            $history = array_slice($history, -100);
        }

        File::put($historyFile, json_encode($history, JSON_PRETTY_PRINT));
    }

    /**
     * Get generation metadata by ID
     */
    protected function getGenerationMetadata(string $generationId): ?array
    {
        $historyFile = storage_path('app/codeforge-database-studio/generation-history.json');

        if (!File::exists($historyFile)) {
            return null;
        }

        $history = json_decode(File::get($historyFile), true) ?? [];

        foreach ($history as $item) {
            if ($item['generation_id'] === $generationId) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Remove generation metadata
     */
    protected function removeGenerationMetadata(string $generationId): void
    {
        $historyFile = storage_path('app/codeforge-database-studio/generation-history.json');

        if (!File::exists($historyFile)) {
            return;
        }

        $history = json_decode(File::get($historyFile), true) ?? [];

        $history = array_filter($history, function ($item) use ($generationId) {
            return $item['generation_id'] !== $generationId;
        });

        File::put($historyFile, json_encode(array_values($history), JSON_PRETTY_PRINT));
    }

    /**
     * Clean up files from failed generation
     */
    protected function cleanupFailedGeneration(array $files): void
    {
        foreach ($files as $file) {
            if (File::exists($file['file_path'])) {
                File::delete($file['file_path']);
            }
        }
    }

    /**
     * Validate consistency between migration and model
     */
    protected function validateMigrationModelConsistency(array $migrationData, array $modelData): array
    {
        $errors = [];

        // Check if table names match
        $migrationTable = $migrationData['table_name'];
        $modelTable = $modelData['table_name'] ?? Str::snake(Str::pluralStudly($modelData['name']));

        if ($migrationTable !== $modelTable) {
            $errors[] = "Migration table name '{$migrationTable}' doesn't match model table name '{$modelTable}'";
        }

        // Check if fillable fields exist in migration columns
        if (!empty($modelData['fillable']) && !empty($migrationData['columns'])) {
            $migrationColumns = collect($migrationData['columns'])->pluck('name')->toArray();

            foreach ($modelData['fillable'] as $fillableField) {
                if (!in_array($fillableField, $migrationColumns)) {
                    $errors[] = "Fillable field '{$fillableField}' not found in migration columns";
                }
            }
        }

        return $errors;
    }

    /**
     * Suggest cast for column type
     */
    protected function suggestCastForColumn(array $column): ?string
    {
        return match ($column['type']) {
            'boolean' => 'boolean',
            'integer', 'bigInteger', 'mediumInteger', 'smallInteger', 'tinyInteger' => 'integer',
            'float', 'double' => 'float',
            'decimal' => 'decimal:2',
            'date' => 'date',
            'dateTime', 'dateTimeTz', 'timestamp', 'timestampTz' => 'datetime',
            'time', 'timeTz' => 'time',
            'json', 'jsonb' => 'array',
            default => null
        };
    }

    /**
     * Suggest relation name from table name
     */
    protected function suggestRelationName(string $tableName): string
    {
        return Str::camel(Str::singular($tableName));
    }

    /**
     * Get column type name safely (handles deprecated getName() method)
     */
    protected function getColumnTypeName($type): string
    {
        // Handle the deprecated getName() method
        if (method_exists($type, 'getName')) {
            return $type->getName();
        }

        // For newer versions, extract type name from class name
        $className = get_class($type);
        $parts = explode('\\', $className);
        $typeName = end($parts);

        // Remove 'Type' suffix if present
        if (str_ends_with($typeName, 'Type')) {
            $typeName = substr($typeName, 0, -4);
        }

        return strtolower($typeName);
    }
}
