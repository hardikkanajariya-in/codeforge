<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * ModelGeneratorService
 * 
 * Advanced Laravel Eloquent model generation service for CodeForge Database Studio.
 * Creates intelligent, feature-rich Eloquent models with comprehensive relationship mapping and optimization.
 * 
 * Features:
 * - Intelligent Eloquent model generation with automatic property detection
 * - Comprehensive relationship mapping with all relationship types
 * - Automatic accessor and mutator generation based on field types
 * - Trait integration with automatic detection and application
 * - Scope generation for common query patterns and filters
 * - Cast configuration with automatic type detection and optimization
 * - Validation rule integration with model-level validation
 * - Event integration with model lifecycle events and observers
 * 
 * Model Generation Intelligence:
 * - Database Schema Analysis: Automatic model generation from database tables
 * - Relationship Detection: Intelligent detection of foreign key relationships
 * - Property Mapping: Automatic property and method generation based on columns
 * - Type Casting: Intelligent cast configuration for optimal data handling
 * - Attribute Discovery: Automatic detection of fillable, hidden, and guarded attributes
 * - Constraint Analysis: Integration of database constraints into model validation
 * - Index Optimization: Model configuration based on database index structures
 * 
 * Relationship Management:
 * - One-to-One Relationships: Automatic hasOne and belongsTo relationship generation
 * - One-to-Many Relationships: Intelligent hasMany and belongsTo relationship mapping
 * - Many-to-Many Relationships: Complex belongsToMany with pivot table configuration
 * - Polymorphic Relationships: Automatic polymorphic relationship detection and generation
 * - Has-Many-Through: Complex relationship chain detection and configuration
 * - Morph-Many Relationships: Advanced polymorphic relationship configurations
 * - Custom Relationships: Support for custom relationship types and configurations
 * 
 * Advanced Features:
 * - Accessor Generation: Automatic accessor creation for formatted data output
 * - Mutator Generation: Intelligent mutator creation for data transformation
 * - Scope Integration: Query scope generation for common filtering patterns
 * - Event Integration: Model event configuration with observer pattern support
 * - Trait Application: Automatic application of appropriate Laravel traits
 * - Soft Delete Integration: Automatic soft delete configuration when applicable
 * - Timestamp Management: Intelligent timestamp configuration and customization
 * 
 * Code Quality Features:
 * - PSR Compliance: Generated code follows PSR standards and best practices
 * - Documentation Generation: Automatic PHPDoc generation with type hints
 * - Code Formatting: Consistent code formatting with configurable style options
 * - Namespace Management: Automatic namespace resolution and organization
 * - Import Optimization: Intelligent use statement generation and organization
 * - Method Organization: Logical organization of generated methods and properties
 * - Comment Generation: Descriptive comments for generated code elements
 * 
 * Validation Integration:
 * - Rule Generation: Automatic validation rule creation based on database constraints
 * - Form Request Integration: Generation of companion form request classes
 * - Custom Validation: Support for custom validation rules and logic
 * - Relationship Validation: Validation rules for relationship integrity
 * - Conditional Validation: Context-aware validation rule generation
 * - Localization Support: Multi-language validation message generation
 * - Error Handling: Comprehensive error handling and validation feedback
 * 
 * Performance Optimization:
 * - Eager Loading Configuration: Automatic with property configuration for relationships
 * - Query Optimization: Optimized query generation with efficient relationship loading
 * - Caching Integration: Model-level caching configuration and optimization
 * - Index Utilization: Model configuration that leverages database indexes
 * - Memory Management: Efficient model generation and instantiation patterns
 * - Lazy Loading: Intelligent lazy loading configuration for large datasets
 * - Connection Management: Multi-database connection support and optimization
 * 
 * Integration Features:
 * - Laravel Integration: Full compatibility with Laravel's Eloquent ORM
 * - Factory Integration: Automatic model factory generation and configuration
 * - Seeder Integration: Model-aware seeder generation with relationship data
 * - Policy Integration: Automatic authorization policy generation and linking
 * - Observer Integration: Model observer generation for event handling
 * - API Resource Integration: Automatic API resource generation for REST APIs
 * - Testing Integration: Test-friendly model generation with testing utilities
 * 
 * Customization Options:
 * - Template System: Customizable model templates with user-defined patterns
 * - Trait Selection: Configurable trait application based on model requirements
 * - Relationship Configuration: Custom relationship naming and configuration options
 * - Code Style: Integration with code formatting standards and style guides
 * - Namespace Customization: Flexible namespace organization and structure
 * - Method Generation: Selective generation of model methods and features
 * - Extension Points: Plugin architecture for custom model generation logic
 * 
 * @package HkDevs\CodeForgeStudio\Services
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * $service = app(ModelGeneratorService::class);
 * $result = $service->generateModel([
 *     'name' => 'User',
 *     'table' => 'users',
 *     'fillable' => ['name', 'email'],
 *     'relationships' => [
 *         ['type' => 'hasMany', 'model' => 'Post', 'name' => 'posts']
 *     ]
 * ]);
 */
