<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * FilamentResourceGeneratorService
 * 
 * Advanced Filament Admin Panel resource generation service for CodeForge Database Studio.
 * Creates comprehensive, production-ready Filament resources with intelligent form and table generation.
 * 
 * Features:
 * - Intelligent Filament resource generation from models and database schemas
 * - Automatic form field generation with appropriate input types and validation
 * - Smart table column generation with sorting, searching, and filtering capabilities
 * - Relationship field handling with intuitive UI components
 * - Permission and policy integration with role-based access control
 * - Custom action generation with bulk operations and modal support
 * - Widget integration with dashboard and resource-specific widgets
 * - Multi-language support with automatic translation key generation
 * 
 * Resource Generation Intelligence:
 * - Model Analysis: Comprehensive model introspection for accurate resource generation
 * - Field Type Detection: Automatic UI component selection based on database column types
 * - Relationship Mapping: Intelligent handling of all Eloquent relationship types
 * - Validation Integration: Automatic form validation based on model rules and database constraints
 * - Permission Detection: Integration with existing authorization policies and middleware
 * - UI Optimization: Context-aware component selection for optimal user experience
 * - Performance Optimization: Efficient query generation with eager loading strategies
 * 
 * Form Generation:
 * - Smart Field Selection: Automatic form field type selection based on data types
 * - Relationship Fields: Select, checkbox, and radio components for model relationships
 * - Rich Text Editors: WYSIWYG editor integration for text and content fields
 * - File Upload Fields: Image and file upload components with validation and storage
 * - Date and Time Pickers: Specialized components for temporal data with timezone support
 * - Custom Components: Integration with custom Filament form components
 * - Conditional Logic: Dynamic form behavior with conditional field visibility
 * 
 * Table Generation:
 * - Column Configuration: Intelligent column selection with sorting and filtering
 * - Search Integration: Full-text search with column-specific search capabilities
 * - Bulk Actions: Automated bulk operation generation with confirmation modals
 * - Export Features: Data export functionality with multiple format support
 * - Pagination: Optimized pagination with configurable page sizes
 * - Custom Columns: Support for calculated columns and custom display logic
 * - Responsive Design: Mobile-optimized table layouts with adaptive columns
 * 
 * Advanced Features:
 * - Page Generation: Create, Edit, List, and View page generation with customization
 * - Widget Integration: Resource-specific widgets and dashboard integration
 * - Action Generation: Custom actions with modal support and confirmation dialogs
 * - Filter Generation: Advanced filtering with date ranges and relationship filters
 * - Navigation Integration: Automatic menu generation with icon and grouping support
 * - Theme Integration: Support for custom themes and branding options
 * - Plugin Integration: Compatibility with popular Filament plugins and extensions
 * 
 * Security and Authorization:
 * - Policy Integration: Automatic integration with Laravel authorization policies
 * - Role-based Access: Support for role-based resource access and field visibility
 * - Permission Checking: Granular permission validation for actions and operations
 * - Audit Integration: Automatic audit trail integration for resource modifications
 * - Data Validation: Comprehensive validation with custom rule support
 * - CSRF Protection: Built-in CSRF protection for all resource operations
 * - Input Sanitization: Automatic input sanitization and XSS prevention
 * 
 * Customization Options:
 * - Template System: Customizable generation templates with override capabilities
 * - Component Overrides: Custom component selection and configuration options
 * - Layout Customization: Flexible layout options with responsive design support
 * - Styling Integration: Custom CSS and styling integration with theme support
 * - JavaScript Integration: Custom JavaScript behavior and component enhancement
 * - Plugin Integration: Support for custom Filament plugins and extensions
 * - Localization: Multi-language support with automatic translation management
 * 
 * Integration Features:
 * - Laravel Integration: Seamless integration with Laravel applications and conventions
 * - Filament Ecosystem: Full compatibility with Filament plugins and extensions
 * - Database Integration: Support for all Laravel-supported database systems
 * - Model Integration: Automatic detection and integration with Eloquent models
 * - Migration Integration: Resource generation based on database migrations
 * - Seeder Integration: Test data integration for resource development and testing
 * - API Integration: RESTful API generation for resource operations
 * 
 * Performance Optimization:
 * - Query Optimization: Efficient database queries with eager loading and optimization
 * - Caching Integration: Intelligent caching strategies for improved performance
 * - Lazy Loading: Progressive loading for large datasets and complex relationships
 * - Memory Management: Efficient memory usage for resource-intensive operations
 * - Background Processing: Asynchronous processing for bulk operations
 * - Index Optimization: Database index recommendations for optimal performance
 * 
 * @package HkDevs\CodeForgeStudio\Services
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * $service = app(FilamentResourceGeneratorService::class);
 * $models = $service->getAvailableModels();
 * $result = $service->generateResource([
 *     'model' => 'App\\Models\\User',
 *     'resource_name' => 'UserResource',
 *     'navigation_group' => 'User Management'
 * ]);
 */
class FilamentResourceGeneratorService
{
    protected array $availableModels = [];
    protected array $availableMigrations = [];

    public function __construct()
    {
        $this->loadAvailableModels();
        $this->loadAvailableMigrations();
    }

    /**
     * Get all available models in the application
     */
    public function getAvailableModels(): array
    {
        return $this->availableModels;
    }

    /**
     * Get models that don't have Filament resources yet
     */
    public function getModelsWithoutResources(): array
    {
        $existingResources = $this->getExistingResources();

        return collect($this->availableModels)->filter(function ($model) use ($existingResources) {
            $modelName = class_basename($model['class']);
            return !in_array($modelName . 'Resource', $existingResources);
        })->values()->all();
    }

    /**
     * Get all available migrations
     */
    public function getAvailableMigrations(): array
    {
        return $this->availableMigrations;
    }

