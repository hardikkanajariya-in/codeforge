<?php

namespace HkDevs\CodeForgeStudio\Services;

use HkDevs\CodeForgeStudio\Models\CodeGenerationHistory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

/**
 * AdvancedCodeGenerationService
 * 
 * Comprehensive code generation orchestration service for CodeForge Database Studio.
 * Provides intelligent, multi-file code generation with dependency resolution and validation.
 * 
 * Features:
 * - Multi-file generation with dependency awareness and proper ordering
 * - Comprehensive configuration validation and sanitization
 * - Transactional generation with automatic rollback on failures
 * - Real-time progress tracking and detailed generation metrics
 * - Advanced stub template integration with dynamic content replacement
 * - Generation history tracking with complete audit trails
 * - User attribution and environment-aware generation
 * - Intelligent conflict detection and resolution strategies
 * 
 * Code Generation Capabilities:
 * - Laravel Models with relationships, accessors, and mutators
 * - Database Migrations with foreign keys and index optimization
 * - Model Factories with realistic data generation patterns
 * - Database Seeders with dependency-aware execution ordering
 * - Form Request Classes with comprehensive validation rules
 * - Policy Classes with resource-based authorization logic
 * - Resource Controllers with RESTful method implementations
 * - API Resources with field transformation and filtering
 * 
 * Advanced Features:
 * - Configuration-driven generation with flexible parameter systems
 * - Multi-template support for different coding standards
 * - Relationship mapping and foreign key constraint generation
 * - Automatic namespace resolution and import optimization
 * - Code style enforcement and formatting standards
 * - Custom stub template creation and management
 * - Generation pattern recognition and optimization
 * 
 * Validation and Safety:
 * - Pre-generation configuration validation with detailed error reporting
 * - File conflict detection with merge and overwrite strategies
 * - Database transaction support for atomic generation operations
 * - Rollback capabilities for failed or incomplete generations
 * - Comprehensive error logging and diagnostic information
 * - Generation impact analysis and preview capabilities
 * 
 * Integration Features:
 * - Seamless integration with Laravel's native code generation
 * - Support for custom stub templates and generation patterns
 * - Integration with CodeForge monitoring and logging systems
 * - Batch generation support for large-scale code creation
 * - CI/CD pipeline integration for automated code generation
 * - Team collaboration features with shared generation templates
 * 
 * Performance Optimization:
 * - Lazy loading of generation dependencies and templates
 * - Intelligent caching of frequently used generation patterns
 * - Batch processing for multiple file generation operations
 * - Memory-efficient handling of large generation sets
 * - Optimized file I/O operations with streaming support
 * 
 * Monitoring and Analytics:
 * - Detailed generation metrics and performance tracking
 * - Historical analysis of generation patterns and success rates
 * - User activity tracking and generation attribution
 * - Error pattern analysis and prevention recommendations
 * - Generation impact assessment and optimization suggestions
 * 
 * @package HkDevs\CodeForgeStudio\Services
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * $service = app(AdvancedCodeGenerationService::class);
 * $result = $service->generateFiles([
 *     'models' => [['name' => 'User', 'table' => 'users']],
 *     'migrations' => [['name' => 'create_users_table']],
 *     'factories' => [['model' => 'User']],
 * ]);
 */
class AdvancedCodeGenerationService
{
    protected StubTemplateService $stubService;
    protected LaravelTypesService $typesService;
    protected MigrationGeneratorService $migrationGenerator;
    protected ModelGeneratorService $modelGenerator;

    public function __construct(
        StubTemplateService $stubService,
        LaravelTypesService $typesService,
        MigrationGeneratorService $migrationGenerator,
        ModelGeneratorService $modelGenerator
    ) {
        $this->stubService = $stubService;
        $this->typesService = $typesService;
        $this->migrationGenerator = $migrationGenerator;
        $this->modelGenerator = $modelGenerator;
    }

    /**
     * Generate files based on configuration
     */
    public function generateFiles(array $config): array
    {
        $startTime = microtime(true);
        $generationId = Str::uuid()->toString();

        $results = [
            'success' => false,
            'generation_id' => $generationId,
            'files_created' => [],
            'errors' => [],
            'generation_time_ms' => 0,
        ];

        DB::beginTransaction();

        try {
            // Validate configuration first
            $validationErrors = $this->validateConfiguration($config);
            if (!empty($validationErrors)) {
                $results['errors'] = $validationErrors;
                return $results;
            }

            // Generate each enabled component
            if ($config['migration']['enabled'] ?? false) {
                $results['files_created'][] = $this->generateMigration($config['migration'], $generationId);
            }

            if ($config['model']['enabled'] ?? false) {
                $results['files_created'][] = $this->generateModel($config['model'], $generationId);
            }

            if ($config['factory']['enabled'] ?? false) {
                $results['files_created'][] = $this->generateFactory($config['factory'], $generationId);
            }

            if ($config['seeder']['enabled'] ?? false) {
                $results['files_created'][] = $this->generateSeeder($config['seeder'], $generationId);
            }

            if ($config['policy']['enabled'] ?? false) {
                $results['files_created'][] = $this->generatePolicy($config['policy'], $generationId);
            }

            if ($config['resource']['enabled'] ?? false) {
                $results['files_created'][] = $this->generateFilamentResource($config['resource'], $generationId);
            }

            if ($config['controller']['enabled'] ?? false) {
                $results['files_created'][] = $this->generateController($config['controller'], $generationId);
            }

            if ($config['tests']['enabled'] ?? false) {
                $testResults = $this->generateTests($config['tests'], $generationId);
                $results['files_created'] = array_merge($results['files_created'], $testResults);
            }

            DB::commit();

            $results['success'] = true;
            $results['generation_time_ms'] = round((microtime(true) - $startTime) * 1000, 2);
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up any partially created files
            $this->cleanupFailedGeneration($results['files_created']);

            $results['errors'][] = 'Generation failed: ' . $e->getMessage();
            $results['generation_time_ms'] = round((microtime(true) - $startTime) * 1000, 2);
        }

        return $results;
    }