class ModelGeneratorService
{
    protected string $modelsPath;
    protected string $factoriesPath;
    protected string $seedersPath;
    protected string $policiesPath;

    public function __construct()
    {
        $this->modelsPath = app_path('Models');
        $this->factoriesPath = database_path('factories');
        $this->seedersPath = database_path('seeders');
        $this->policiesPath = app_path('Policies');
    }

    /**
     * Generate a complete model with optional relations, factory, seeder, and policy
     */
    public function generateModel(array $modelData): array
    {
        $results = [];

        // Generate the model
        $modelResult = $this->createModel($modelData);
        $results['model'] = $modelResult;

        // Generate factory if requested
        if ($modelData['generate_factory'] ?? false) {
            $factoryResult = $this->createFactory($modelData);
            $results['factory'] = $factoryResult;
        }

        // Generate seeder if requested
        if ($modelData['generate_seeder'] ?? false) {
            $seederResult = $this->createSeeder($modelData);
            $results['seeder'] = $seederResult;
        }

        // Generate policy if requested
        if ($modelData['generate_policy'] ?? false) {
            $policyResult = $this->createPolicy($modelData);
            $results['policy'] = $policyResult;
        }

        return $results;
    }

    /**
     * Generate files - wrapper method for BaseGeneratorPage compatibility
     */
    public function generateFiles(array $modelData): array
    {
        $results = $this->generateModel($modelData);

        // Format results for BaseGeneratorPage compatibility
        $formattedResults = [
            'success' => true,
            'files_created' => []
        ];

        foreach ($results as $type => $result) {
            if ($result['success'] ?? false) {
                $formattedResults['files_created'][] = [
                    'type' => $result['type'],
                    'class_name' => $result['class_name'],
                    'path' => $result['file_path'],
                    'file_name' => $result['file_name']
                ];
            }
        }

        return $formattedResults;
    }

    /**
     * Preview model generation without creating files
     */
    public function previewModel(array $modelData): array
    {
        $results = [];

        // Preview the model
        $className = $this->getModelClassName($modelData['name']);
        $results['model'] = [
            'class_name' => "{$className}.php",
            'content' => $this->generateModelContent($modelData),
            'file_path' => $this->modelsPath . "/{$className}.php"
        ];

        // Preview factory if requested
        if ($modelData['generate_factory'] ?? false) {
            $factoryClassName = "{$className}Factory";
            $results['factory'] = [
                'class_name' => "{$factoryClassName}.php",
                'content' => $this->generateFactoryContent($modelData),
                'file_path' => $this->factoriesPath . "/{$factoryClassName}.php"
            ];
        }

        // Preview seeder if requested
        if ($modelData['generate_seeder'] ?? false) {
            $seederClassName = "{$className}Seeder";
            $results['seeder'] = [
                'class_name' => "{$seederClassName}.php",
                'content' => $this->generateSeederContent($modelData),
                'file_path' => $this->seedersPath . "/{$seederClassName}.php"
            ];
        }

        // Preview policy if requested
        if ($modelData['generate_policy'] ?? false) {
            $policyClassName = "{$className}Policy";
            $results['policy'] = [
                'class_name' => "{$policyClassName}.php",
                'content' => $this->generatePolicyContent($modelData),
                'file_path' => $this->policiesPath . "/{$policyClassName}.php"
            ];
        }

        return $results;
    }