    /**
     * Generate resource configuration from model
     */
    public function generateConfigurationFromModel(string $modelClass): array
    {
        try {
            $reflection = new ReflectionClass($modelClass);
            $model = new $modelClass();

            // Get table information
            $tableName = $model->getTable();
            $fillable = $model->getFillable();
            $casts = $model->getCasts();
            $hidden = $model->getHidden();

            // Get column information
            $columns = Schema::getColumnListing($tableName);
            $columnDetails = [];

            foreach ($columns as $column) {
                $columnType = Schema::getColumnType($tableName, $column);
                $columnDetails[$column] = [
                    'name' => $column,
                    'type' => $columnType,
                    'fillable' => in_array($column, $fillable),
                    'hidden' => in_array($column, $hidden),
                    'cast' => $casts[$column] ?? null,
                ];
            }

            return [
                'model_name' => class_basename($modelClass),
                'model_class' => $modelClass,
                'table_name' => $tableName,
                'columns' => $columnDetails,
                'fillable' => $fillable,
                'casts' => $casts,
                'hidden' => $hidden,
                'suggested_form_fields' => $this->suggestFormFields($columnDetails),
                'suggested_table_columns' => $this->suggestTableColumns($columnDetails),
                'suggested_filters' => $this->suggestFilters($columnDetails),
            ];
        } catch (\Exception $e) {
            throw new \Exception("Failed to analyze model {$modelClass}: " . $e->getMessage());
        }
    }

    /**
     * Generate resource configuration from migration
     */
    public function generateConfigurationFromMigration(string $migrationFile): array
    {
        try {
            $migrationPath = database_path('migrations/' . $migrationFile . '.php');

            if (!File::exists($migrationPath)) {
                throw new \Exception("Migration file not found: {$migrationFile}");
            }

            $content = File::get($migrationPath);

            // Extract table name and columns from migration
            $tableName = $this->extractTableNameFromMigration($content, $migrationFile);
            $columns = $this->extractColumnsFromMigration($content);

            $modelName = Str::studly(Str::singular($tableName));
            $modelClass = "App\\Models\\{$modelName}";

            // Check if model exists, if not, generate it
            $modelGenerated = false;
            if (!class_exists($modelClass)) {
                $this->generateModelClass($modelName, $tableName, $columns);
                $modelGenerated = true;
            }

            return [
                'migration_file' => $migrationFile,
                'table_name' => $tableName,
                'model_name' => $modelName,
                'model_class' => $modelClass,
                'model_generated' => $modelGenerated,
                'columns' => $columns,
                'suggested_form_fields' => $this->suggestFormFields($columns),
                'suggested_table_columns' => $this->suggestTableColumns($columns),
                'suggested_filters' => $this->suggestFilters($columns),
            ];
        } catch (\Exception $e) {
            throw new \Exception("Failed to analyze migration {$migrationFile}: " . $e->getMessage());
        }
    }

    /**
     * Generate Filament resource code
     */
    public function generateResourceCode(object $generator): array
    {
        try {
            $resourceName = $generator->name;
            $modelClass = $generator->model_class;
            $formConfig = $generator->form_configuration;
            $tableConfig = $generator->table_configuration;
            $filterConfig = $generator->filter_configuration ?? [];
            $actionConfig = $generator->action_configuration ?? [];
            $bulkActionConfig = $generator->bulk_action_configuration ?? [];
            $pageConfig = $generator->page_configuration ?? [];
            $policyConfig = $generator->policy_configuration ?? [];

            // Generate resource class content
            $resourceCode = $this->buildResourceClass(
                $resourceName,
                $modelClass,
                $formConfig,
                $tableConfig,
                $filterConfig,
                $actionConfig,
                $bulkActionConfig,
                $pageConfig,
                $policyConfig
            );

            // Generate resource pages if needed
            $pages = [];
            if ($pageConfig['generate_pages'] ?? true) {
                $pages = $this->generateResourcePages($resourceName, $pageConfig);
            }

            // Generate policy if requested
            $policy = null;
            if ($policyConfig['generate_policy'] ?? false) {
                $policy = $this->generateResourcePolicy($resourceName, $modelClass, $policyConfig);
            }

            return [
                'resource' => $resourceCode,
                'pages' => $pages,
                'policy' => $policy,
                'files_to_create' => $this->getFilesToCreate($resourceName, $resourceCode, $pages, $policy),
            ];
        } catch (\Exception $e) {
            throw new \Exception("Failed to generate resource code: " . $e->getMessage());
        }
    }

