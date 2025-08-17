<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use HkDevs\CodeForgeStudio\Services\ModelGeneratorService;
use HkDevs\CodeForgeStudio\Services\IntelligentSuggestionService;

/**
 * ModelGeneratorPage
 * 
 * Advanced Laravel Eloquent model generator with intelligent feature suggestion
 * and comprehensive code generation capabilities powered by dynamic database analysis.
 * 
 * Key Features:
 * - Complete Eloquent model generation with all Laravel features
 * - Intelligent field analysis using real database schema introspection
 * - Dynamic relationship discovery from foreign keys and naming patterns
 * - Smart casting suggestions based on actual column data types
 * - Automated security field detection (hidden fields)
 * - Performance-optimized suggestion algorithms
 * 
 * Intelligent Suggestions:
 * - Database-driven fillable field suggestions based on actual table columns
 * - Foreign key relationship detection with proper method generation
 * - Data type-aware casting configuration for optimal performance
 * - Security-conscious hidden field suggestions for sensitive data
 * - Cross-table relationship pattern recognition
 * - Industry best practice integration
 * 
 * Model Configuration:
 * - Namespace and class name customization
 * - Table name mapping and connection configuration
 * - Fillable, guarded, hidden, and visible field management
 * - Casting configuration for proper data type handling
 * - Timestamp and soft delete support
 * 
 * Relationship Management:
 * - HasOne, HasMany, BelongsTo, BelongsToMany relationships
 * - Polymorphic relationship support
 * - Through relationship configuration
 * - Pivot table and attribute management
 * - Relationship method generation with proper return types
 * 
 * Advanced Features:
 * - Query scope generation for common filters
 * - Mutator and accessor generation for data transformation
 * - Custom method generation for business logic
 * - Event listener and observer integration
 * - Factory integration for testing support
 * 
 * Code Quality:
 * - PSR-12 compliant code generation
 * - Proper type hints and return types
 * - DocBlock generation for methods and properties
 * - Laravel best practices implementation
 * 
 * Integration:
 * - Extends BaseGeneratorPage for workflow management
 * - ModelGeneratorService for generation logic
 * - IntelligentSuggestionService for dynamic analysis
 * - SchemaAnalyzerService for database introspection
 * - Template service for code generation
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class ModelGeneratorPage extends BaseGeneratorPage
{
    protected static string $view = 'codeforge-database-studio::pages.model-generator';
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $title = 'Model Generator';
    protected static ?string $navigationLabel = 'Model Generator';
    protected static ?int $navigationSort = 2;

    protected function initializeConfiguration(): void
    {
        $this->generationConfig = [
            'enabled' => true,
            'name' => '',
            'table_name' => '',
            'namespace' => 'App\\Models',
            'extends' => 'Model',
            'traits' => ['HasFactory'],
            'fillable' => [],
            'guarded' => [],
            'hidden' => [],
            'visible' => [],
            'casts' => [],
            'dates' => [],
            'relations' => [],
            'scopes' => [],
            'mutators' => [],
            'accessors' => [],
            'custom_methods' => [],
            'event_listeners' => [],
            'observers' => [],
            'timestamps' => true,
            'soft_deletes' => false,
            'use_uuid' => false,
            'route_key_name' => null,
            'per_page' => null,
            'connection' => null,
            'with' => [],
            'without' => [],
            'appends' => [],
        ];
    }

    protected function getGeneratorService(): ModelGeneratorService
    {
        return app(ModelGeneratorService::class);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Model Name')
                            ->helperText('Enter the name of your model in PascalCase (e.g., User, BlogPost, ProductCategory)')
                            ->placeholder('e.g., User, Product, Order')
                            ->required()
                            ->live(debounce: 300)
                            ->afterStateUpdated(function ($state) {
                                if ($state) {
                                    $this->autoSuggestNames($state);
                                }
                            })
                            ->rule(function ($get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if ($value && preg_match('/^[A-Z][a-zA-Z0-9]*$/', $value)) {
                                        $namespace = $get('namespace') ?: 'App\\Models';
                                        $modelPath = $this->getModelFilePath($value, $namespace);
                                        $overwriteError = $this->wouldOverwriteFile($modelPath, 'model');
                                        if ($overwriteError) {
                                            $fail($overwriteError);
                                        }
                                    }
                                };
                            }),

                        Forms\Components\TextInput::make('table_name')
                            ->label('Table Name')
                            ->helperText('The database table name. Leave empty to auto-generate from model name (e.g., User becomes "users")')
                            ->placeholder('Leave empty to auto-generate'),

                        Forms\Components\TextInput::make('namespace')
                            ->label('Namespace')
                            ->helperText('The PHP namespace for your model. Standard Laravel convention is App\\Models')
                            ->default('App\\Models')
                            ->required(),

                        Forms\Components\TextInput::make('extends')
                            ->label('Extends')
                            ->helperText('The base class your model extends. Usually "Model" for standard Eloquent models')
                            ->default('Model')
                            ->required(),
                    ]),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Toggle::make('timestamps')
                            ->label('Use Timestamps')
                            ->helperText('Automatically manage created_at and updated_at columns')
                            ->default(true),

                        Forms\Components\Toggle::make('soft_deletes')
                            ->label('Use Soft Deletes')
                            ->helperText('Enable soft deleting with deleted_at column instead of permanent deletion'),

                        Forms\Components\Toggle::make('use_uuid')
                            ->label('Use UUID Primary Key')
                            ->helperText('Use UUID strings instead of auto-incrementing integers for primary keys'),
                    ]),

                Section::make('Model Properties')
                    ->description('Configure which attributes can be mass-assigned and which should be hidden from serialization')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TagsInput::make('traits')
                            ->label('Traits')
                            ->helperText('PHP traits to include in your model for additional functionality')
                            ->default(['HasFactory'])
                            ->suggestions([
                                'HasFactory',
                                'SoftDeletes',
                                'HasUuids',
                                'Notifiable',
                                'HasApiTokens',
                                'MustVerifyEmail',
                            ]),

                        Forms\Components\TagsInput::make('fillable')
                            ->label('Fillable Fields')
                            ->helperText('Attributes that can be mass-assigned using create() or update() methods')
                            ->placeholder('Add fillable attributes'),

                        Forms\Components\TagsInput::make('hidden')
                            ->label('Hidden Fields')
                            ->helperText('Attributes that will be hidden when the model is converted to array/JSON')
                            ->placeholder('Add hidden attributes')
                            ->suggestions(['password', 'remember_token', 'api_token']),

                        Forms\Components\TagsInput::make('guarded')
                            ->label('Guarded Fields')
                            ->helperText('Attributes that are protected from mass assignment (opposite of fillable)')
                            ->placeholder('Add guarded attributes'),
                    ]),

                Section::make('Casts & Dates')
                    ->description('Configure how attributes are cast when accessing them and specify date fields for automatic Carbon conversion')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Repeater::make('casts')
                            ->label('Attribute Casts')
                            ->helperText('Define how database values should be cast to PHP types when accessed')
                            ->schema([
                                Forms\Components\TextInput::make('attribute')
                                    ->label('Attribute')
                                    ->helperText('The database column name')
                                    ->required(),
                                Forms\Components\Select::make('cast')
                                    ->label('Cast Type')
                                    ->helperText('How to convert the database value')
                                    ->options([
                                        'array' => 'Array - JSON to PHP array',
                                        'boolean' => 'Boolean - 1/0 to true/false',
                                        'collection' => 'Collection - JSON to Laravel Collection',
                                        'date' => 'Date - String to Carbon date',
                                        'datetime' => 'DateTime - String to Carbon datetime',
                                        'decimal' => 'Decimal - String to decimal number',
                                        'double' => 'Double - String to double',
                                        'encrypted' => 'Encrypted - Auto encrypt/decrypt',
                                        'float' => 'Float - String to float',
                                        'hashed' => 'Hashed - Auto hash on save',
                                        'integer' => 'Integer - String to integer',
                                        'json' => 'JSON - String to JSON',
                                        'object' => 'Object - JSON to PHP object',
                                        'real' => 'Real - String to real number',
                                        'string' => 'String - Force to string',
                                        'timestamp' => 'Timestamp - Unix timestamp to Carbon',
                                    ])
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Cast')
                            ->defaultItems(0),

                        Forms\Components\TagsInput::make('dates')
                            ->label('Date Fields')
                            ->helperText('Attributes that should be automatically converted to Carbon instances')
                            ->placeholder('Add date attributes'),
                    ]),

                Section::make('Relationships')
                    ->description('Define how this model relates to other models in your application. Relationships help you retrieve related data efficiently and maintain data integrity.')
                    ->schema([
                        Forms\Components\Repeater::make('relations')
                            ->label('Model Relations')
                            ->helperText('Add relationships to connect this model with other models. Each relationship defines how data is linked between tables.')
                            ->schema($this->getRelationSchema())
                            ->columnSpanFull()
                            ->live(debounce: 300)
                            ->addActionLabel('Add Relation')
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                $modelName = $this->generationConfig['name'] ?? 'Model';
                                $relationName = $state['name'] ?? '';
                                $relationType = $state['type'] ?? '';
                                $relatedModel = $state['related_model'] ?? '';

                                if (!$relationName || !$relationType || !$relatedModel) {
                                    return 'New Relationship';
                                }

                                return $this->formatRelationshipTitle($modelName, $relationName, $relationType, $relatedModel);
                            }),
                    ]),

                Section::make('Advanced Features')
                    ->description('Add query scopes, mutators, accessors, and custom methods to enhance your model functionality')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Repeater::make('scopes')
                            ->label('Query Scopes')
                            ->helperText('Create reusable query constraints that can be chained with other queries')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Scope Name')
                                    ->helperText('Name without "scope" prefix (e.g., "active" creates "scopeActive")')
                                    ->required(),
                                Forms\Components\Textarea::make('body')
                                    ->label('Scope Body')
                                    ->helperText('Return the modified query (e.g., "return $query->where(\'active\', true);")')
                                    ->placeholder('return $query->where(...);')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add Scope')
                            ->defaultItems(0),

                        Forms\Components\Repeater::make('mutators')
                            ->label('Mutators')
                            ->helperText('Transform attribute values when saving to the database')
                            ->schema([
                                Forms\Components\TextInput::make('attribute')
                                    ->label('Attribute')
                                    ->helperText('The attribute name (e.g., "name" creates "setNameAttribute")')
                                    ->required(),
                                Forms\Components\Textarea::make('body')
                                    ->label('Mutator Body')
                                    ->helperText('Transform the value before saving (e.g., "$this->attributes[\'name\'] = ucfirst($value);")')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add Mutator')
                            ->defaultItems(0),

                        Forms\Components\Repeater::make('accessors')
                            ->label('Accessors')
                            ->helperText('Transform attribute values when retrieving from the database')
                            ->schema([
                                Forms\Components\TextInput::make('attribute')
                                    ->label('Attribute')
                                    ->helperText('The attribute name (e.g., "name" creates "getNameAttribute")')
                                    ->required(),
                                Forms\Components\Textarea::make('body')
                                    ->label('Accessor Body')
                                    ->helperText('Transform the value when accessing (e.g., "return ucfirst($this->attributes[\'name\']);")')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add Accessor')
                            ->defaultItems(0),

                        Forms\Components\Repeater::make('custom_methods')
                            ->label('Custom Methods')
                            ->helperText('Add custom business logic methods to your model')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Method Name')
                                    ->helperText('The name of your custom method')
                                    ->required(),
                                Forms\Components\Select::make('visibility')
                                    ->label('Visibility')
                                    ->helperText('Method visibility level')
                                    ->options([
                                        'public' => 'Public - Accessible from outside the class',
                                        'protected' => 'Protected - Accessible from this class and subclasses',
                                        'private' => 'Private - Accessible only from this class',
                                    ])
                                    ->default('public'),
                                Forms\Components\Repeater::make('parameters')
                                    ->label('Parameters')
                                    ->helperText('Method parameters')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Parameter Name')
                                            ->required(),
                                        Forms\Components\TextInput::make('type')
                                            ->label('Type Hint')
                                            ->placeholder('string, int, array, etc.'),
                                        Forms\Components\TextInput::make('default')
                                            ->label('Default Value')
                                            ->placeholder('null, "", 0, etc.'),
                                    ])
                                    ->columns(3)
                                    ->addActionLabel('Add Parameter')
                                    ->defaultItems(0),
                                Forms\Components\Textarea::make('body')
                                    ->label('Method Body')
                                    ->helperText('The PHP code inside the method')
                                    ->required(),
                                Forms\Components\TextInput::make('return_type')
                                    ->label('Return Type')
                                    ->helperText('The return type hint for the method')
                                    ->placeholder('string, array, Model, etc.'),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add Method')
                            ->defaultItems(0),
                    ]),
            ])
            ->statePath('generationConfig');
    }

    protected function getRelationSchema(): array
    {
        return [
            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Relation Name')
                        ->helperText('The method name that will be created in your model (e.g., "posts", "user", "tags")')
                        ->placeholder('e.g., user, posts, categories')
                        ->required(),

                    Forms\Components\Select::make('type')
                        ->label('Relation Type')
                        ->helperText('Choose the type of relationship between models')
                        ->options([
                            'hasOne' => 'Has One',
                            'hasMany' => 'Has Many',
                            'belongsTo' => 'Belongs To',
                            'belongsToMany' => 'Belongs To Many',
                            'hasOneThrough' => 'Has One Through',
                            'hasManyThrough' => 'Has Many Through',
                            'morphOne' => 'Morph One',
                            'morphMany' => 'Morph Many',
                            'morphTo' => 'Morph To',
                            'morphToMany' => 'Morph To Many',
                            'morphedByMany' => 'Morphed By Many',
                        ])
                        ->live(debounce: 300)
                        ->required(),

                    Forms\Components\TextInput::make('related_model')
                        ->label('Related Model')
                        ->helperText('The name of the model you want to relate to (e.g., User, Post, Category)')
                        ->placeholder('e.g., User, Post, Category')
                        ->required(),
                ]),

            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('foreign_key')
                        ->label('Foreign Key')
                        ->helperText('The column that links to the related model. Leave empty for auto-generation based on naming conventions.')
                        ->placeholder('Optional - auto-generated if empty'),

                    Forms\Components\TextInput::make('local_key')
                        ->label('Local Key')
                        ->helperText('The column in this model that the foreign key references. Usually "id" for primary keys.')
                        ->placeholder('Optional - defaults to id'),

                    Forms\Components\TextInput::make('pivot_table')
                        ->label('Pivot Table')
                        ->helperText('Required for many-to-many relationships. The intermediate table that connects both models.')
                        ->placeholder('For many-to-many relations')
                        ->visible(fn($get) => in_array($get('type'), ['belongsToMany', 'morphToMany', 'morphedByMany'])),
                ]),
        ];
    }

    protected function validateConfiguration(): array
    {
        $errors = [];

        if (empty($this->generationConfig['name'])) {
            $errors[] = 'Model name is required.';
        } elseif (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $this->generationConfig['name'])) {
            $errors[] = 'Model name must be a valid PHP class name (PascalCase).';
        } else {
            // Check if model file already exists
            $modelName = $this->generationConfig['name'];
            $namespace = $this->generationConfig['namespace'] ?? 'App\\Models';
            $modelPath = $this->getModelFilePath($modelName, $namespace);

            $overwriteError = $this->wouldOverwriteFile($modelPath, 'model');
            if ($overwriteError) {
                $errors[] = $overwriteError;
            }

            // Check for reserved names
            $reservedError = $this->isReservedName($modelName, 'class');
            if ($reservedError) {
                $errors[] = $reservedError;
            }

            // Check for case-insensitive conflicts
            $caseError = $this->checkCaseInsensitiveConflicts($modelPath, $modelName, 'model');
            if ($caseError) {
                $errors[] = $caseError;
            }
        }

        if (!empty($this->generationConfig['namespace']) && !preg_match('/^[A-Z][a-zA-Z0-9\\\\]*$/', $this->generationConfig['namespace'])) {
            $errors[] = 'Namespace must be a valid PHP namespace.';
        }

        // Validate relations
        foreach ($this->generationConfig['relations'] ?? [] as $index => $relation) {
            if (empty($relation['name'])) {
                $errors[] = "Relation #" . ($index + 1) . ": Name is required.";
            }

            if (empty($relation['type'])) {
                $errors[] = "Relation #" . ($index + 1) . ": Type is required.";
            }

            if (empty($relation['related_model'])) {
                $errors[] = "Relation #" . ($index + 1) . ": Related model is required.";
            }
        }

        return $errors;
    }

    /**
     * Get the file path for a model based on name and namespace
     */
    protected function getModelFilePath(string $modelName, string $namespace): string
    {
        $namespacePath = $this->namespaceToPath($namespace);
        return base_path($namespacePath . '/' . $modelName . '.php');
    }

    /**
     * Format a human-readable relationship title for the repeater item
     */
    protected function formatRelationshipTitle(string $modelName, string $relationName, string $relationType, string $relatedModel): string
    {
        switch ($relationType) {
            case 'hasOne':
                return "{$modelName} has one {$relatedModel}";

            case 'hasMany':
                return "{$modelName} has many {$relatedModel}";

            case 'belongsTo':
                return "{$modelName} belongs to {$relatedModel}";

            case 'belongsToMany':
                return "{$modelName} ↔ {$relatedModel} (Many-to-Many)";

            case 'hasOneThrough':
                return "{$modelName} has one {$relatedModel} (through)";

            case 'hasManyThrough':
                return "{$modelName} has many {$relatedModel} (through)";

            case 'morphOne':
                return "{$modelName} → {$relatedModel} (Polymorphic One)";

            case 'morphMany':
                return "{$modelName} → {$relatedModel} (Polymorphic Many)";

            case 'morphTo':
                return "{$modelName} → {$relatedModel} (Morph To)";

            case 'morphToMany':
                return "{$modelName} ↔ {$relatedModel} (Polymorphic Many-to-Many)";

            case 'morphedByMany':
                return "{$modelName} ← {$relatedModel} (Morphed By Many)";

            default:
                return "{$relationName}: {$modelName} → {$relatedModel}";
        }
    }
    protected function autoSuggestNames(string $modelName, ?string $tableName = null): void
    {
        // Auto-suggest table name
        if (empty($this->generationConfig['table_name'])) {
            $this->generationConfig['table_name'] = str()->snake(str()->plural($modelName));
        }

        // Use the intelligent suggestion service for dynamic suggestions
        $suggestionService = app(IntelligentSuggestionService::class);
        $suggestions = $suggestionService->getModelSuggestions($modelName, $this->generationConfig['table_name']);

        // Auto-suggest fillable fields based on actual table analysis
        if (empty($this->generationConfig['fillable'])) {
            $this->generationConfig['fillable'] = $suggestions['fillable'];
        }

        // Auto-suggest hidden fields for security
        if (empty($this->generationConfig['hidden'])) {
            $this->generationConfig['hidden'] = $suggestions['hidden'];
        }

        // Auto-suggest casting based on column types
        if (empty($this->generationConfig['casts'])) {
            $this->generationConfig['casts'] = $suggestions['casts'];
        }

        // Auto-suggest date fields
        if (empty($this->generationConfig['dates'])) {
            $this->generationConfig['dates'] = $suggestions['dates'];
        }

        // Auto-suggest relationships based on foreign key analysis
        if (empty($this->generationConfig['relations'])) {
            $this->generationConfig['relations'] = $suggestions['relations'];
        }

        // Auto-suggest traits based on table structure and model purpose
        if (empty($this->generationConfig['traits']) || $this->generationConfig['traits'] === ['HasFactory']) {
            $this->generationConfig['traits'] = $suggestions['traits'];
        }
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Code Generators';
    }
}