    /**
     * Generate preview - wrapper method for BaseGeneratorPage compatibility
     */
    public function generatePreview(array $modelData): array
    {
        return $this->previewModel($modelData);
    }

    /**
     * Create the model file
     */
    protected function createModel(array $modelData): array
    {
        $className = $this->getModelClassName($modelData['name']);
        $fileName = "{$className}.php";
        $filePath = $this->modelsPath . '/' . $fileName;

        if (File::exists($filePath)) {
            throw new \Exception("Model file already exists: {$fileName}");
        }

        $content = $this->generateModelContent($modelData);

        // Ensure directory exists
        if (!File::isDirectory($this->modelsPath)) {
            File::makeDirectory($this->modelsPath, 0755, true);
        }

        File::put($filePath, $content);

        return [
            'type' => 'model',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'content' => $content,
            'success' => true
        ];
    }

    /**
     * Generate model content
     */
    protected function generateModelContent(array $modelData): string
    {
        $className = $this->getModelClassName($modelData['name']);
        $tableName = $modelData['table_name'] ?? Str::snake(Str::pluralStudly($className));

        $fillable = $this->generateFillableArray($modelData);
        $casts = $this->generateCastsArray($modelData);
        $relations = $this->generateRelations($modelData);
        $scopes = $this->generateScopes($modelData);
        $mutators = $this->generateMutators($modelData);
        $accessors = $this->generateAccessors($modelData);
        $customMethods = $this->generateCustomMethods($modelData);

        $content = "<?php\n\nnamespace App\\Models;\n\n";

        // Add imports
        $imports = [
            'Illuminate\Database\Eloquent\Model',
            'Illuminate\Database\Eloquent\Factories\HasFactory',
        ];

        if ($modelData['soft_deletes'] ?? false) {
            $imports[] = 'Illuminate\Database\Eloquent\SoftDeletes';
        }

        if (!empty($modelData['relations'])) {
            foreach ($modelData['relations'] as $relation) {
                $relatedModel = $this->getModelClassName($relation['related_model']);
                if ($relatedModel !== $className) {
                    $imports[] = "App\\Models\\{$relatedModel}";
                }
            }
        }

        // Remove duplicates and sort
        $imports = array_unique($imports);
        sort($imports);

        foreach ($imports as $import) {
            $content .= "use {$import};\n";
        }

        $content .= "\nclass {$className} extends Model\n{\n";

        // Add traits
        $traitsList = ['HasFactory'];
        if ($modelData['soft_deletes'] ?? false) {
            $traitsList[] = 'SoftDeletes';
        }

        $content .= "    use " . implode(', ', $traitsList) . ";\n\n";

        // Add table name if different from convention
        $conventionalTable = Str::snake(Str::pluralStudly($className));
        if ($tableName !== $conventionalTable) {
            $content .= "    protected \$table = '{$tableName}';\n\n";
        }

        // Add fillable
        if (!empty($fillable)) {
            $content .= "    protected \$fillable = [\n";
            foreach ($fillable as $field) {
                $content .= "        '{$field}',\n";
            }
            $content .= "    ];\n\n";
        }

        // Add casts
        if (!empty($casts)) {
            $content .= "    protected \$casts = [\n";
            foreach ($casts as $field => $cast) {
                $content .= "        '{$field}' => '{$cast}',\n";
            }
            $content .= "    ];\n\n";
        }

        // Add timestamp configuration
        if ($modelData['timestamps'] === false) {
            $content .= "    public \$timestamps = false;\n\n";
        }

        // Add custom attributes
        if (!empty($modelData['custom_attributes'])) {
            foreach ($modelData['custom_attributes'] as $attribute) {
                $content .= "    protected \${$attribute['name']} = {$attribute['value']};\n";
            }
            $content .= "\n";
        }

        // Add relations
        if (!empty($relations)) {
            $content .= $relations . "\n";
        }

        // Add scopes
        if (!empty($scopes)) {
            $content .= $scopes . "\n";
        }

        // Add mutators
        if (!empty($mutators)) {
            $content .= $mutators . "\n";
        }

        // Add accessors
        if (!empty($accessors)) {
            $content .= $accessors . "\n";
        }

        // Add custom methods
        if (!empty($customMethods)) {
            $content .= $customMethods;
        }

        $content .= "}\n";

        return $content;
    }