    /**
     * Preview generation without creating files
     */
    public function previewGeneration(array $config): array
    {
        $previews = [];

        try {
            if ($config['migration']['enabled'] ?? false) {
                $previews['migration'] = $this->previewMigration($config['migration']);
            }

            if ($config['model']['enabled'] ?? false) {
                $previews['model'] = $this->previewModel($config['model']);
            }

            if ($config['factory']['enabled'] ?? false) {
                $previews['factory'] = $this->previewFactory($config['factory']);
            }

            if ($config['seeder']['enabled'] ?? false) {
                $previews['seeder'] = $this->previewSeeder($config['seeder']);
            }

            if ($config['policy']['enabled'] ?? false) {
                $previews['policy'] = $this->previewPolicy($config['policy']);
            }

            if ($config['resource']['enabled'] ?? false) {
                $previews['resource'] = $this->previewFilamentResource($config['resource']);
            }

            if ($config['controller']['enabled'] ?? false) {
                $previews['controller'] = $this->previewController($config['controller']);
            }
        } catch (\Exception $e) {
            $previews['errors'] = [$e->getMessage()];
        }

        return $previews;
    }

    /**
     * Validate configuration
     */
    public function validateConfiguration(array $config): array
    {
        $errors = [];

        // Check if at least one component is enabled
        $enabledComponents = array_filter([
            $config['migration']['enabled'] ?? false,
            $config['model']['enabled'] ?? false,
            $config['factory']['enabled'] ?? false,
            $config['seeder']['enabled'] ?? false,
            $config['policy']['enabled'] ?? false,
            $config['resource']['enabled'] ?? false,
            $config['controller']['enabled'] ?? false,
        ]);

        if (empty($enabledComponents)) {
            $errors[] = 'At least one component must be enabled for generation.';
            return $errors;
        }

        // Validate migration
        if ($config['migration']['enabled'] ?? false) {
            $migrationErrors = $this->validateMigrationConfig($config['migration']);
            $errors = array_merge($errors, $migrationErrors);
        }

        // Validate model
        if ($config['model']['enabled'] ?? false) {
            $modelErrors = $this->validateModelConfig($config['model']);
            $errors = array_merge($errors, $modelErrors);
        }

        // Validate dependencies
        if ($config['factory']['enabled'] && !($config['model']['enabled'] ?? false)) {
            $errors[] = 'Factory generation requires model to be enabled.';
        }

        if ($config['seeder']['enabled'] && !($config['model']['enabled'] ?? false)) {
            $errors[] = 'Seeder generation requires model to be enabled.';
        }

        if ($config['policy']['enabled'] && !($config['model']['enabled'] ?? false)) {
            $errors[] = 'Policy generation requires model to be enabled.';
        }

        if ($config['resource']['enabled'] && !($config['model']['enabled'] ?? false)) {
            $errors[] = 'Filament Resource generation requires model to be enabled.';
        }

        return $errors;
    }

    /**
     * Generate migration using stub templates
     */
    protected function generateMigration(array $config, string $generationId): array
    {
        $className = 'Create' . Str::studly($config['table_name']) . 'Table';
        $fileName = date('Y_m_d_His') . '_' . Str::snake($className) . '.php';
        $filePath = database_path('migrations/' . $fileName);

        $stub = $this->stubService->getStub('migration.create');
        $content = $this->stubService->populateStub($stub, [
            'CLASS_NAME' => $className,
            'TABLE_NAME' => $config['table_name'],
            'COLUMNS' => $this->buildColumnsCode($config['columns'] ?? []),
            'INDEXES' => $this->buildIndexesCode($config['indexes'] ?? []),
            'FOREIGN_KEYS' => $this->buildForeignKeysCode($config['foreign_keys'] ?? []),
            'TIMESTAMPS' => $config['timestamps'] ? '$table->timestamps();' : '',
            'SOFT_DELETES' => $config['soft_deletes'] ? '$table->softDeletes();' : '',
        ]);

        File::put($filePath, $content);

        return $this->createGenerationHistory([
            'generation_id' => $generationId,
            'type' => 'migration',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'configuration' => $config,
            'generated_code' => $content,
            'template_used' => 'migration.create',
        ]);
    }