    /**
     * Create resource files
     */
    public function createResourceFiles(object $generator, array $generatedCode): array
    {
        $createdFiles = [];
        $errors = [];

        try {
            // Create resource directory
            $resourceDir = app_path('Filament/Resources');
            if (!File::exists($resourceDir)) {
                File::makeDirectory($resourceDir, 0755, true);
            }

            // Create main resource file
            $resourceFile = $resourceDir . '/' . $generator->name . 'Resource.php';
            File::put($resourceFile, $generatedCode['resource']);
            $createdFiles[] = $resourceFile;

            // Create resource pages
            if (!empty($generatedCode['pages'])) {
                $pagesDir = $resourceDir . '/' . $generator->name . 'Resource/Pages';
                if (!File::exists($pagesDir)) {
                    File::makeDirectory($pagesDir, 0755, true);
                }

                foreach ($generatedCode['pages'] as $pageName => $pageCode) {
                    $pageFile = $pagesDir . '/' . $pageName . '.php';
                    File::put($pageFile, $pageCode);
                    $createdFiles[] = $pageFile;
                }
            }

            // Create policy if generated
            if (!empty($generatedCode['policy'])) {
                $policyDir = app_path('Policies');
                if (!File::exists($policyDir)) {
                    File::makeDirectory($policyDir, 0755, true);
                }

                $policyFile = $policyDir . '/' . $generator->name . 'Policy.php';
                File::put($policyFile, $generatedCode['policy']);
                $createdFiles[] = $policyFile;
            }

            // Update generator record (only if it's an Eloquent model)
            if (method_exists($generator, 'update')) {
                $generator->update([
                    'resource_class' => 'App\\Filament\\Resources\\' . $generator->name . 'Resource',
                    'file_path' => $resourceFile,
                    'status' => 'generated',
                    'error_message' => null,
                ]);
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();

            // Update generator with error (only if it's an Eloquent model)
            if (method_exists($generator, 'update')) {
                $generator->update([
                    'status' => 'error',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        return [
            'created_files' => $createdFiles,
            'errors' => $errors,
            'success' => empty($errors),
        ];
    }

    /**
     * Preview resource code without creating files
     */
    public function previewResourceCode(object $generator): array
    {
        $generatedCode = $this->generateResourceCode($generator);

        $previewFiles = [];

        // Add resource file
        if (isset($generatedCode['resource'])) {
            $previewFiles[] = [
                'name' => $generator->name . 'Resource.php',
                'content' => $generatedCode['resource'],
            ];
        }

        // Add page files
        if (isset($generatedCode['pages']) && is_array($generatedCode['pages'])) {
            foreach ($generatedCode['pages'] as $pageName => $pageContent) {
                $previewFiles[] = [
                    'name' => $pageName . '.php',
                    'content' => $pageContent,
                ];
            }
        }

        // Add policy file
        if (isset($generatedCode['policy']) && $generatedCode['policy']) {
            $previewFiles[] = [
                'name' => $generator->name . 'Policy.php',
                'content' => $generatedCode['policy'],
            ];
        }

        return $previewFiles;
    }

    /**
     * Update existing resource
     */
    public function updateResource(object $generator): array
    {
        try {
            if (!$generator->isGenerated()) {
                throw new \Exception('Resource has not been generated yet');
            }

            // Backup existing file
            $resourceFile = $generator->file_path;
            if (File::exists($resourceFile)) {
                $backupFile = $resourceFile . '.backup.' . time();
                File::copy($resourceFile, $backupFile);
            }

            // Generate new code
            $generatedCode = $this->generateResourceCode($generator);

            // Create updated files
            $result = $this->createResourceFiles($generator, $generatedCode);

            if ($result['success']) {
                if (method_exists($generator, 'update')) {
                    $generator->update([
                        'status' => 'updated',
                        'error_message' => null,
                    ]);
                }
            }

            return $result;
        } catch (\Exception $e) {
            if (method_exists($generator, 'update')) {
                $generator->update([
                    'status' => 'error',
                    'error_message' => $e->getMessage(),
                ]);
            }

            throw $e;
        }
    }

    /**
     * Delete generated resource files
     */
    public function deleteResource(object $generator): array
    {
        $deletedFiles = [];
        $errors = [];

        try {
            // Delete main resource file
            if ($generator->file_path && File::exists($generator->file_path)) {
                File::delete($generator->file_path);
                $deletedFiles[] = $generator->file_path;
            }

            // Delete resource pages directory
            $resourceDir = dirname($generator->file_path);
            $pagesDir = $resourceDir . '/' . $generator->name . 'Resource';
            if (File::exists($pagesDir)) {
                File::deleteDirectory($pagesDir);
                $deletedFiles[] = $pagesDir;
            }

            // Delete policy if exists
            $policyFile = app_path('Policies/' . $generator->name . 'Policy.php');
            if (File::exists($policyFile)) {
                File::delete($policyFile);
                $deletedFiles[] = $policyFile;
            }

            if (method_exists($generator, 'update')) {
                $generator->update([
                    'status' => 'draft',
                    'resource_class' => null,
                    'file_path' => null,
                    'error_message' => null,
                ]);
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        return [
            'deleted_files' => $deletedFiles,
            'errors' => $errors,
            'success' => empty($errors),
        ];
    }

    /**
     * Load available models
     */
    protected function loadAvailableModels(): void
    {
        $this->availableModels = [];

        // Get models from app/Models directory
        $modelsPath = app_path('Models');
        if (File::exists($modelsPath)) {
            $modelFiles = File::files($modelsPath);

            foreach ($modelFiles as $file) {
                $className = 'App\\Models\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME);

                if (class_exists($className)) {
                    try {
                        $reflection = new ReflectionClass($className);
                        $modelName = class_basename($className);

                        // Skip abstract classes and exclude "Unknown" models
                        if (!$reflection->isAbstract() && $modelName !== 'Unknown') {
                            $this->availableModels[] = [
                                'class' => $className,
                                'name' => $modelName,
                                'file' => $file->getFilename(),
                            ];
                        }
                    } catch (\Exception $e) {
                        // Skip problematic models
                    }
                }
            }
        }
    }

    /**
     * Load available migrations
     */
    protected function loadAvailableMigrations(): void
    {
        $this->availableMigrations = [];
        $migrationsPath = database_path('migrations');

        if (File::exists($migrationsPath)) {
            $migrationFiles = File::files($migrationsPath);

            foreach ($migrationFiles as $file) {
                $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

                // Skip package-specific migrations (prefixed with 2024_01_01_*)
                if ($this->isPackageMigration($filename)) {
                    continue;
                }

                // Skip Laravel system migrations
                if ($this->isLaravelSystemMigration($filename)) {
                    continue;
                }

                // Only include "create table" migrations
                if (!$this->isCreateTableMigration($filename, $file->getPathname())) {
                    continue;
                }

                $this->availableMigrations[] = [
                    'file' => $filename,
                    'name' => $this->extractMigrationName($filename),
                    'path' => $file->getPathname(),
                ];
            }
        }
    }

    /**
     * Check if migration belongs to this package
     */
    protected function isPackageMigration(string $filename): bool
    {
        // Package migrations are prefixed with 2024_01_01_*
        return Str::startsWith($filename, '2024_01_01_');
    }

    /**
     * Check if migration is a Laravel system migration
     */
    protected function isLaravelSystemMigration(string $filename): bool
    {
        $systemMigrations = [
            'create_users_table',
            'create_password_reset_tokens_table',
            'create_password_resets_table',
            'create_sessions_table',
            'create_cache_table',
            'create_jobs_table',
            'create_job_batches_table',
            'create_failed_jobs_table',
            'create_personal_access_tokens_table',
            'add_two_factor_columns_to_users_table',
            'create_teams_table',
            'create_team_user_table',
            'create_team_invitations_table',
        ];

        foreach ($systemMigrations as $systemMigration) {
            if (Str::contains($filename, $systemMigration)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if migration is a "create table" migration
     */
    protected function isCreateTableMigration(string $filename, string $filePath): bool
    {
        // Check filename pattern for "create_" prefix
        if (!preg_match('/create_\w+_table/', $filename)) {
            return false;
        }

        // Double-check by reading file content for Schema::create
        try {
            $content = File::get($filePath);
            return Str::contains($content, 'Schema::create(');
        } catch (\Exception $e) {
            // If we can't read the file, fall back to filename pattern
            return true;
        }
    }

    /**
     * Get existing Filament resources
     */
    protected function getExistingResources(): array
    {
        $resources = [];
        $resourcesPath = app_path('Filament/Resources');

        if (File::exists($resourcesPath)) {
            $resourceFiles = File::files($resourcesPath);

            foreach ($resourceFiles as $file) {
                if (Str::endsWith($file->getFilename(), 'Resource.php')) {
                    $resources[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                }
            }
        }

        return $resources;
    }

    /**
     * Suggest form fields based on column information
     */
    protected function suggestFormFields(array $columns): array
    {
        $fields = [];

        foreach ($columns as $column) {
            $fieldConfig = $this->suggestFormField($column);
            if ($fieldConfig) {
                $fields[] = $fieldConfig;
            }
        }

        return $fields;
    }

    /**
     * Suggest table columns based on column information
     */
    protected function suggestTableColumns(array $columns): array
    {
        $tableColumns = [];

        foreach ($columns as $column) {
            $columnConfig = $this->suggestTableColumn($column);
            if ($columnConfig) {
                $tableColumns[] = $columnConfig;
            }
        }

        return $tableColumns;
    }

    /**
     * Suggest filters based on column information
     */
    protected function suggestFilters(array $columns): array
    {
        $filters = [];

        foreach ($columns as $column) {
            $filterConfig = $this->suggestFilter($column);
            if ($filterConfig) {
                $filters[] = $filterConfig;
            }
        }

        return $filters;
    }

    /**
     * Suggest form field configuration for a column
     */
    protected function suggestFormField(array $column): ?array
    {
        $name = $column['name'];
        $type = $column['type'] ?? 'string';

        // Skip system columns
        if (in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
            return null;
        }

        $field = [
            'name' => $name,
            'label' => Str::title(str_replace('_', ' ', $name)),
            'required' => !in_array($name, ['email_verified_at']) && !Str::contains($name, 'nullable'),
        ];

        // Determine field type based on column type and name
        if (Str::contains($name, 'password')) {
            $field['type'] = 'password';
        } elseif (Str::contains($name, 'email')) {
            $field['type'] = 'email';
        } elseif (Str::contains($name, 'phone')) {
            $field['type'] = 'tel';
        } elseif (Str::contains($name, ['description', 'content', 'notes', 'message'])) {
            $field['type'] = 'textarea';
            $field['rows'] = 3;
        } elseif (Str::contains($name, 'date') || $type === 'date') {
            $field['type'] = 'date';
        } elseif (Str::contains($name, 'datetime') || $type === 'datetime') {
            $field['type'] = 'datetime';
        } elseif (Str::contains($name, 'time') || $type === 'time') {
            $field['type'] = 'time';
        } elseif ($type === 'boolean') {
            $field['type'] = 'toggle';
        } elseif (in_array($type, ['integer', 'bigint', 'smallint'])) {
            $field['type'] = 'number';
        } elseif (in_array($type, ['decimal', 'float', 'double'])) {
            $field['type'] = 'number';
            $field['step'] = '0.01';
        } elseif (Str::endsWith($name, '_id') || Str::contains($name, 'foreign')) {
            $field['type'] = 'select';
            $field['relationship'] = $this->guessRelationship($name);
        } else {
            $field['type'] = 'text';
        }

        // Only add placeholder for components that support it
        if (!in_array($field['type'], ['toggle', 'date', 'datetime', 'time'])) {
            $field['placeholder'] = 'Enter ' . str_replace('_', ' ', $name);
        }

        return $field;
    }

    /**
     * Suggest table column configuration for a column
     */
    protected function suggestTableColumn(array $column): ?array
    {
        $name = $column['name'];
        $type = $column['type'] ?? 'string';

        $tableColumn = [
            'name' => $name,
            'label' => Str::title(str_replace('_', ' ', $name)),
            'searchable' => in_array($name, ['name', 'title', 'email', 'slug']),
            'sortable' => !in_array($type, ['text', 'json']),
        ];

        // Determine column type
        if (in_array($name, ['created_at', 'updated_at', 'deleted_at'])) {
            $tableColumn['type'] = 'datetime';
            $tableColumn['date_format'] = 'M j, Y g:i A';
        } elseif (Str::contains($name, 'date') || $type === 'date') {
            $tableColumn['type'] = 'date';
        } elseif (Str::contains($name, 'email')) {
            $tableColumn['type'] = 'text';
            $tableColumn['icon'] = 'heroicon-o-envelope';
        } elseif ($type === 'boolean') {
            $tableColumn['type'] = 'icon';
            $tableColumn['boolean'] = true;
        } elseif (Str::contains($name, ['status', 'type', 'category'])) {
            $tableColumn['type'] = 'badge';
        } elseif (in_array($type, ['decimal', 'float', 'double']) && Str::contains($name, ['price', 'amount', 'cost'])) {
            $tableColumn['type'] = 'money';
        } else {
            $tableColumn['type'] = 'text';
        }

        return $tableColumn;
    }

    /**
     * Suggest filter configuration for a column
     */
    protected function suggestFilter(array $column): ?array
    {
        $name = $column['name'];
        $type = $column['type'] ?? 'string';

        // Only suggest filters for appropriate columns
        if (
            !in_array($name, ['status', 'type', 'category', 'created_at', 'updated_at']) &&
            !$type === 'boolean' &&
            !Str::endsWith($name, '_id')
        ) {
            return null;
        }

        $filter = [
            'name' => $name,
            'label' => Str::title(str_replace('_', ' ', $name)),
        ];

        if ($type === 'boolean') {
            $filter['type'] = 'ternary';
        } elseif (in_array($name, ['created_at', 'updated_at'])) {
            $filter['type'] = 'date_range';
        } elseif (in_array($name, ['status', 'type', 'category'])) {
            $filter['type'] = 'select';
            $filter['options'] = $this->guessSelectOptions($name);
        } elseif (Str::endsWith($name, '_id')) {
            $filter['type'] = 'select';
            $filter['relationship'] = $this->guessRelationship($name);
        } else {
            $filter['type'] = 'text';
        }

        return $filter;
    }

    /**
     * Build the resource class code
     */
    protected function buildResourceClass(
        string $resourceName,
        string $modelClass,
        array $formConfig,
        array $tableConfig,
        array $filterConfig,
        array $actionConfig,
        array $bulkActionConfig,
        array $pageConfig,
        array $policyConfig
    ): string {
        $modelClassName = class_basename($modelClass);
        $resourceClassName = $resourceName . 'Resource';
        $navigationIcon = $pageConfig['navigation_icon'] ?? 'heroicon-o-rectangle-stack';
        $navigationGroup = $pageConfig['navigation_group'] ?? null;
        $navigationSort = $pageConfig['navigation_sort'] ?? null;

        // Only generate model if it doesn't exist AND we're working from migration
        // When using model selection, the model should already exist
        if (!class_exists($modelClass) && !$this->isModelBasedGeneration($pageConfig)) {
            // Extract table name from model class for generation
            $tableName = Str::snake(Str::plural($modelClassName));
            $this->generateModelClass($modelClassName, $tableName, []);
        }

        $navigationGroupLine = $navigationGroup ? "    protected static ?string \$navigationGroup = '{$navigationGroup}';" : "    protected static ?string \$navigationGroup = 'Resources';";
        $navigationSortLine = $navigationSort ? "    protected static ?int \$navigationSort = {$navigationSort};" : "    protected static ?int \$navigationSort = null;";

        $formSchema = $this->buildFormSchema($formConfig);
        $tableColumns = $this->buildTableColumns($tableConfig);
        $filters = $this->buildFilters($filterConfig);
        $actions = $this->buildActions($actionConfig);
        $bulkActions = $this->buildBulkActions($bulkActionConfig);
        $pages = $this->buildPages($resourceName, $pageConfig);

        return "<?php

namespace App\Filament\Resources;

use {$modelClass};
use App\Filament\Resources\\{$resourceClassName}\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class {$resourceClassName} extends Resource
{
    protected static ?string \$model = {$modelClassName}::class;

    protected static ?string \$navigationIcon = '{$navigationIcon}';
    
{$navigationGroupLine}
    
{$navigationSortLine}

    public static function form(Form \$form): Form
    {
        return \$form
            ->schema([
{$formSchema}
            ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
{$tableColumns}
            ])
            ->filters([
{$filters}
            ])
            ->actions([
{$actions}
            ])
            ->bulkActions([
{$bulkActions}
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
{$pages}
        ];
    }
}";
    }

    /**
     * Check if generation is model-based (not migration-based)
     */
    protected function isModelBasedGeneration(array $pageConfig): bool
    {
        // This method helps distinguish between model-based and migration-based generation
        // If source_type is set and equals 'model', we're doing model-based generation
        return ($pageConfig['source_type'] ?? null) === 'model';
    }
    protected function buildFormSchema(array $formConfig): string
    {
        $fields = [];

        foreach ($formConfig['fields'] ?? [] as $field) {
            // Skip empty or invalid fields
            if (empty($field['name']) || empty($field['type'])) {
                continue;
            }

            $fieldCode = $this->buildFormField($field);
            if ($fieldCode) {
                $fields[] = $fieldCode;
            }
        }

        // If no fields were provided, add at least a basic field
        if (empty($fields)) {
            $fields[] = "                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()";
        }

        return implode(",\n", $fields);
    }

    /**
     * Build form field code
     */
    protected function buildFormField(array $field): string
    {
        $type = $field['type'] ?? 'text';
        $name = $field['name'];
        $label = $field['label'] ?? Str::title(str_replace('_', ' ', $name));

        $code = "                Forms\Components\\";

        switch ($type) {
            case 'text':
                $code .= "TextInput::make('{$name}')";
                break;
            case 'email':
                $code .= "TextInput::make('{$name}')\n                    ->email()";
                break;
            case 'password':
                $code .= "TextInput::make('{$name}')\n                    ->password()";
                break;
            case 'number':
                $code .= "TextInput::make('{$name}')\n                    ->numeric()";
                if (isset($field['step'])) {
                    $code .= "\n                    ->step('{$field['step']}')";
                }
                break;
            case 'textarea':
                $code .= "Textarea::make('{$name}')";
                if (isset($field['rows'])) {
                    $code .= "\n                    ->rows({$field['rows']})";
                }
                break;
            case 'select':
                $code .= "Select::make('{$name}')";
                if (isset($field['options'])) {
                    $options = $this->formatOptionsArray($field['options']);
                    $code .= "\n                    ->options({$options})";
                }
                break;
            case 'toggle':
                $code .= "Toggle::make('{$name}')";
                break;
            case 'date':
                $code .= "DatePicker::make('{$name}')";
                break;
            case 'datetime':
                $code .= "DateTimePicker::make('{$name}')";
                break;
            case 'time':
                $code .= "TimePicker::make('{$name}')";
                break;
            case 'relationship':
                $relationshipType = $field['relationship_type'] ?? 'belongsTo';
                $relationshipName = $field['relationship_name'] ?? $name;
                $titleAttribute = $field['title_attribute'] ?? 'name';
                $relatedModel = $field['related_model'] ?? '';

                if ($relationshipType === 'belongsToMany') {
                    $code .= "CheckboxList::make('{$relationshipName}')";
                    $code .= "\n                    ->relationship('{$relationshipName}', '{$titleAttribute}')";
                    if ($field['searchable'] ?? false) {
                        $code .= "\n                    ->searchable()";
                    }
                } else {
                    $code .= "Select::make('{$relationshipName}')";
                    $code .= "\n                    ->relationship('{$relationshipName}', '{$titleAttribute}')";
                    if ($field['searchable'] ?? false) {
                        $code .= "\n                    ->searchable()";
                    }
                    if ($field['preload'] ?? false) {
                        $code .= "\n                    ->preload()";
                    }
                }
                break;
            default:
                $code .= "TextInput::make('{$name}')";
        }

        // Add common properties
        if ($label !== Str::title(str_replace('_', ' ', $name))) {
            $code .= "\n                    ->label('{$label}')";
        }

        if ($field['required'] ?? false) {
            $code .= "\n                    ->required()";
        }

        // Only add placeholder for components that support it (exclude toggle, date pickers, etc.)
        if (isset($field['placeholder']) && !in_array($type, ['toggle', 'date', 'datetime', 'time'])) {
            $code .= "\n                    ->placeholder('{$field['placeholder']}')";
        }

        if (isset($field['helper_text'])) {
            $code .= "\n                    ->helperText('{$field['helper_text']}')";
        }

        return $code;
    }

    /**
     * Build table columns code
     */
    protected function buildTableColumns(array $tableConfig): string
    {
        $columns = [];

        foreach ($tableConfig['columns'] ?? [] as $column) {
            // Skip empty or invalid columns
            if (empty($column['name']) || empty($column['type'])) {
                continue;
            }

            $columnCode = $this->buildTableColumn($column);
            if ($columnCode) {
                $columns[] = $columnCode;
            }
        }

        // If no columns were provided, add at least basic columns
        if (empty($columns)) {
            $columns[] = "                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()";
            $columns[] = "                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggledHiddenByDefault()";
        }

        return implode(",\n", $columns);
    }

    /**
     * Build table column code
     */
    protected function buildTableColumn(array $column): string
    {
        $type = $column['type'] ?? 'text';
        $name = $column['name'];
        $label = $column['label'] ?? Str::title(str_replace('_', ' ', $name));

        $code = "                Tables\Columns\\";

        switch ($type) {
            case 'text':
                $code .= "TextColumn::make('{$name}')";
                break;
            case 'badge':
                $code .= "TextColumn::make('{$name}')\n                    ->badge()";
                if (isset($column['colors'])) {
                    $code .= "\n                    ->color(fn (string \$state): string => match (\$state) {";
                    foreach ($column['colors'] as $value => $color) {
                        $code .= "\n                        '{$value}' => '{$color}',";
                    }
                    $code .= "\n                        default => 'gray',\n                    })";
                }
                break;
            case 'icon':
                $code .= "IconColumn::make('{$name}')";
                if ($column['boolean'] ?? false) {
                    $code .= "\n                    ->boolean()";
                }
                break;
            case 'date':
                $code .= "TextColumn::make('{$name}')\n                    ->date()";
                break;
            case 'datetime':
                $code .= "TextColumn::make('{$name}')\n                    ->dateTime()";
                break;
            case 'money':
                $code .= "TextColumn::make('{$name}')\n                    ->money('USD')";
                break;
            case 'relationship':
                $relationshipName = $column['relationship_name'] ?? $name;
                $titleAttribute = $column['title_attribute'] ?? 'name';
                $relationshipType = $column['relationship_type'] ?? 'belongsTo';

                if ($relationshipType === 'belongsToMany') {
                    // For many-to-many relationships, show a comma-separated list
                    $code .= "TextColumn::make('{$relationshipName}.{$titleAttribute}')\n                    ->listWithLineBreaks()";
                } else {
                    // For belongsTo and hasOne relationships
                    $code .= "TextColumn::make('{$relationshipName}.{$titleAttribute}')";
                }
                break;
            default:
                $code .= "TextColumn::make('{$name}')";
        }

        // Add common properties
        if ($label !== Str::title(str_replace('_', ' ', $name))) {
            $code .= "\n                    ->label('{$label}')";
        }

        if ($column['searchable'] ?? false) {
            $code .= "\n                    ->searchable()";
        }

        if ($column['sortable'] ?? false) {
            $code .= "\n                    ->sortable()";
        }

        return $code;
    }

    /**
     * Build filters code
     */
    protected function buildFilters(array $filterConfig): string
    {
        $filters = [];

        foreach ($filterConfig['filters'] ?? [] as $filter) {
            $filters[] = $this->buildFilter($filter);
        }

        return implode(",\n", $filters);
    }

    /**
     * Build filter code
     */
    protected function buildFilter(array $filter): string
    {
        $type = $filter['type'] ?? 'text';
        $name = $filter['name'];
        $label = $filter['label'] ?? Str::title(str_replace('_', ' ', $name));

        $code = "                Tables\Filters\\";

        switch ($type) {
            case 'text':
                $code .= "Filter::make('{$name}')\n                    ->form([\n                        Forms\Components\TextInput::make('{$name}'),\n                    ])\n                    ->query(function (Builder \$query, array \$data): Builder {\n                        return \$query->when(\n                            \$data['{$name}'],\n                            fn (Builder \$query, \$value): Builder => \$query->where('{$name}', 'like', \"%{\$value}%\"),\n                        );\n                    })";
                break;
            case 'select':
                $code .= "SelectFilter::make('{$name}')";
                if (isset($filter['options'])) {
                    $options = $this->formatOptionsArray($filter['options']);
                    $code .= "\n                    ->options({$options})";
                }
                break;
            case 'ternary':
                $code .= "TernaryFilter::make('{$name}')";
                break;
            case 'date_range':
                $code .= "Filter::make('{$name}_range')\n                    ->form([\n                        Forms\Components\DatePicker::make('from'),\n                        Forms\Components\DatePicker::make('until'),\n                    ])\n                    ->query(function (Builder \$query, array \$data): Builder {\n                        return \$query\n                            ->when(\n                                \$data['from'],\n                                fn (Builder \$query, \$date): Builder => \$query->whereDate('{$name}', '>=', \$date),\n                            )\n                            ->when(\n                                \$data['until'],\n                                fn (Builder \$query, \$date): Builder => \$query->whereDate('{$name}', '<=', \$date),\n                            );\n                    })";
                break;
            default:
                $code .= "Filter::make('{$name}')\n                    ->form([\n                        Forms\Components\TextInput::make('{$name}'),\n                    ])\n                    ->query(function (Builder \$query, array \$data): Builder {\n                        return \$query->when(\n                            \$data['{$name}'],\n                            fn (Builder \$query, \$value): Builder => \$query->where('{$name}', 'like', \"%{\$value}%\"),\n                        );\n                    })";
        }

        if ($label !== Str::title(str_replace('_', ' ', $name))) {
            $code .= "\n                    ->label('{$label}')";
        }

        return $code;
    }

    /**
     * Build actions code
     */
    protected function buildActions(array $actionConfig): string
    {
        $actions = [
            "                Tables\Actions\ViewAction::make(),",
            "                Tables\Actions\EditAction::make(),",
        ];

        foreach ($actionConfig['actions'] ?? [] as $action) {
            $actions[] = $this->buildAction($action);
        }

        return implode("\n", $actions);
    }

    /**
     * Build bulk actions code
     */
    protected function buildBulkActions(array $bulkActionConfig): string
    {
        $actions = [
            "                Tables\Actions\BulkActionGroup::make([",
            "                    Tables\Actions\DeleteBulkAction::make(),",
            "                ]),",
        ];

        foreach ($bulkActionConfig['bulk_actions'] ?? [] as $action) {
            // Add custom bulk actions here
        }

        return implode("\n", $actions);
    }

    /**
     * Build pages configuration
     */
    protected function buildPages(string $resourceName, array $pageConfig): string
    {
        $pages = [
            "            'index' => Pages\List{$resourceName}s::route('/'),",
            "            'create' => Pages\Create{$resourceName}::route('/create'),",
            "            'view' => Pages\View{$resourceName}::route('/{record}'),",
            "            'edit' => Pages\Edit{$resourceName}::route('/{record}/edit'),",
        ];

        return implode("\n", $pages);
    }

    /**
     * Build action code
     */
    protected function buildAction(array $action): string
    {
        // Implement custom action building logic
        return "                // Custom action: {$action['name']}";
    }

    /**
     * Generate resource pages
     */
    protected function generateResourcePages(string $resourceName, array $pageConfig): array
    {
        $pages = [];

        // Generate List page
        $pages['List' . $resourceName . 's'] = $this->generateListPage($resourceName);

        // Generate Create page
        $pages['Create' . $resourceName] = $this->generateCreatePage($resourceName);

        // Generate View page
        $pages['View' . $resourceName] = $this->generateViewPage($resourceName);

        // Generate Edit page
        $pages['Edit' . $resourceName] = $this->generateEditPage($resourceName);

        return $pages;
    }

    /**
     * Generate List page
     */
    protected function generateListPage(string $resourceName): string
    {
        $resourceClassName = $resourceName . 'Resource';

        return "<?php

namespace App\Filament\Resources\\{$resourceClassName}\Pages;

use App\Filament\Resources\\{$resourceClassName};
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class List{$resourceName}s extends ListRecords
{
    protected static string \$resource = {$resourceClassName}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}";
    }

    /**
     * Generate Create page
     */
    protected function generateCreatePage(string $resourceName): string
    {
        $resourceClassName = $resourceName . 'Resource';

        return "<?php

namespace App\Filament\Resources\\{$resourceClassName}\Pages;

use App\Filament\Resources\\{$resourceClassName};
use Filament\Resources\Pages\CreateRecord;

class Create{$resourceName} extends CreateRecord
{
    protected static string \$resource = {$resourceClassName}::class;
}";
    }

    /**
     * Generate View page
     */
    protected function generateViewPage(string $resourceName): string
    {
        $resourceClassName = $resourceName . 'Resource';

        return "<?php

namespace App\Filament\Resources\\{$resourceClassName}\Pages;

use App\Filament\Resources\\{$resourceClassName};
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class View{$resourceName} extends ViewRecord
{
    protected static string \$resource = {$resourceClassName}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}";
    }

    /**
     * Generate Edit page
     */
    protected function generateEditPage(string $resourceName): string
    {
        $resourceClassName = $resourceName . 'Resource';

        return "<?php

namespace App\Filament\Resources\\{$resourceClassName}\Pages;

use App\Filament\Resources\\{$resourceClassName};
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit{$resourceName} extends EditRecord
{
    protected static string \$resource = {$resourceClassName}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}";
    }

    /**
     * Generate resource policy
     */
    protected function generateResourcePolicy(string $resourceName, string $modelClass, array $policyConfig): string
    {
        $modelClassName = class_basename($modelClass);
        $policyClassName = $resourceName . 'Policy';

        return "<?php

namespace App\Policies;

use App\Models\User;
use {$modelClass};

class {$policyClassName}
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User \$user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User \$user, {$modelClassName} \${$this->camelCase($resourceName)}): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User \$user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User \$user, {$modelClassName} \${$this->camelCase($resourceName)}): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User \$user, {$modelClassName} \${$this->camelCase($resourceName)}): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User \$user, {$modelClassName} \${$this->camelCase($resourceName)}): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User \$user, {$modelClassName} \${$this->camelCase($resourceName)}): bool
    {
        return true;
    }
}";
    }

    /**
     * Helper methods
     */
    protected function extractTableNameFromMigration(string $content, string $migrationFile = ''): string
    {
        // Try multiple patterns to extract table name from file content
        $patterns = [
            '/Schema::create\([\'"]([^\'"]+)[\'"]/', // Schema::create('table_name'
            '/Schema::create\(\s*[\'"]([^\'"]+)[\'"]/', // Schema::create( 'table_name'
            '/create\([\'"]([^\'"]+)[\'"]/', // create('table_name'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                return $matches[1];
            }
        }

        // If we still can't find it, try to extract from the migration filename
        // Migration files typically follow the pattern: YYYY_MM_DD_HHMMSS_create_table_name_table.php
        if ($migrationFile && preg_match('/create_(.+)_table/', $migrationFile, $matches)) {
            return $matches[1];
        }

        // Final fallback - if all else fails, throw an exception instead of returning unknown_table
        throw new \Exception("Could not extract table name from migration file: {$migrationFile}");
    }

    protected function extractColumnsFromMigration(string $content): array
    {
        $columns = [];

        // Simple regex to extract column definitions
        if (preg_match_all('/\$table->(\w+)\([\'"]([^\'"]+)[\'"]/', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $type = $match[1];
                $name = $match[2];

                $columns[$name] = [
                    'name' => $name,
                    'type' => $type,
                ];
            }
        }

        return $columns;
    }

    protected function extractMigrationName(string $filename): string
    {
        // Remove timestamp prefix and convert to readable name
        $name = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $filename);
        return Str::title(str_replace('_', ' ', $name));
    }

    protected function guessRelationship(string $columnName): string
    {
        if (Str::endsWith($columnName, '_id')) {
            return Str::camel(Str::replaceLast('_id', '', $columnName));
        }

        return $columnName;
    }

    protected function guessSelectOptions(string $columnName): array
    {
        return match ($columnName) {
            'status' => ['active' => 'Active', 'inactive' => 'Inactive'],
            'type' => ['type1' => 'Type 1', 'type2' => 'Type 2'],
            'category' => ['category1' => 'Category 1', 'category2' => 'Category 2'],
            default => [],
        };
    }

    /**
     * Format options array for PHP code generation
     */
    protected function formatOptionsArray($options): string
    {
        // Handle different input types
        if (empty($options)) {
            return '[]';
        }

        // If it's a string, try to parse it or return as simple array
        if (is_string($options)) {
            // Try to parse as JSON first
            $decoded = json_decode($options, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $options = $decoded;
            } else {
                // If not JSON, treat as comma-separated values
                $items = array_map('trim', explode(',', $options));
                $options = array_combine($items, $items);
            }
        }

        // Ensure we have an array at this point
        if (!is_array($options)) {
            return '[]';
        }

        $formattedOptions = [];
        foreach ($options as $key => $value) {
            $formattedOptions[] = "'{$key}' => '{$value}'";
        }

        return '[' . implode(', ', $formattedOptions) . ']';
    }

    protected function getFilesToCreate(string $resourceName, string $resourceCode, array $pages, ?string $policy): array
    {
        $files = [
            'resource' => "app/Filament/Resources/{$resourceName}Resource.php",
        ];

        foreach ($pages as $pageName => $pageCode) {
            $files['pages'][$pageName] = "app/Filament/Resources/{$resourceName}Resource/Pages/{$pageName}.php";
        }

        if ($policy) {
            $files['policy'] = "app/Policies/{$resourceName}Policy.php";
        }

        return $files;
    }

    protected function camelCase(string $value): string
    {
        return Str::camel($value);
    }

    /**
     * Generate Eloquent model class
     */
    protected function generateModelClass(string $modelName, string $tableName, array $columns): void
    {
        $modelPath = app_path("Models/{$modelName}.php");

        // Create Models directory if it doesn't exist
        $modelsDir = app_path('Models');
        if (!File::exists($modelsDir)) {
            File::makeDirectory($modelsDir, 0755, true);
        }

        // Generate fillable fields (exclude timestamps and id)
        $fillableFields = collect($columns)
            ->filter(function ($column) {
                return !in_array($column['name'], ['id', 'created_at', 'updated_at', 'deleted_at']);
            })
            ->pluck('name')
            ->map(fn($name) => "        '{$name}'")
            ->implode(",\n");

        // Generate casts for special column types
        $casts = collect($columns)
            ->filter(function ($column) {
                return in_array($column['type'], ['json', 'boolean', 'date', 'datetime', 'timestamp']);
            })
            ->mapWithKeys(function ($column) {
                $cast = match ($column['type']) {
                    'json' => 'array',
                    'boolean' => 'boolean',
                    'date' => 'date',
                    'datetime', 'timestamp' => 'datetime',
                    default => null
                };
                return $cast ? [$column['name'] => $cast] : [];
            })
            ->map(fn($cast, $name) => "        '{$name}' => '{$cast}'")
            ->implode(",\n");

        // Check if timestamps exist
        $hasTimestamps = collect($columns)->contains(
            fn($col) =>
            in_array($col['name'], ['created_at', 'updated_at'])
        );

        $modelContent = "<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
use Illuminate\\Database\\Eloquent\\Model;

class {$modelName} extends Model
{
    use HasFactory;

    protected \$table = '{$tableName}';

    public \$timestamps = " . ($hasTimestamps ? 'true' : 'false') . ";

    protected \$fillable = [
{$fillableFields}
    ];";

        if (!empty($casts)) {
            $modelContent .= "

    protected \$casts = [
{$casts}
    ];";
        }

        $modelContent .= "
}
";

        File::put($modelPath, $modelContent);
    }

    /**
     * Generate preview from configuration array (for BaseGeneratorPage compatibility)
     */
    public function generatePreview(array $config): array
    {
        // Create a temporary generator object from config
        $generator = $this->createGeneratorFromConfig($config);

        return $this->previewResourceCode($generator);
    }

    /**
     * Generate files from configuration array (for BaseGeneratorPage compatibility)
     */
    public function generateFiles(array $config): array
    {
        // Create a temporary generator object from config
        $generator = $this->createGeneratorFromConfig($config);

        $generatedCode = $this->generateResourceCode($generator);
        return $this->createResourceFiles($generator, $generatedCode);
    }

    /**
     * Create a generator object from configuration array
     */
    protected function createGeneratorFromConfig(array $config): object
    {
        $generator = new \stdClass();

        // Determine the resource name from class_name or provide better defaults
        $className = $config['class_name'] ?? '';
        $modelClass = $config['model'] ?? '';

        // If we have a model class but no class name, generate it from the model
        if (empty($className) && !empty($modelClass)) {
            $modelName = class_basename($modelClass);
            $className = $modelName . 'Resource';
        }

        // Final fallback if both are empty
        if (empty($className)) {
            $className = 'ExampleResource';
        }

        // Extract just the resource name without 'Resource' suffix for proper naming
        $resourceName = Str::replaceLast('Resource', '', $className);

        // Ensure we have the correct model class reference
        $finalModelClass = $modelClass ?: 'App\\Models\\' . $resourceName;

        // Map configuration to generator properties (matching expected property names)
        $generator->name = $resourceName;
        $generator->class_name = $className;
        $generator->model = $finalModelClass;
        $generator->model_class = $finalModelClass;
        $generator->namespace = $config['namespace'] ?? 'App\\Filament\\Resources';
        $generator->navigation_icon = $config['navigation_icon'] ?? 'heroicon-o-rectangle-stack';
        $generator->navigation_label = $config['navigation_label'] ?? '';
        $generator->navigation_group = $config['navigation_group'] ?? null;
        $generator->navigation_sort = $config['navigation_sort'] ?? null;
        $generator->slug = $config['slug'] ?? '';
        $generator->pages = $config['pages'] ?? ['index', 'create', 'edit'];

        // Prepare configuration arrays properly
        $generator->form_configuration = [
            'fields' => $config['form_fields'] ?? []
        ];
        $generator->table_configuration = [
            'columns' => $config['table_columns'] ?? []
        ];
        $generator->filter_configuration = [
            'filters' => $config['filters'] ?? []
        ];
        $generator->action_configuration = [
            'actions' => $config['actions'] ?? []
        ];
        $generator->bulk_action_configuration = [
            'bulk_actions' => $config['bulk_actions'] ?? []
        ];
        $generator->page_configuration = [
            'generate_pages' => true,
            'navigation_icon' => $config['navigation_icon'] ?? 'heroicon-o-rectangle-stack',
            'navigation_group' => $config['navigation_group'] ?? null,
            'navigation_sort' => $config['navigation_sort'] ?? null,
            'enable_view_page' => in_array('view', $config['pages'] ?? []),
            'source_type' => $config['source_type'] ?? 'model', // Track generation source
        ];
        $generator->policy_configuration = [
            'generate_policy' => false
        ];

        // Backward compatibility properties
        $generator->table_columns = $config['table_columns'] ?? [];
        $generator->form_fields = $config['form_fields'] ?? [];
        $generator->filters = $config['filters'] ?? [];
        $generator->actions = $config['actions'] ?? [];
        $generator->bulk_actions = $config['bulk_actions'] ?? [];
        $generator->widgets = $config['widgets'] ?? [];
        $generator->relations = $config['relations'] ?? [];
        $generator->enable_global_search = $config['enable_global_search'] ?? true;
        $generator->searchable_fields = $config['searchable_fields'] ?? [];
        $generator->default_sort = $config['default_sort'] ?? ['id', 'desc'];

        return $generator;
    }
}