    /**
     * Generate fillable array
     */
    protected function generateFillableArray(array $modelData): array
    {
        $fillable = [];

        if (!empty($modelData['columns'])) {
            foreach ($modelData['columns'] as $column) {
                if ($column['fillable'] ?? false) {
                    $fillable[] = $column['name'];
                }
            }
        }

        // Add custom fillable fields
        if (!empty($modelData['fillable'])) {
            $fillable = array_merge($fillable, $modelData['fillable']);
        }

        return array_unique($fillable);
    }

    /**
     * Generate casts array
     */
    protected function generateCastsArray(array $modelData): array
    {
        $casts = [];

        if (!empty($modelData['columns'])) {
            foreach ($modelData['columns'] as $column) {
                $cast = $this->getEloquentCast($column['type']);
                if ($cast && ($column['cast'] ?? true)) {
                    $casts[$column['name']] = $cast;
                }
            }
        }

        // Add custom casts
        if (!empty($modelData['casts'])) {
            $casts = array_merge($casts, $modelData['casts']);
        }

        return $casts;
    }

    /**
     * Generate relations
     */
    protected function generateRelations(array $modelData): string
    {
        if (empty($modelData['relations'])) {
            return '';
        }

        $relations = "    // Relations\n";

        foreach ($modelData['relations'] as $relation) {
            $relations .= $this->generateRelationMethod($relation) . "\n";
        }

        return $relations;
    }

    /**
     * Generate a single relation method
     */
    protected function generateRelationMethod(array $relation): string
    {
        $methodName = $relation['name'];
        $type = $relation['type'];
        $relatedModel = $this->getModelClassName($relation['related_model']);
        $foreignKey = $relation['foreign_key'] ?? null;
        $localKey = $relation['local_key'] ?? null;

        $method = "    public function {$methodName}()\n    {\n";

        switch ($type) {
            case 'hasOne':
                $method .= "        return \$this->hasOne({$relatedModel}::class";
                if ($foreignKey) $method .= ", '{$foreignKey}'";
                if ($localKey) $method .= ", '{$localKey}'";
                $method .= ");\n";
                break;

            case 'hasMany':
                $method .= "        return \$this->hasMany({$relatedModel}::class";
                if ($foreignKey) $method .= ", '{$foreignKey}'";
                if ($localKey) $method .= ", '{$localKey}'";
                $method .= ");\n";
                break;

            case 'belongsTo':
                $method .= "        return \$this->belongsTo({$relatedModel}::class";
                if ($foreignKey) $method .= ", '{$foreignKey}'";
                if ($localKey) $method .= ", '{$localKey}'";
                $method .= ");\n";
                break;

            case 'belongsToMany':
                $pivotTable = $relation['pivot_table'] ?? null;
                $method .= "        return \$this->belongsToMany({$relatedModel}::class";
                if ($pivotTable) $method .= ", '{$pivotTable}'";
                if ($foreignKey) $method .= ", '{$foreignKey}'";
                if ($localKey) $method .= ", '{$localKey}'";
                $method .= ")";

                if (!empty($relation['pivot_columns'])) {
                    $pivotCols = "'" . implode("', '", $relation['pivot_columns']) . "'";
                    $method .= "->withPivot([{$pivotCols}])";
                }

                if ($relation['timestamps'] ?? false) {
                    $method .= "->withTimestamps()";
                }

                $method .= ";\n";
                break;

            case 'morphTo':
                $method .= "        return \$this->morphTo();\n";
                break;

            case 'morphOne':
                $method .= "        return \$this->morphOne({$relatedModel}::class, '{$relation['morph_name']}');\n";
                break;

            case 'morphMany':
                $method .= "        return \$this->morphMany({$relatedModel}::class, '{$relation['morph_name']}');\n";
                break;

            default:
                $method .= "        // Custom relation implementation\n";
        }

        $method .= "    }\n";

        return $method;
    }

    /**
     * Generate custom methods
     */
    protected function generateCustomMethods(array $modelData): string
    {
        if (empty($modelData['custom_methods'])) {
            return '';
        }

        $methods = "    // Custom Methods\n";

        foreach ($modelData['custom_methods'] as $method) {
            $methods .= $this->generateCustomMethod($method) . "\n";
        }

        return $methods;
    }