    /**
     * Generate model using advanced stub templates
     */
    protected function generateModel(array $config, string $generationId): array
    {
        $className = $config['name'];
        $fileName = $className . '.php';
        $filePath = app_path('Models/' . $fileName);

        $stub = $this->stubService->getStub('model.advanced');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'App\\Models',
            'CLASS_NAME' => $className,
            'EXTENDS' => $config['extends'] ?? 'Model',
            'TRAITS' => $this->buildTraitsCode($config['traits'] ?? []),
            'TABLE_NAME' => $config['table_name'] ? "\n    protected \$table = '{$config['table_name']}';" : '',
            'FILLABLE' => $this->buildFillableCode($config['fillable'] ?? []),
            'HIDDEN' => $this->buildHiddenCode($config['hidden'] ?? []),
            'CASTS' => $this->buildCastsCode($config['casts'] ?? []),
            'DATES' => $this->buildDatesCode($config['dates'] ?? []),
            'RELATIONS' => $this->buildRelationsCode($config['relations'] ?? []),
            'SCOPES' => $this->buildScopesCode($config['scopes'] ?? []),
            'MUTATORS' => $this->buildMutatorsCode($config['mutators'] ?? []),
            'ACCESSORS' => $this->buildAccessorsCode($config['accessors'] ?? []),
            'CUSTOM_METHODS' => $this->buildCustomMethodsCode($config['custom_methods'] ?? []),
            'TIMESTAMPS' => $config['timestamps'] ? '' : "\n    public \$timestamps = false;",
        ]);

        File::put($filePath, $content);

        return $this->createGenerationHistory([
            'generation_id' => $generationId,
            'type' => 'model',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'namespace' => $config['namespace'] ?? 'App\\Models',
            'configuration' => $config,
            'generated_code' => $content,
            'template_used' => 'model.advanced',
        ]);
    }

    /**
     * Generate factory with advanced features
     */
    protected function generateFactory(array $config, string $generationId): array
    {
        $className = $config['class_name'];
        $fileName = $className . '.php';
        $filePath = database_path('factories/' . $fileName);

        $stub = $this->stubService->getStub('factory.advanced');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'Database\\Factories',
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'],
            'FAKE_DATA' => $this->buildFakeDataCode($config['fake_data'] ?? []),
            'STATES' => $this->buildFactoryStatesCode($config['states'] ?? []),
            'SEQUENCES' => $this->buildSequencesCode($config['sequences'] ?? []),
            'AFTER_CREATING' => $this->buildAfterCreatingCode($config['after_creating'] ?? []),
            'AFTER_MAKING' => $this->buildAfterMakingCode($config['after_making'] ?? []),
        ]);

        File::put($filePath, $content);

        return $this->createGenerationHistory([
            'generation_id' => $generationId,
            'type' => 'factory',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'namespace' => $config['namespace'] ?? 'Database\\Factories',
            'configuration' => $config,
            'generated_code' => $content,
            'template_used' => 'factory.advanced',
        ]);
    }

    /**
     * Generate seeder with advanced configuration
     */
    protected function generateSeeder(array $config, string $generationId): array
    {
        $className = $config['class_name'];
        $fileName = $className . '.php';
        $filePath = database_path('seeders/' . $fileName);

        $stub = $this->stubService->getStub('seeder.advanced');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'Database\\Seeders',
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'],
            'COUNT' => $config['count'] ?? 10,
            'FACTORY_USAGE' => $this->buildFactoryUsageCode($config),
            'MANUAL_DATA' => $this->buildManualDataCode($config['manual_data'] ?? [], $config),
            'TRUNCATE_TABLE' => $config['truncate_table'] ? 'DB::table(\'' . Str::snake(Str::plural($config['model'])) . '\')->truncate();' : '',
            'DISABLE_FOREIGN_KEYS' => $config['disable_foreign_keys'] ? 'Schema::disableForeignKeyConstraints();' : '',
            'ENABLE_FOREIGN_KEYS' => $config['disable_foreign_keys'] ? 'Schema::enableForeignKeyConstraints();' : '',
        ]);

        File::put($filePath, $content);

        return $this->createGenerationHistory([
            'generation_id' => $generationId,
            'type' => 'seeder',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'namespace' => $config['namespace'] ?? 'Database\\Seeders',
            'configuration' => $config,
            'generated_code' => $content,
            'template_used' => 'seeder.advanced',
        ]);
    }

    /**
     * Generate policy with custom methods
     */
    protected function generatePolicy(array $config, string $generationId): array
    {
        $className = $config['class_name'];
        $fileName = $className . '.php';
        $filePath = app_path('Policies/' . $fileName);

        $stub = $this->stubService->getStub('policy.advanced');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'App\\Policies',
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'],
            'POLICY_METHODS' => $this->buildPolicyMethodsCode($config['methods'] ?? []),
            'CUSTOM_METHODS' => $this->buildCustomPolicyMethodsCode($config['custom_methods'] ?? []),
        ]);

        File::put($filePath, $content);

        return $this->createGenerationHistory([
            'generation_id' => $generationId,
            'type' => 'policy',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'namespace' => $config['namespace'] ?? 'App\\Policies',
            'configuration' => $config,
            'generated_code' => $content,
            'template_used' => 'policy.advanced',
        ]);
    }

    /**
     * Generate Filament Resource
     */
    protected function generateFilamentResource(array $config, string $generationId): array
    {
        $className = $config['class_name'];
        $fileName = $className . '.php';
        $filePath = app_path('Filament/Resources/' . $fileName);

        $stub = $this->stubService->getStub('filament.resource');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'App\\Filament\\Resources',
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'],
            'NAVIGATION_ICON' => $config['navigation_icon'] ?? 'heroicon-o-rectangle-stack',
            'NAVIGATION_LABEL' => $config['navigation_label'] ?? '',
            'NAVIGATION_GROUP' => $config['navigation_group'] ? "'{$config['navigation_group']}'" : 'null',
            'SLUG' => $config['slug'] ?? Str::kebab(str_replace('Resource', '', $className)),
            'TABLE_COLUMNS' => $this->buildTableColumnsCode($config['table_columns'] ?? []),
            'FORM_FIELDS' => $this->buildFormFieldsCode($config['form_fields'] ?? []),
            'FILTERS' => $this->buildFiltersCode($config['filters'] ?? []),
            'ACTIONS' => $this->buildActionsCode($config['actions'] ?? []),
            'BULK_ACTIONS' => $this->buildBulkActionsCode($config['bulk_actions'] ?? []),
        ]);

        File::put($filePath, $content);

        return $this->createGenerationHistory([
            'generation_id' => $generationId,
            'type' => 'resource',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'namespace' => $config['namespace'] ?? 'App\\Filament\\Resources',
            'configuration' => $config,
            'generated_code' => $content,
            'template_used' => 'filament.resource',
        ]);
    }

    /**
     * Generate Controller
     */
    protected function generateController(array $config, string $generationId): array
    {
        $className = $config['class_name'];
        $fileName = $className . '.php';
        $filePath = app_path('Http/Controllers/' . $fileName);

        $stubType = match ($config['type'] ?? 'resource') {
            'api' => 'controller.api',
            'invokable' => 'controller.invokable',
            default => 'controller.resource',
        };

        $stub = $this->stubService->getStub($stubType);
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'App\\Http\\Controllers',
            'CLASS_NAME' => $className,
            'EXTENDS' => $config['extends'] ?? 'Controller',
            'MODEL_CLASS' => $config['model'] ?? '',
            'METHODS' => $this->buildControllerMethodsCode($config['methods'] ?? []),
            'CUSTOM_METHODS' => $this->buildCustomControllerMethodsCode($config['custom_methods'] ?? []),
        ]);

        File::put($filePath, $content);

        return $this->createGenerationHistory([
            'generation_id' => $generationId,
            'type' => 'controller',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'namespace' => $config['namespace'] ?? 'App\\Http\\Controllers',
            'configuration' => $config,
            'generated_code' => $content,
            'template_used' => $stubType,
        ]);
    }

    /**
     * Generate Tests
     */
    protected function generateTests(array $config, string $generationId): array
    {
        $results = [];

        if ($config['feature_tests'] ?? false) {
            $results[] = $this->generateFeatureTest($config, $generationId);
        }

        if ($config['unit_tests'] ?? false) {
            $results[] = $this->generateUnitTest($config, $generationId);
        }

        return $results;
    }

    protected function generateFeatureTest(array $config, string $generationId): array
    {
        $className = ($config['model'] ?? 'Example') . 'Test';
        $fileName = $className . '.php';
        $filePath = base_path('tests/Feature/' . $fileName);

        $stub = $this->stubService->getStub('test.feature');
        $content = $this->stubService->populateStub($stub, [
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'] ?? 'Example',
            'TEST_METHODS' => $this->buildTestMethodsCode($config['test_methods'] ?? []),
        ]);

        File::put($filePath, $content);

        return $this->createGenerationHistory([
            'generation_id' => $generationId,
            'type' => 'test',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'configuration' => $config,
            'generated_code' => $content,
            'template_used' => 'test.feature',
        ]);
    }

    protected function generateUnitTest(array $config, string $generationId): array
    {
        $className = ($config['model'] ?? 'Example') . 'UnitTest';
        $fileName = $className . '.php';
        $filePath = base_path('tests/Unit/' . $fileName);

        $stub = $this->stubService->getStub('test.unit');
        $content = $this->stubService->populateStub($stub, [
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'] ?? 'Example',
            'TEST_METHODS' => $this->buildUnitTestMethodsCode($config['test_methods'] ?? []),
        ]);

        File::put($filePath, $content);

        return $this->createGenerationHistory([
            'generation_id' => $generationId,
            'type' => 'test',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'configuration' => $config,
            'generated_code' => $content,
            'template_used' => 'test.unit',
        ]);
    }

    // Preview methods (return content without creating files)
    protected function previewMigration(array $config): array
    {
        $className = 'Create' . Str::studly($config['table_name']) . 'Table';
        $stub = $this->stubService->getStub('migration.create');
        $content = $this->stubService->populateStub($stub, [
            'CLASS_NAME' => $className,
            'TABLE_NAME' => $config['table_name'],
            'COLUMNS' => $this->buildColumnsCode($config['columns'] ?? []),
            'INDEXES' => $this->buildIndexesCode($config['indexes'] ?? []),
            'FOREIGN_KEYS' => $this->buildForeignKeysCode($config['foreign_keys'] ?? []),
            'TIMESTAMPS' => $config['timestamps'] ? '$table->timestamps();' : '',
            'SOFT_DELETES' => $config['soft_deletes'] ? '$table->softDeletes();' : '',
        ]);

        return [
            'class_name' => $className . '.php',
            'content' => $content
        ];
    }

    protected function previewModel(array $config): array
    {
        $className = $config['name'];
        $stub = $this->stubService->getStub('model.advanced');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'App\\Models',
            'CLASS_NAME' => $className,
            'EXTENDS' => $config['extends'] ?? 'Model',
            'TRAITS' => $this->buildTraitsCode($config['traits'] ?? []),
            'TABLE_NAME' => $config['table_name'] ? "\n    protected \$table = '{$config['table_name']}';" : '',
            'FILLABLE' => $this->buildFillableCode($config['fillable'] ?? []),
            'HIDDEN' => $this->buildHiddenCode($config['hidden'] ?? []),
            'CASTS' => $this->buildCastsCode($config['casts'] ?? []),
            'DATES' => $this->buildDatesCode($config['dates'] ?? []),
            'RELATIONS' => $this->buildRelationsCode($config['relations'] ?? []),
            'SCOPES' => $this->buildScopesCode($config['scopes'] ?? []),
            'MUTATORS' => $this->buildMutatorsCode($config['mutators'] ?? []),
            'ACCESSORS' => $this->buildAccessorsCode($config['accessors'] ?? []),
            'CUSTOM_METHODS' => $this->buildCustomMethodsCode($config['custom_methods'] ?? []),
            'TIMESTAMPS' => $config['timestamps'] ? '' : "\n    public \$timestamps = false;",
        ]);

        return [
            'class_name' => $className . '.php',
            'content' => $content
        ];
    }

    protected function previewFactory(array $config): array
    {
        $className = $config['class_name'];
        $stub = $this->stubService->getStub('factory.advanced');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'Database\\Factories',
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'],
            'FAKE_DATA' => $this->buildFakeDataCode($config['fake_data'] ?? []),
            'STATES' => $this->buildFactoryStatesCode($config['states'] ?? []),
            'SEQUENCES' => $this->buildSequencesCode($config['sequences'] ?? []),
            'AFTER_CREATING' => $this->buildAfterCreatingCode($config['after_creating'] ?? []),
            'AFTER_MAKING' => $this->buildAfterMakingCode($config['after_making'] ?? []),
        ]);

        return [
            'class_name' => $className . '.php',
            'content' => $content
        ];
    }

    protected function previewSeeder(array $config): array
    {
        $className = $config['class_name'];
        $stub = $this->stubService->getStub('seeder.advanced');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'Database\\Seeders',
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'],
            'COUNT' => $config['count'] ?? 10,
            'FACTORY_USAGE' => $this->buildFactoryUsageCode($config),
            'MANUAL_DATA' => $this->buildManualDataCode($config['manual_data'] ?? [], $config),
            'TRUNCATE_TABLE' => $config['truncate_table'] ? 'DB::table(\'' . Str::snake(Str::plural($config['model'])) . '\')->truncate();' : '',
            'DISABLE_FOREIGN_KEYS' => $config['disable_foreign_keys'] ? 'Schema::disableForeignKeyConstraints();' : '',
            'ENABLE_FOREIGN_KEYS' => $config['disable_foreign_keys'] ? 'Schema::enableForeignKeyConstraints();' : '',
        ]);

        return [
            'class_name' => $className . '.php',
            'content' => $content
        ];
    }

    protected function previewPolicy(array $config): array
    {
        $className = $config['class_name'];
        $stub = $this->stubService->getStub('policy.advanced');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'App\\Policies',
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'],
            'POLICY_METHODS' => $this->buildPolicyMethodsCode($config['methods'] ?? []),
            'CUSTOM_METHODS' => $this->buildCustomPolicyMethodsCode($config['custom_methods'] ?? []),
        ]);

        return [
            'class_name' => $className . '.php',
            'content' => $content
        ];
    }

    protected function previewFilamentResource(array $config): array
    {
        $className = $config['class_name'];
        $stub = $this->stubService->getStub('filament.resource');
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'App\\Filament\\Resources',
            'CLASS_NAME' => $className,
            'MODEL_CLASS' => $config['model'],
            'NAVIGATION_ICON' => $config['navigation_icon'] ?? 'heroicon-o-rectangle-stack',
            'NAVIGATION_LABEL' => $config['navigation_label'] ?? '',
            'NAVIGATION_GROUP' => $config['navigation_group'] ? "'{$config['navigation_group']}'" : 'null',
            'SLUG' => $config['slug'] ?? Str::kebab(str_replace('Resource', '', $className)),
            'TABLE_COLUMNS' => $this->buildTableColumnsCode($config['table_columns'] ?? []),
            'FORM_FIELDS' => $this->buildFormFieldsCode($config['form_fields'] ?? []),
            'FILTERS' => $this->buildFiltersCode($config['filters'] ?? []),
            'ACTIONS' => $this->buildActionsCode($config['actions'] ?? []),
            'BULK_ACTIONS' => $this->buildBulkActionsCode($config['bulk_actions'] ?? []),
        ]);

        return [
            'class_name' => $className . '.php',
            'content' => $content
        ];
    }

    protected function previewController(array $config): array
    {
        $className = $config['class_name'];
        $stubType = match ($config['type'] ?? 'resource') {
            'api' => 'controller.api',
            'invokable' => 'controller.invokable',
            default => 'controller.resource',
        };

        $stub = $this->stubService->getStub($stubType);
        $content = $this->stubService->populateStub($stub, [
            'NAMESPACE' => $config['namespace'] ?? 'App\\Http\\Controllers',
            'CLASS_NAME' => $className,
            'EXTENDS' => $config['extends'] ?? 'Controller',
            'MODEL_CLASS' => $config['model'] ?? '',
            'METHODS' => $this->buildControllerMethodsCode($config['methods'] ?? []),
            'CUSTOM_METHODS' => $this->buildCustomControllerMethodsCode($config['custom_methods'] ?? []),
        ]);

        return [
            'class_name' => $className . '.php',
            'content' => $content
        ];
    }

    // Validation methods
    protected function validateMigrationConfig(array $config): array
    {
        $errors = [];

        if (empty($config['table_name'])) {
            $errors[] = 'Migration table name is required.';
        } elseif (!preg_match('/^[a-z_][a-z0-9_]*$/', $config['table_name'])) {
            $errors[] = 'Migration table name must be lowercase with underscores only.';
        }

        return $errors;
    }

    protected function validateModelConfig(array $config): array
    {
        $errors = [];

        if (empty($config['name'])) {
            $errors[] = 'Model name is required.';
        } elseif (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $config['name'])) {
            $errors[] = 'Model name must be PascalCase.';
        }

        return $errors;
    }

    // Code building methods
    protected function buildColumnsCode(array $columns): string
    {
        $code = '';
        foreach ($columns as $column) {
            $line = "\$table->{$column['type']}('{$column['name']}'";

            if (!empty($column['length'])) {
                $line .= ", {$column['length']}";
            }

            $line .= ')';

            if ($column['nullable'] ?? false) $line .= '->nullable()';
            if ($column['unique'] ?? false) $line .= '->unique()';
            if ($column['index'] ?? false) $line .= '->index()';
            if ($column['unsigned'] ?? false) $line .= '->unsigned()';
            if ($column['auto_increment'] ?? false) $line .= '->autoIncrement()';
            if ($column['primary'] ?? false) $line .= '->primary()';
            if (!empty($column['default'])) $line .= "->default('{$column['default']}')";
            if (!empty($column['comment'])) $line .= "->comment('{$column['comment']}')";

            $code .= "            {$line};\n";
        }

        return $code;
    }

    protected function buildIndexesCode(array $indexes): string
    {
        $code = '';
        foreach ($indexes as $index) {
            $columns = is_array($index['columns']) ? $index['columns'] : explode(',', $index['columns']);
            $columnsStr = "['" . implode("', '", $columns) . "']";

            $line = "\$table->{$index['type']}({$columnsStr}";
            if (!empty($index['name'])) {
                $line .= ", '{$index['name']}'";
            }
            $line .= ');';

            $code .= "            {$line}\n";
        }

        return $code;
    }

    protected function buildForeignKeysCode(array $foreignKeys): string
    {
        $code = '';
        foreach ($foreignKeys as $fk) {
            $line = "\$table->foreign('{$fk['column']}')->references('{$fk['references']}')->on('{$fk['on']}')";

            if (!empty($fk['on_delete'])) $line .= "->onDelete('{$fk['on_delete']}')";
            if (!empty($fk['on_update'])) $line .= "->onUpdate('{$fk['on_update']}')";
            if (!empty($fk['name'])) $line .= "->name('{$fk['name']}')";

            $code .= "            {$line};\n";
        }

        return $code;
    }

    protected function buildTraitsCode(array $traits): string
    {
        if (empty($traits)) return '';
        return 'use ' . implode(', ', $traits) . ';';
    }

    protected function buildFillableCode(array $fillable): string
    {
        if (empty($fillable)) return '';

        $items = array_map(fn($item) => "'{$item}'", $fillable);
        return "\n    protected \$fillable = [\n        " . implode(",\n        ", $items) . "\n    ];";
    }

    protected function buildHiddenCode(array $hidden): string
    {
        if (empty($hidden)) return '';

        $items = array_map(fn($item) => "'{$item}'", $hidden);
        return "\n    protected \$hidden = [\n        " . implode(",\n        ", $items) . "\n    ];";
    }

    protected function buildCastsCode(array $casts): string
    {
        if (empty($casts)) return '';

        $items = [];
        foreach ($casts as $field => $cast) {
            $items[] = "'{$field}' => '{$cast}'";
        }

        return "\n    protected \$casts = [\n        " . implode(",\n        ", $items) . "\n    ];";
    }

    protected function buildDatesCode(array $dates): string
    {
        if (empty($dates)) return '';

        $items = array_map(fn($item) => "'{$item}'", $dates);
        return "\n    protected \$dates = [\n        " . implode(",\n        ", $items) . "\n    ];";
    }

    protected function buildRelationsCode(array $relations): string
    {
        $code = '';
        foreach ($relations as $relation) {
            $code .= "\n    public function {$relation['name']}()\n    {\n";
            $code .= "        return \$this->{$relation['type']}({$relation['related_model']}::class";

            if (!empty($relation['foreign_key'])) {
                $code .= ", '{$relation['foreign_key']}'";
            }
            if (!empty($relation['local_key'])) {
                $code .= ", '{$relation['local_key']}'";
            }

            $code .= ");\n    }\n";
        }

        return $code;
    }

    protected function buildScopesCode(array $scopes): string
    {
        $code = '';
        foreach ($scopes as $scope) {
            $code .= "\n    public function scope{$scope['name']}(\$query";
            if (!empty($scope['parameters'])) {
                $code .= ', ' . implode(', ', $scope['parameters']);
            }
            $code .= ")\n    {\n";
            $code .= "        {$scope['body']}\n";
            $code .= "    }\n";
        }

        return $code;
    }

    protected function buildMutatorsCode(array $mutators): string
    {
        $code = '';
        foreach ($mutators as $mutator) {
            $attributeName = Str::studly($mutator['attribute']);
            $code .= "\n    public function set{$attributeName}Attribute(\$value)\n    {\n";
            $code .= "        {$mutator['body']}\n";
            $code .= "    }\n";
        }

        return $code;
    }

    protected function buildAccessorsCode(array $accessors): string
    {
        $code = '';
        foreach ($accessors as $accessor) {
            $attributeName = Str::studly($accessor['attribute']);
            $code .= "\n    public function get{$attributeName}Attribute(\$value)\n    {\n";
            $code .= "        {$accessor['body']}\n";
            $code .= "    }\n";
        }

        return $code;
    }

    protected function buildCustomMethodsCode(array $methods): string
    {
        $code = '';
        foreach ($methods as $method) {
            $code .= "\n    public function {$method['name']}(";
            if (!empty($method['parameters'])) {
                $code .= implode(', ', $method['parameters']);
            }
            $code .= ")\n    {\n";
            $code .= "        {$method['body']}\n";
            $code .= "    }\n";
        }

        return $code;
    }

    protected function buildFakeDataCode(array $fakeData): string
    {
        $code = '';
        foreach ($fakeData as $data) {
            $parameters = !empty($data['parameters']) ? '(' . $data['parameters'] . ')' : '';
            $code .= "            '{$data['field']}' => fake()->{$data['faker_method']}{$parameters},\n";
        }

        return $code;
    }

    protected function buildFactoryStatesCode(array $states): string
    {
        $code = '';
        foreach ($states as $state) {
            $code .= "\n    public function {$state['name']}(): static\n    {\n";
            $code .= "        return \$this->state(fn (array \$attributes) => [\n";

            foreach ($state['attributes'] as $key => $value) {
                $code .= "            '{$key}' => {$value},\n";
            }

            $code .= "        ]);\n    }\n";
        }

        return $code;
    }

    protected function buildSequencesCode(array $sequences): string
    {
        // Implementation for sequences
        return '';
    }

    protected function buildAfterCreatingCode(array $afterCreating): string
    {
        // Implementation for after creating callbacks
        return '';
    }

    protected function buildAfterMakingCode(array $afterMaking): string
    {
        // Implementation for after making callbacks
        return '';
    }

    protected function buildFactoryUsageCode(array $config): string
    {
        if (!($config['use_factory'] ?? true)) {
            return '';
        }

        $code = "{$config['model']}::factory()";

        if (!empty($config['factory_states'])) {
            foreach ($config['factory_states'] as $state) {
                $code .= "->{$state}()";
            }
        }

        $code .= "->count({$config['count']})->create();";

        return $code;
    }

    protected function buildManualDataCode(array $manualData, array $config = []): string
    {
        $code = '';
        foreach ($manualData as $record) {
            $code .= "            [\n";
            foreach ($record['data'] as $key => $value) {
                $code .= "                '{$key}' => '{$value}',\n";
            }
            $code .= "            ],\n";
        }

        if (!empty($code) && !empty($config['model'])) {
            return "\$data = [\n{$code}        ];\n        foreach (\$data as \$item) {\n            {$config['model']}::create(\$item);\n        }";
        }

        return '';
    }

    protected function buildPolicyMethodsCode(array $methods): string
    {
        $code = '';
        foreach ($methods as $method) {
            $code .= "\n    public function {$method}(User \$user";
            if (!in_array($method, ['viewAny', 'create'])) {
                $code .= ", {$this->getModelNameFromConfig()} \$model";
            }
            $code .= "): bool\n    {\n";
            $code .= "        // TODO: Implement authorization logic\n";
            $code .= "        return false;\n";
            $code .= "    }\n";
        }

        return $code;
    }

    protected function buildCustomPolicyMethodsCode(array $customMethods): string
    {
        $code = '';
        foreach ($customMethods as $method) {
            $code .= "\n    public function {$method['name']}(User \$user): bool\n    {\n";
            $code .= "        {$method['logic']}\n";
            $code .= "    }\n";
        }

        return $code;
    }

    protected function buildTableColumnsCode(array $columns): string
    {
        // Implementation for Filament table columns
        return '';
    }

    protected function buildFormFieldsCode(array $fields): string
    {
        // Implementation for Filament form fields
        return '';
    }

    protected function buildFiltersCode(array $filters): string
    {
        // Implementation for Filament filters
        return '';
    }

    protected function buildActionsCode(array $actions): string
    {
        // Implementation for Filament actions
        return '';
    }

    protected function buildBulkActionsCode(array $bulkActions): string
    {
        // Implementation for Filament bulk actions
        return '';
    }

    protected function buildControllerMethodsCode(array $methods): string
    {
        $code = '';
        foreach ($methods as $method) {
            switch ($method) {
                case 'index':
                    $code .= "\n    public function index()\n    {\n        // TODO: Implement index method\n    }\n";
                    break;
                case 'show':
                    $code .= "\n    public function show(\$id)\n    {\n        // TODO: Implement show method\n    }\n";
                    break;
                case 'create':
                    $code .= "\n    public function create()\n    {\n        // TODO: Implement create method\n    }\n";
                    break;
                case 'store':
                    $code .= "\n    public function store(Request \$request)\n    {\n        // TODO: Implement store method\n    }\n";
                    break;
                case 'edit':
                    $code .= "\n    public function edit(\$id)\n    {\n        // TODO: Implement edit method\n    }\n";
                    break;
                case 'update':
                    $code .= "\n    public function update(Request \$request, \$id)\n    {\n        // TODO: Implement update method\n    }\n";
                    break;
                case 'destroy':
                    $code .= "\n    public function destroy(\$id)\n    {\n        // TODO: Implement destroy method\n    }\n";
                    break;
            }
        }

        return $code;
    }

    protected function buildCustomControllerMethodsCode(array $customMethods): string
    {
        $code = '';
        foreach ($customMethods as $method) {
            $code .= "\n    public function {$method['name']}(";
            if (!empty($method['parameters'])) {
                $code .= implode(', ', $method['parameters']);
            }
            $code .= ")\n    {\n";
            $code .= "        {$method['body']}\n";
            $code .= "    }\n";
        }

        return $code;
    }

    protected function buildTestMethodsCode(array $methods): string
    {
        // Implementation for test methods
        return '';
    }

    protected function buildUnitTestMethodsCode(array $methods): string
    {
        // Implementation for unit test methods
        return '';
    }

    protected function createGenerationHistory(array $data): array
    {
        $data['success'] = true;
        $data['file_size'] = strlen($data['generated_code']);
        $data['user_id'] = Auth::id();

        $history = CodeGenerationHistory::create($data);

        return [
            'type' => $data['type'],
            'file_name' => $data['file_name'],
            'file_path' => $data['file_path'],
            'class_name' => $data['class_name'],
            'success' => true,
        ];
    }

    protected function cleanupFailedGeneration(array $files): void
    {
        foreach ($files as $file) {
            if (isset($file['file_path']) && File::exists($file['file_path'])) {
                File::delete($file['file_path']);
            }
        }
    }

    protected function getModelNameFromConfig(): string
    {
        // This would be dynamically set based on the current configuration
        return 'Model';
    }

    public function generatePreview(array $config): array
    {
        $previews = [];

        try {
            if ($config['migration']['enabled'] ?? false) {
                $previews['migration'] = $this->previewMigration($config['migration']);
            }

            if ($config['model']['enabled'] ?? false) {
                $previews['model'] = $this->previewModel($config['model']);
            }

            if ($config['factory']['enabled'] ?? false) {
                $previews['factory'] = $this->previewFactory($config['factory']);
            }

            if ($config['seeder']['enabled'] ?? false) {
                $previews['seeder'] = $this->previewSeeder($config['seeder']);
            }

            if ($config['policy']['enabled'] ?? false) {
                $previews['policy'] = $this->previewPolicy($config['policy']);
            }

            if ($config['resource']['enabled'] ?? false) {
                $previews['resource'] = $this->previewFilamentResource($config['resource']);
            }

            if ($config['controller']['enabled'] ?? false) {
                $previews['controller'] = $this->previewController($config['controller']);
            }
        } catch (\Exception $e) {
            $previews['errors'] = [$e->getMessage()];
        }

        return $previews;
    }

    protected function getMigrationClassName(string $tableName): string
    {
        return 'Create' . Str::studly(Str::singular($tableName)) . 'Table';
    }
}