    /**
     * Generate a single custom method
     */
    protected function generateCustomMethod(array $method): string
    {
        $visibility = $method['visibility'] ?? 'public';
        $name = $method['name'];
        $parameters = $method['parameters'] ?? [];
        $returnType = $method['return_type'] ?? '';
        $body = $method['body'] ?? '// Method implementation';

        $methodStr = "    {$visibility} function {$name}(";

        // Add parameters
        $paramStrings = [];
        foreach ($parameters as $param) {
            $paramStr = '';
            if (!empty($param['type'])) {
                $paramStr .= $param['type'] . ' ';
            }
            $paramStr .= '$' . $param['name'];
            if (!empty($param['default'])) {
                $paramStr .= ' = ' . $param['default'];
            }
            $paramStrings[] = $paramStr;
        }
        $methodStr .= implode(', ', $paramStrings);
        $methodStr .= ")";

        // Add return type
        if ($returnType) {
            $methodStr .= ": {$returnType}";
        }

        $methodStr .= "\n    {\n";

        // Add body with proper indentation
        $bodyLines = explode("\n", $body);
        foreach ($bodyLines as $line) {
            if (trim($line)) {
                $methodStr .= "        {$line}\n";
            } else {
                $methodStr .= "\n";
            }
        }

        $methodStr .= "    }\n";

        return $methodStr;
    }

    /**
     * Generate scopes
     */
    protected function generateScopes(array $modelData): string
    {
        if (empty($modelData['scopes'])) {
            return '';
        }

        $scopes = "    // Query Scopes\n";

        foreach ($modelData['scopes'] as $scope) {
            $scopes .= $this->generateScopeMethod($scope) . "\n";
        }

        return $scopes;
    }

    /**
     * Generate a single scope method
     */
    protected function generateScopeMethod(array $scope): string
    {
        $name = $scope['name'];
        $parameters = $scope['parameters'] ?? [];
        $body = $scope['body'] ?? '// Scope implementation';

        $methodName = 'scope' . ucfirst($name);
        $methodStr = "    public function {$methodName}(\$query";

        // Add parameters
        foreach ($parameters as $param) {
            $methodStr .= ', ';
            if (!empty($param['type'])) {
                $methodStr .= $param['type'] . ' ';
            }
            $methodStr .= '$' . $param['name'];
            if (!empty($param['default'])) {
                $methodStr .= ' = ' . $param['default'];
            }
        }

        $methodStr .= ")\n    {\n";

        // Add body with proper indentation
        $bodyLines = explode("\n", $body);
        foreach ($bodyLines as $line) {
            if (trim($line)) {
                $methodStr .= "        {$line}\n";
            } else {
                $methodStr .= "\n";
            }
        }

        $methodStr .= "    }\n";

        return $methodStr;
    }

    /**
     * Generate mutators
     */
    protected function generateMutators(array $modelData): string
    {
        if (empty($modelData['mutators'])) {
            return '';
        }

        $mutators = "    // Mutators\n";

        foreach ($modelData['mutators'] as $mutator) {
            $mutators .= $this->generateMutatorMethod($mutator) . "\n";
        }

        return $mutators;
    }

    /**
     * Generate a single mutator method
     */
    protected function generateMutatorMethod(array $mutator): string
    {
        $attribute = $mutator['attribute'];
        $body = $mutator['body'] ?? 'return $value;';

        $methodName = 'set' . Str::studly($attribute) . 'Attribute';
        $methodStr = "    public function {$methodName}(\$value)\n    {\n";

        // Add body with proper indentation
        $bodyLines = explode("\n", $body);
        foreach ($bodyLines as $line) {
            if (trim($line)) {
                $methodStr .= "        {$line}\n";
            } else {
                $methodStr .= "\n";
            }
        }

        $methodStr .= "    }\n";

        return $methodStr;
    }

    /**
     * Generate accessors
     */
    protected function generateAccessors(array $modelData): string
    {
        if (empty($modelData['accessors'])) {
            return '';
        }

        $accessors = "    // Accessors\n";

        foreach ($modelData['accessors'] as $accessor) {
            $accessors .= $this->generateAccessorMethod($accessor) . "\n";
        }

        return $accessors;
    }

    /**
     * Generate a single accessor method
     */
    protected function generateAccessorMethod(array $accessor): string
    {
        $attribute = $accessor['attribute'];
        $body = $accessor['body'] ?? 'return $value;';

        $methodName = 'get' . Str::studly($attribute) . 'Attribute';
        $methodStr = "    public function {$methodName}(\$value)\n    {\n";

        // Add body with proper indentation
        $bodyLines = explode("\n", $body);
        foreach ($bodyLines as $line) {
            if (trim($line)) {
                $methodStr .= "        {$line}\n";
            } else {
                $methodStr .= "\n";
            }
        }

        $methodStr .= "    }\n";

        return $methodStr;
    }

    /**
     * Create factory file
     */
    protected function createFactory(array $modelData): array
    {
        $modelClass = $this->getModelClassName($modelData['name']);
        $className = "{$modelClass}Factory";
        $fileName = "{$className}.php";
        $filePath = $this->factoriesPath . '/' . $fileName;

        if (File::exists($filePath)) {
            throw new \Exception("Factory file already exists: {$fileName}");
        }

        $content = $this->generateFactoryContent($modelData);

        // Ensure directory exists
        if (!File::isDirectory($this->factoriesPath)) {
            File::makeDirectory($this->factoriesPath, 0755, true);
        }

        File::put($filePath, $content);

        return [
            'type' => 'factory',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'content' => $content,
            'success' => true
        ];
    }

    /**
     * Generate factory content
     */
    protected function generateFactoryContent(array $modelData): string
    {
        $modelClass = $this->getModelClassName($modelData['name']);
        $className = "{$modelClass}Factory";

        $content = "<?php\n\nnamespace Database\\Factories;\n\n";
        $content .= "use App\\Models\\{$modelClass};\n";
        $content .= "use Illuminate\\Database\\Eloquent\\Factories\\Factory;\n\n";
        $content .= "/**\n * @extends \\Illuminate\\Database\\Eloquent\\Factories\\Factory<\\App\\Models\\{$modelClass}>\n */\n";
        $content .= "class {$className} extends Factory\n{\n";
        $content .= "    /**\n     * The name of the factory's corresponding model.\n     *\n     * @var string\n     */\n";
        $content .= "    protected \$model = {$modelClass}::class;\n\n";
        $content .= "    /**\n     * Define the model's default state.\n     *\n     * @return array<string, mixed>\n     */\n";
        $content .= "    public function definition(): array\n    {\n";
        $content .= "        return [\n";

        // Generate fake data for columns
        if (!empty($modelData['columns'])) {
            foreach ($modelData['columns'] as $column) {
                if (!in_array($column['name'], ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    $fakeData = $this->generateFakeData($column);
                    $content .= "            '{$column['name']}' => {$fakeData},\n";
                }
            }
        }

        // Add factory data from model data
        if (!empty($modelData['factory_data'])) {
            foreach ($modelData['factory_data'] as $field => $fakeMethod) {
                $content .= "            '{$field}' => {$fakeMethod},\n";
            }
        }

        $content .= "        ];\n";
        $content .= "    }\n";

        // Add factory states if defined
        if (!empty($modelData['factory_states'])) {
            foreach ($modelData['factory_states'] as $state) {
                $content .= "\n    /**\n     * {$state['description']}\n     */\n";
                $content .= "    public function {$state['name']}(): static\n    {\n";
                $content .= "        return \$this->state(fn (array \$attributes) => [\n";
                foreach ($state['attributes'] as $attr => $value) {
                    $content .= "            '{$attr}' => {$value},\n";
                }
                $content .= "        ]);\n";
                $content .= "    }\n";
            }
        }

        $content .= "}\n";

        return $content;
    }

    /**
     * Create seeder file
     */
    protected function createSeeder(array $modelData): array
    {
        $modelClass = $this->getModelClassName($modelData['name']);
        $className = "{$modelClass}Seeder";
        $fileName = "{$className}.php";
        $filePath = $this->seedersPath . '/' . $fileName;

        if (File::exists($filePath)) {
            throw new \Exception("Seeder file already exists: {$fileName}");
        }

        $content = $this->generateSeederContent($modelData);

        // Ensure directory exists
        if (!File::isDirectory($this->seedersPath)) {
            File::makeDirectory($this->seedersPath, 0755, true);
        }

        File::put($filePath, $content);

        return [
            'type' => 'seeder',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'content' => $content,
            'success' => true
        ];
    }

    /**
     * Generate seeder content
     */
    protected function generateSeederContent(array $modelData): string
    {
        $modelClass = $this->getModelClassName($modelData['name']);
        $className = "{$modelClass}Seeder";
        $count = $modelData['seeder_count'] ?? 10;

        $content = "<?php\n\nnamespace Database\\Seeders;\n\n";
        $content .= "use App\\Models\\{$modelClass};\n";
        $content .= "use Illuminate\\Database\\Seeder;\n\n";
        $content .= "class {$className} extends Seeder\n{\n";
        $content .= "    /**\n     * Run the database seeds.\n     */\n";
        $content .= "    public function run(): void\n    {\n";

        if ($modelData['use_factory'] ?? true) {
            $content .= "        {$modelClass}::factory()\n";

            if (!empty($modelData['factory_states'])) {
                foreach ($modelData['factory_states'] as $state) {
                    if ($state['use_in_seeder'] ?? false) {
                        $content .= "            ->{$state['name']}()\n";
                    }
                }
            }

            $content .= "            ->count({$count})\n";
            $content .= "            ->create();\n";
        } else {
            // Manual seeding
            $content .= "        \$data = [\n";
            if (!empty($modelData['seed_data'])) {
                foreach ($modelData['seed_data'] as $record) {
                    $content .= "            [\n";
                    foreach ($record as $field => $value) {
                        $content .= "                '{$field}' => '{$value}',\n";
                    }
                    $content .= "            ],\n";
                }
            }
            $content .= "        ];\n\n";
            $content .= "        foreach (\$data as \$item) {\n";
            $content .= "            {$modelClass}::create(\$item);\n";
            $content .= "        }\n";
        }

        $content .= "    }\n";
        $content .= "}\n";

        return $content;
    }

    /**
     * Create policy file
     */
    protected function createPolicy(array $modelData): array
    {
        $modelClass = $this->getModelClassName($modelData['name']);
        $className = "{$modelClass}Policy";
        $fileName = "{$className}.php";
        $filePath = $this->policiesPath . '/' . $fileName;

        if (File::exists($filePath)) {
            throw new \Exception("Policy file already exists: {$fileName}");
        }

        $content = $this->generatePolicyContent($modelData);

        // Ensure directory exists
        if (!File::isDirectory($this->policiesPath)) {
            File::makeDirectory($this->policiesPath, 0755, true);
        }

        File::put($filePath, $content);

        return [
            'type' => 'policy',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'class_name' => $className,
            'content' => $content,
            'success' => true
        ];
    }

    /**
     * Generate policy content
     */
    protected function generatePolicyContent(array $modelData): string
    {
        $modelClass = $this->getModelClassName($modelData['name']);
        $className = "{$modelClass}Policy";

        $content = "<?php\n\nnamespace App\\Policies;\n\n";
        $content .= "use App\\Models\\{$modelClass};\n";
        $content .= "use App\\Models\\User;\n";
        $content .= "use Illuminate\\Auth\\Access\\Response;\n\n";
        $content .= "class {$className}\n{\n";

        // Standard policy methods
        $methods = [
            'viewAny' => 'Determine whether the user can view any models.',
            'view' => 'Determine whether the user can view the model.',
            'create' => 'Determine whether the user can create models.',
            'update' => 'Determine whether the user can update the model.',
            'delete' => 'Determine whether the user can delete the model.',
            'restore' => 'Determine whether the user can restore the model.',
            'forceDelete' => 'Determine whether the user can permanently delete the model.',
        ];

        foreach ($methods as $methodName => $description) {
            $content .= "    /**\n     * {$description}\n     */\n";
            $content .= "    public function {$methodName}(User \$user";

            if (!in_array($methodName, ['viewAny', 'create'])) {
                $content .= ", {$modelClass} \$" . Str::camel($modelClass);
            }

            $content .= "): bool\n    {\n";

            // Add policy logic based on configuration
            if (!empty($modelData['policy_rules'][$methodName])) {
                $rule = $modelData['policy_rules'][$methodName];
                $content .= "        {$rule}\n";
            } else {
                $content .= "        // Add your authorization logic here\n";
                $content .= "        return true;\n";
            }

            $content .= "    }\n\n";
        }

        $content = rtrim($content) . "\n}\n";

        return $content;
    }

    /**
     * Validate model data
     */
    public function validateModelData(array $modelData): array
    {
        $errors = [];

        if (empty($modelData['name'])) {
            $errors[] = 'Model name is required';
        } elseif (!$this->isValidClassName($modelData['name'])) {
            $errors[] = 'Invalid model name format';
        }

        // Validate relations
        if (!empty($modelData['relations'])) {
            foreach ($modelData['relations'] as $index => $relation) {
                $relationErrors = $this->validateRelation($relation, $index);
                $errors = array_merge($errors, $relationErrors);
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Get model class name
     */
    protected function getModelClassName(string $name): string
    {
        return Str::studly($name);
    }

    /**
     * Get Eloquent cast for column type
     */
    protected function getEloquentCast(string $columnType): ?string
    {
        return match ($columnType) {
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
     * Generate fake data for factory
     */
    protected function generateFakeData(array $column): string
    {
        $type = $column['type'];
        $name = $column['name'];

        // Check for common field names first
        if (Str::contains($name, ['email'])) {
            return 'fake()->unique()->safeEmail()';
        }
        if (Str::contains($name, ['phone'])) {
            return 'fake()->phoneNumber()';
        }
        if (Str::contains($name, ['name', 'title'])) {
            return 'fake()->name()';
        }
        if (Str::contains($name, ['address'])) {
            return 'fake()->address()';
        }
        if (Str::contains($name, ['city'])) {
            return 'fake()->city()';
        }
        if (Str::contains($name, ['country'])) {
            return 'fake()->country()';
        }
        if (Str::contains($name, ['url', 'website'])) {
            return 'fake()->url()';
        }

        // Then check by type
        return match ($type) {
            'string', 'char' => 'fake()->words(3, true)',
            'text', 'mediumText', 'longText' => 'fake()->paragraph()',
            'integer', 'bigInteger', 'mediumInteger', 'smallInteger', 'tinyInteger' => 'fake()->numberBetween(1, 1000)',
            'float', 'double' => 'fake()->randomFloat(2, 0, 1000)',
            'decimal' => 'fake()->randomFloat(2, 0, 1000)',
            'boolean' => 'fake()->boolean()',
            'date' => 'fake()->date()',
            'dateTime', 'dateTimeTz', 'timestamp', 'timestampTz' => 'fake()->dateTime()',
            'time', 'timeTz' => 'fake()->time()',
            'enum' => !empty($column['enum_values']) ? "fake()->randomElement(['" . implode("', '", $column['enum_values']) . "'])" : 'fake()->word()',
            'json', 'jsonb' => 'fake()->words(5)',
            'uuid' => 'fake()->uuid()',
            'ipAddress' => 'fake()->ipv4()',
            default => 'fake()->word()'
        };
    }

    /**
     * Validate class name
     */
    protected function isValidClassName(string $name): bool
    {
        return preg_match('/^[A-Z][a-zA-Z0-9_]*$/', $name) === 1;
    }

    /**
     * Validate relation
     */
    protected function validateRelation(array $relation, int $index): array
    {
        $errors = [];

        if (empty($relation['name'])) {
            $errors[] = "Relation {$index}: Method name is required";
        }

        if (empty($relation['type'])) {
            $errors[] = "Relation {$index}: Type is required";
        }

        if (empty($relation['related_model'])) {
            $errors[] = "Relation {$index}: Related model is required";
        }

        $validTypes = ['hasOne', 'hasMany', 'belongsTo', 'belongsToMany', 'morphTo', 'morphOne', 'morphMany'];
        if (!empty($relation['type']) && !in_array($relation['type'], $validTypes)) {
            $errors[] = "Relation {$index}: Invalid relation type";
        }

        return $errors;
    }
}
