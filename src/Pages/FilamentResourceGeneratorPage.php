<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use HkDevs\CodeForgeStudio\Services\FilamentResourceGeneratorService;

/**
 * FilamentResourceGeneratorPage
 * 
 * Advanced generator for creating complete Filament admin resources with
 * forms, tables, and CRUD functionality based on model analysis.
 * 
 * Key Features:
 * - Complete Filament resource generation with forms and tables
 * - Intelligent field type mapping for optimal UI components
 * - Relationship handling with proper form field generation
 * - Action generation for common CRUD operations
 * - Filter and search functionality generation
 * - Permission integration and access control setup
 * 
 * Resource Generation:
 * - Model analysis for automatic resource structure
 * - Form builder with appropriate field components
 * - Table builder with columns, filters, and actions
 * - Resource page generation (List, Create, Edit, View)
 * - Navigation integration and menu structure
 * 
 * Advanced Features:
 * - Custom action generation for specialized operations
 * - Bulk action support for batch operations
 * - Advanced filter generation based on field types
 * - Search functionality with intelligent field selection
 * - Export capabilities and data formatting
 * - Widget integration for dashboard components
 * 
 * UI Components:
 * - Automatic field component selection based on data types
 * - Relationship components for foreign key fields
 * - File upload components for file and image fields
 * - Rich text editors for text content
 * - Date/time pickers with appropriate formats
 * 
 * Configuration Options:
 * - Model selection and resource naming
 * - Field inclusion and customization
 * - Action selection and configuration
 * - Navigation group and positioning
 * - Permission and access control setup
 * 
 * Integration:
 * - Extends BaseGeneratorPage for workflow management
 * - FilamentResourceGeneratorService for generation logic
 * - Model introspection for intelligent defaults
 * - Template service for code generation
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @property array|null $generationConfig
 */
class FilamentResourceGeneratorPage extends BaseGeneratorPage
{
    protected static string $view = 'codeforge-database-studio::pages.filament-resource-generator';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $title = 'Filament Resource Generator';
    protected static ?string $navigationLabel = 'Filament Resource';
    protected static ?int $navigationSort = 5;

    // Step-based properties for the wizard
    public string $currentStep = 'select_source';
    public ?string $sourceType = null;
    public ?string $selectedModel = null;
    public ?string $selectedMigration = null;
    public array $availableModels = [];
    public array $availableMigrations = [];

    // Configuration arrays that the view expects
    public array $formConfiguration = ['fields' => []];
    public array $tableConfiguration = ['columns' => []];
    public array $filterConfiguration = ['filters' => []];

    public function mount(): void
    {
        $this->initializeConfiguration();
        $this->currentStep = 'select_source';
        $this->isGenerating = false;
        $this->dispatch('isGeneratingChanged', false);
    }

    protected function initializeConfiguration(): void
    {
        $this->generationConfig = [
            'enabled' => true,
            'class_name' => '',
            'model' => '',
            'namespace' => 'App\\Filament\\Resources',
            'navigation_icon' => 'heroicon-o-rectangle-stack',
            'navigation_label' => '',
            'navigation_group' => null,
            'navigation_sort' => null,
            'slug' => '',
            'pages' => ['index', 'create', 'edit', 'view'],
            'table_columns' => [],
            'form_fields' => [],
            'filters' => [],
            'actions' => [],
            'bulk_actions' => [],
            'widgets' => [],
            'relations' => [],
            'enable_global_search' => true,
            'searchable_fields' => [],
            'default_sort' => ['id', 'desc'],
        ];

        // Initialize available models and migrations
        $this->loadAvailableModels();
        $this->loadAvailableMigrations();

        // Initialize form and table configurations
        $this->syncConfigurationArrays();
    }

    protected function syncConfigurationArrays(): void
    {
        // Sync form fields
        $this->formConfiguration['fields'] = $this->generationConfig['form_fields'] ?? [];

        // Sync table columns
        $this->tableConfiguration['columns'] = $this->generationConfig['table_columns'] ?? [];

        // Sync filters
        $this->filterConfiguration['filters'] = $this->generationConfig['filters'] ?? [];
    }

    public function selectSourceType(string $type): void
    {
        $this->sourceType = $type;
        $this->selectedModel = null;
        $this->selectedMigration = null;
    }

    public function selectModel(string $modelClass): void
    {
        $this->selectedModel = $modelClass;
        $this->currentStep = 'configure_resource';

        // Auto-populate configuration based on selected model
        $this->autoConfigureFromModel($modelClass);
    }

    public function selectMigration(string $migrationFile): void
    {
        $this->selectedMigration = $migrationFile;
        $this->currentStep = 'configure_resource';

        // Auto-populate configuration based on selected migration
        $this->autoConfigureFromMigration($migrationFile);
    }

    protected function loadAvailableModels(): void
    {
        $this->availableModels = $this->getModelsWithoutResources();
    }

    protected function loadAvailableMigrations(): void
    {
        $this->availableMigrations = $this->getAvailableMigrations();
    }

    public function getModelsWithoutResources(): array
    {
        $models = [];
        $modelPath = app_path('Models');

        if (!is_dir($modelPath)) {
            return $models;
        }

        $files = glob($modelPath . '/*.php');

        foreach ($files as $file) {
            $fileName = basename($file, '.php');
            $className = 'App\\Models\\' . $fileName;

            if (class_exists($className)) {
                $models[] = [
                    'name' => $fileName,
                    'class' => $className,
                    'file' => $file,
                ];
            }
        }

        return $models;
    }

    public function getAvailableMigrations(): array
    {
        $migrations = [];
        $migrationPath = database_path('migrations');

        if (!is_dir($migrationPath)) {
            return $migrations;
        }

        $files = glob($migrationPath . '/*.php');

        foreach ($files as $file) {
            $fileName = basename($file);
            if (str_contains($fileName, 'create_') && str_contains($fileName, '_table')) {
                $tableName = $this->extractTableNameFromMigration($fileName);
                $migrations[] = [
                    'name' => $fileName,
                    'table' => $tableName,
                    'file' => $file,
                ];
            }
        }

        return $migrations;
    }

    protected function extractTableNameFromMigration(string $fileName): string
    {
        // Extract table name from migration file name like "2023_01_01_000000_create_users_table.php"
        preg_match('/create_(.+)_table/', $fileName, $matches);
        return $matches[1] ?? '';
    }

    protected function autoConfigureFromModel(string $modelClass): void
    {
        $modelName = class_basename($modelClass);
        $this->generationConfig['model'] = $modelClass;
        $this->generationConfig['class_name'] = $modelName . 'Resource';
        $this->autoSuggestNames($this->generationConfig['class_name']);
    }

    protected function autoConfigureFromMigration(string $migrationFile): void
    {
        $tableName = $this->extractTableNameFromMigration(basename($migrationFile));
        $modelName = str(str($tableName)->singular())->studly();

        $this->generationConfig['model'] = 'App\\Models\\' . $modelName;
        $this->generationConfig['class_name'] = $modelName . 'Resource';
        $this->autoSuggestNames($this->generationConfig['class_name'], $tableName);
    }

    public function setStep(string $step): void
    {
        $this->currentStep = $step;
    }

    public function previewResource(): void
    {
        $this->currentStep = 'preview';
        $this->generatePreview();
    }

    public function generateResource(): void
    {
        $this->generateFiles();
        $this->currentStep = 'generation_complete';
    }

    public function resetWizard(): void
    {
        $this->currentStep = 'select_source';
        $this->sourceType = null;
        $this->selectedModel = null;
        $this->selectedMigration = null;
        $this->resetConfiguration();
    }

    public function addFormField(): void
    {
        $newField = [
            'name' => '',
            'type' => 'textInput',
            'label' => '',
            'required' => false,
            'disabled' => false,
            'placeholder' => '',
            'validation' => '',
            'options' => '',
        ];

        $this->generationConfig['form_fields'][] = $newField;
        $this->formConfiguration['fields'][] = $newField;
    }

    public function removeFormField(int $index): void
    {
        if (isset($this->generationConfig['form_fields'][$index])) {
            unset($this->generationConfig['form_fields'][$index]);
            $this->generationConfig['form_fields'] = array_values($this->generationConfig['form_fields']);
        }

        if (isset($this->formConfiguration['fields'][$index])) {
            unset($this->formConfiguration['fields'][$index]);
            $this->formConfiguration['fields'] = array_values($this->formConfiguration['fields']);
        }
    }

    public function addTableColumn(): void
    {
        $newColumn = [
            'name' => '',
            'type' => 'text',
            'label' => '',
            'sortable' => false,
            'searchable' => false,
            'toggleable' => false,
            'options' => '',
        ];

        $this->generationConfig['table_columns'][] = $newColumn;
        $this->tableConfiguration['columns'][] = $newColumn;
    }

    public function removeTableColumn(int $index): void
    {
        if (isset($this->generationConfig['table_columns'][$index])) {
            unset($this->generationConfig['table_columns'][$index]);
            $this->generationConfig['table_columns'] = array_values($this->generationConfig['table_columns']);
        }

        if (isset($this->tableConfiguration['columns'][$index])) {
            unset($this->tableConfiguration['columns'][$index]);
            $this->tableConfiguration['columns'] = array_values($this->tableConfiguration['columns']);
        }
    }

    public function addFilter(): void
    {
        $newFilter = [
            'name' => '',
            'type' => 'text',
            'configuration' => '',
        ];

        $this->generationConfig['filters'][] = $newFilter;
        $this->filterConfiguration['filters'][] = $newFilter;
    }

    public function removeFilter(int $index): void
    {
        if (isset($this->generationConfig['filters'][$index])) {
            unset($this->generationConfig['filters'][$index]);
            $this->generationConfig['filters'] = array_values($this->generationConfig['filters']);
        }

        if (isset($this->filterConfiguration['filters'][$index])) {
            unset($this->filterConfiguration['filters'][$index]);
            $this->filterConfiguration['filters'] = array_values($this->filterConfiguration['filters']);
        }
    }

    public function getExistingResources(): array
    {
        $resources = [];
        $resourcePath = app_path('Filament/Resources');

        if (!is_dir($resourcePath)) {
            return $resources;
        }

        $files = glob($resourcePath . '/*Resource.php');

        foreach ($files as $file) {
            $fileName = basename($file, '.php');
            $className = 'App\\Filament\\Resources\\' . $fileName;

            if (class_exists($className)) {
                $resources[] = [
                    'id' => count($resources) + 1,
                    'name' => $fileName,
                    'class' => $className,
                    'file' => $file,
                ];
            }
        }

        return $resources;
    }

    public function getFormFieldTypes(): array
    {
        return [
            'textInput' => 'Text Input',
            'textarea' => 'Textarea',
            'richEditor' => 'Rich Editor',
            'select' => 'Select',
            'checkbox' => 'Checkbox',
            'toggle' => 'Toggle',
            'radio' => 'Radio',
            'datePicker' => 'Date Picker',
            'timePicker' => 'Time Picker',
            'dateTimePicker' => 'DateTime Picker',
            'fileUpload' => 'File Upload',
            'colorPicker' => 'Color Picker',
            'keyValue' => 'Key-Value',
            'repeater' => 'Repeater',
            'tagsinput' => 'Tags Input',
        ];
    }

    public function getTableColumnTypes(): array
    {
        return [
            'text' => 'Text',
            'badge' => 'Badge',
            'boolean' => 'Boolean',
            'date' => 'Date',
            'datetime' => 'DateTime',
            'image' => 'Image',
            'icon' => 'Icon',
            'color' => 'Color',
            'toggle' => 'Toggle',
            'select' => 'Select',
            'tags' => 'Tags',
        ];
    }

    public function getFilterTypes(): array
    {
        return [
            'text' => 'Text Filter',
            'select' => 'Select Filter',
            'date' => 'Date Filter',
            'boolean' => 'Boolean Filter',
            'ternary' => 'Ternary Filter',
        ];
    }

    public function getAvailableTemplates(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Simple CRUD',
                'description' => 'Basic Create, Read, Update, Delete resource',
            ],
            [
                'id' => 2,
                'name' => 'Advanced Resource',
                'description' => 'Resource with filters, actions, and widgets',
            ],
            [
                'id' => 3,
                'name' => 'User Management',
                'description' => 'Resource optimized for user management',
            ],
        ];
    }

    public function editExistingResource(int $resourceId): void
    {
        $resources = $this->getExistingResources();
        $resource = collect($resources)->firstWhere('id', $resourceId);

        if ($resource) {
            $this->selectedModel = $resource['class'];
            $this->currentStep = 'configure_resource';
            // Load existing resource configuration
            $this->loadExistingResourceConfig($resource);
        }
    }

    public function applyTemplate(int $templateId): void
    {
        $templates = $this->getAvailableTemplates();
        $template = collect($templates)->firstWhere('id', $templateId);

        if ($template) {
            switch ($templateId) {
                case 1: // Simple CRUD
                    $this->applySimpleCrudTemplate();
                    break;
                case 2: // Advanced Resource
                    $this->applyAdvancedResourceTemplate();
                    break;
                case 3: // User Management
                    $this->applyUserManagementTemplate();
                    break;
            }
        }
    }

    protected function loadExistingResourceConfig(array $resource): void
    {
        // This would analyze the existing resource file and populate the configuration
        // For now, we'll just set basic defaults
        $modelName = str_replace(['App\\Filament\\Resources\\', 'Resource'], '', $resource['class']);
        $this->generationConfig['class_name'] = $resource['name'];
        $this->generationConfig['model'] = 'App\\Models\\' . $modelName;
    }

    protected function applySimpleCrudTemplate(): void
    {
        $this->generationConfig['pages'] = ['index', 'create', 'edit'];
        $this->generationConfig['table_columns'] = [
            ['name' => 'id', 'type' => 'text', 'sortable' => true],
            ['name' => 'created_at', 'type' => 'datetime', 'sortable' => true],
        ];
        $this->generationConfig['form_fields'] = [
            ['name' => 'name', 'type' => 'textInput', 'required' => true],
        ];
        $this->syncConfigurationArrays();
    }

    protected function applyAdvancedResourceTemplate(): void
    {
        $this->generationConfig['pages'] = ['index', 'create', 'edit', 'view'];
        $this->generationConfig['enable_global_search'] = true;
        $this->generationConfig['filters'] = [
            ['name' => 'status', 'type' => 'select'],
            ['name' => 'created_at', 'type' => 'date'],
        ];
        $this->generationConfig['actions'] = [
            ['name' => 'view', 'type' => 'view'],
            ['name' => 'edit', 'type' => 'edit'],
        ];
        $this->syncConfigurationArrays();
    }

    protected function applyUserManagementTemplate(): void
    {
        $this->generationConfig['table_columns'] = [
            ['name' => 'id', 'type' => 'text', 'sortable' => true],
            ['name' => 'name', 'type' => 'text', 'sortable' => true, 'searchable' => true],
            ['name' => 'email', 'type' => 'text', 'sortable' => true, 'searchable' => true],
            ['name' => 'email_verified_at', 'type' => 'datetime', 'sortable' => true],
            ['name' => 'created_at', 'type' => 'datetime', 'sortable' => true],
        ];
        $this->generationConfig['form_fields'] = [
            ['name' => 'name', 'type' => 'textInput', 'required' => true],
            ['name' => 'email', 'type' => 'textInput', 'required' => true, 'validation' => 'required|email|unique:users,email'],
            ['name' => 'password', 'type' => 'textInput', 'required' => true, 'validation' => 'required|min:8'],
        ];
        $this->syncConfigurationArrays();
    }

    protected function getGeneratorService()
    {
        return app(FilamentResourceGeneratorService::class);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Resource Configuration')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('class_name')
                                    ->label('Resource Class Name')
                                    ->placeholder('e.g., UserResource, ProductResource')
                                    ->required()
                                    ->live(debounce: 300)
                                    ->afterStateUpdated(function ($state) {
                                        if ($state) {
                                            $this->autoSuggestNames($state);
                                        }
                                    })
                                    ->rule(function ($get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                                            if ($value && preg_match('/^[A-Z][a-zA-Z0-9]*Resource$/', $value)) {
                                                $namespace = $get('namespace') ?: 'App\\Filament\\Resources';
                                                $resourcePath = $this->getResourceFilePath($value, $namespace);
                                                $overwriteError = $this->wouldOverwriteFile($resourcePath, 'resource');
                                                if ($overwriteError) {
                                                    $fail($overwriteError);
                                                }
                                            }
                                        };
                                    }),

                                Forms\Components\TextInput::make('model')
                                    ->label('Target Model')
                                    ->placeholder('e.g., User, Product, Order')
                                    ->required(),

                                Forms\Components\TextInput::make('namespace')
                                    ->label('Namespace')
                                    ->default('App\\Filament\\Resources')
                                    ->required(),

                                Forms\Components\TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->placeholder('Auto-generated if empty'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('navigation_label')
                                    ->label('Navigation Label')
                                    ->placeholder('Auto-generated if empty'),

                                Forms\Components\TextInput::make('navigation_group')
                                    ->label('Navigation Group')
                                    ->placeholder('Optional navigation group'),

                                Forms\Components\Select::make('navigation_icon')
                                    ->label('Navigation Icon')
                                    ->options($this->getHeroicons())
                                    ->default('heroicon-o-rectangle-stack')
                                    ->searchable(),

                                Forms\Components\TextInput::make('navigation_sort')
                                    ->label('Navigation Sort Order')
                                    ->numeric()
                                    ->placeholder('Optional sort order'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('enable_global_search')
                                    ->label('Enable Global Search')
                                    ->default(true),

                                Forms\Components\CheckboxList::make('pages')
                                    ->label('Generate Pages')
                                    ->options([
                                        'index' => 'Index (List)',
                                        'create' => 'Create',
                                        'edit' => 'Edit',
                                        'view' => 'View',
                                    ])
                                    ->default(['index', 'create', 'edit', 'view'])
                                    ->columns(2),
                            ]),

                        Forms\Components\TagsInput::make('searchable_fields')
                            ->label('Global Search Fields')
                            ->placeholder('Add searchable field names')
                            ->visible(fn(Forms\Get $get) => $get('enable_global_search')),

                        Section::make('Table Configuration')
                            ->schema([
                                Forms\Components\Repeater::make('table_columns')
                                    ->label('Table Columns')
                                    ->schema($this->getTableColumnSchema())
                                    ->columnSpanFull()
                                    ->addActionLabel('Add Column')
                                    ->defaultItems(0)
                                    ->collapsible(),
                            ]),

                        Section::make('Form Configuration')
                            ->schema([
                                Forms\Components\Repeater::make('form_fields')
                                    ->label('Form Fields')
                                    ->schema($this->getFormFieldSchema())
                                    ->columnSpanFull()
                                    ->addActionLabel('Add Field')
                                    ->defaultItems(0)
                                    ->collapsible(),
                            ]),

                        Section::make('Filters & Actions')
                            ->columns(2)
                            ->schema([
                                Forms\Components\Repeater::make('filters')
                                    ->label('Table Filters')
                                    ->schema($this->getFilterSchema())
                                    ->addActionLabel('Add Filter')
                                    ->defaultItems(0)
                                    ->collapsible(),

                                Forms\Components\Repeater::make('actions')
                                    ->label('Table Actions')
                                    ->schema($this->getActionSchema())
                                    ->addActionLabel('Add Action')
                                    ->defaultItems(0)
                                    ->collapsible(),

                                Forms\Components\Repeater::make('bulk_actions')
                                    ->label('Bulk Actions')
                                    ->schema($this->getBulkActionSchema())
                                    ->addActionLabel('Add Bulk Action')
                                    ->defaultItems(0)
                                    ->collapsible(),

                                Forms\Components\TagsInput::make('widgets')
                                    ->label('Resource Widgets')
                                    ->placeholder('Add widget class names'),
                            ]),

                        Section::make('Relations')
                            ->schema([
                                Forms\Components\TagsInput::make('relations')
                                    ->label('Relation Managers')
                                    ->placeholder('Add relation names to generate managers for')
                                    ->helperText('These will generate RelationManager classes'),
                            ]),
                    ]),
            ])
            ->statePath('generationConfig');
    }

    protected function getTableColumnSchema(): array
    {
        return [
            Forms\Components\Grid::make(4)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Column Name')
                        ->required(),

                    Forms\Components\Select::make('type')
                        ->label('Column Type')
                        ->options([
                            'text' => 'Text',
                            'badge' => 'Badge',
                            'boolean' => 'Boolean',
                            'date' => 'Date',
                            'datetime' => 'DateTime',
                            'image' => 'Image',
                            'icon' => 'Icon',
                            'color' => 'Color',
                            'toggle' => 'Toggle',
                            'select' => 'Select',
                            'tags' => 'Tags',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('label')
                        ->label('Label')
                        ->placeholder('Auto-generated if empty'),

                    Forms\Components\Toggle::make('sortable')
                        ->label('Sortable'),

                    Forms\Components\Toggle::make('searchable')
                        ->label('Searchable'),

                    Forms\Components\Toggle::make('toggleable')
                        ->label('Toggleable'),
                ]),

            Forms\Components\Textarea::make('options')
                ->label('Options/Configuration')
                ->placeholder('Additional column configuration')
                ->columnSpanFull(),
        ];
    }

    protected function getFormFieldSchema(): array
    {
        return [
            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Field Name')
                        ->required(),

                    Forms\Components\Select::make('type')
                        ->label('Field Type')
                        ->options([
                            'textInput' => 'Text Input',
                            'textarea' => 'Textarea',
                            'richEditor' => 'Rich Editor',
                            'select' => 'Select',
                            'checkbox' => 'Checkbox',
                            'toggle' => 'Toggle',
                            'radio' => 'Radio',
                            'datePicker' => 'Date Picker',
                            'timePicker' => 'Time Picker',
                            'dateTimePicker' => 'DateTime Picker',
                            'fileUpload' => 'File Upload',
                            'colorPicker' => 'Color Picker',
                            'keyValue' => 'Key-Value',
                            'repeater' => 'Repeater',
                            'tagsinput' => 'Tags Input',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('label')
                        ->label('Label')
                        ->placeholder('Auto-generated if empty'),

                    Forms\Components\Toggle::make('required')
                        ->label('Required'),

                    Forms\Components\Toggle::make('disabled')
                        ->label('Disabled'),

                    Forms\Components\TextInput::make('placeholder')
                        ->label('Placeholder'),
                ]),

            Forms\Components\Textarea::make('validation')
                ->label('Validation Rules')
                ->placeholder('e.g., required|string|max:255')
                ->columnSpanFull(),

            Forms\Components\Textarea::make('options')
                ->label('Options/Configuration')
                ->placeholder('Additional field configuration')
                ->columnSpanFull(),
        ];
    }

    protected function getFilterSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Filter Name')
                ->required(),

            Forms\Components\Select::make('type')
                ->label('Filter Type')
                ->options([
                    'text' => 'Text Filter',
                    'select' => 'Select Filter',
                    'date' => 'Date Filter',
                    'boolean' => 'Boolean Filter',
                    'ternary' => 'Ternary Filter',
                ])
                ->required(),

            Forms\Components\Textarea::make('configuration')
                ->label('Filter Configuration')
                ->placeholder('Additional filter configuration'),
        ];
    }

    protected function getActionSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Action Name')
                ->required(),

            Forms\Components\Select::make('type')
                ->label('Action Type')
                ->options([
                    'edit' => 'Edit Action',
                    'view' => 'View Action',
                    'delete' => 'Delete Action',
                    'custom' => 'Custom Action',
                ])
                ->required(),

            Forms\Components\Textarea::make('configuration')
                ->label('Action Configuration')
                ->placeholder('Additional action configuration'),
        ];
    }

    protected function getBulkActionSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Bulk Action Name')
                ->required(),

            Forms\Components\Select::make('type')
                ->label('Action Type')
                ->options([
                    'delete' => 'Delete Action',
                    'export' => 'Export Action',
                    'custom' => 'Custom Action',
                ])
                ->required(),

            Forms\Components\Textarea::make('configuration')
                ->label('Action Configuration')
                ->placeholder('Additional action configuration'),
        ];
    }

    protected function getHeroicons(): array
    {
        return [
            'heroicon-o-academic-cap' => 'Academic Cap',
            'heroicon-o-adjustments-horizontal' => 'Adjustments Horizontal',
            'heroicon-o-adjustments-vertical' => 'Adjustments Vertical',
            'heroicon-o-archive-box' => 'Archive Box',
            'heroicon-o-arrow-down' => 'Arrow Down',
            'heroicon-o-arrow-left' => 'Arrow Left',
            'heroicon-o-arrow-right' => 'Arrow Right',
            'heroicon-o-arrow-up' => 'Arrow Up',
            'heroicon-o-bars-3' => 'Bars 3',
            'heroicon-o-bell' => 'Bell',
            'heroicon-o-bookmark' => 'Bookmark',
            'heroicon-o-briefcase' => 'Briefcase',
            'heroicon-o-building-office' => 'Building Office',
            'heroicon-o-calendar' => 'Calendar',
            'heroicon-o-camera' => 'Camera',
            'heroicon-o-chart-bar' => 'Chart Bar',
            'heroicon-o-chat-bubble-left' => 'Chat Bubble Left',
            'heroicon-o-check' => 'Check',
            'heroicon-o-circle-stack' => 'Circle Stack',
            'heroicon-o-clipboard' => 'Clipboard',
            'heroicon-o-clock' => 'Clock',
            'heroicon-o-cloud' => 'Cloud',
            'heroicon-o-code-bracket' => 'Code Bracket',
            'heroicon-o-cog-6-tooth' => 'Cog 6 Tooth',
            'heroicon-o-command-line' => 'Command Line',
            'heroicon-o-computer-desktop' => 'Computer Desktop',
            'heroicon-o-cube' => 'Cube',
            'heroicon-o-currency-dollar' => 'Currency Dollar',
            'heroicon-o-document' => 'Document',
            'heroicon-o-envelope' => 'Envelope',
            'heroicon-o-eye' => 'Eye',
            'heroicon-o-face-smile' => 'Face Smile',
            'heroicon-o-film' => 'Film',
            'heroicon-o-finger-print' => 'Finger Print',
            'heroicon-o-fire' => 'Fire',
            'heroicon-o-flag' => 'Flag',
            'heroicon-o-folder' => 'Folder',
            'heroicon-o-gift' => 'Gift',
            'heroicon-o-globe-alt' => 'Globe Alt',
            'heroicon-o-heart' => 'Heart',
            'heroicon-o-home' => 'Home',
            'heroicon-o-identification' => 'Identification',
            'heroicon-o-inbox' => 'Inbox',
            'heroicon-o-key' => 'Key',
            'heroicon-o-light-bulb' => 'Light Bulb',
            'heroicon-o-link' => 'Link',
            'heroicon-o-list-bullet' => 'List Bullet',
            'heroicon-o-lock-closed' => 'Lock Closed',
            'heroicon-o-magnifying-glass' => 'Magnifying Glass',
            'heroicon-o-map' => 'Map',
            'heroicon-o-megaphone' => 'Megaphone',
            'heroicon-o-microphone' => 'Microphone',
            'heroicon-o-musical-note' => 'Musical Note',
            'heroicon-o-newspaper' => 'Newspaper',
            'heroicon-o-pencil' => 'Pencil',
            'heroicon-o-phone' => 'Phone',
            'heroicon-o-photo' => 'Photo',
            'heroicon-o-play' => 'Play',
            'heroicon-o-plus' => 'Plus',
            'heroicon-o-presentation-chart-line' => 'Presentation Chart Line',
            'heroicon-o-printer' => 'Printer',
            'heroicon-o-puzzle-piece' => 'Puzzle Piece',
            'heroicon-o-qr-code' => 'QR Code',
            'heroicon-o-question-mark-circle' => 'Question Mark Circle',
            'heroicon-o-rectangle-stack' => 'Rectangle Stack',
            'heroicon-o-rocket-launch' => 'Rocket Launch',
            'heroicon-o-scale' => 'Scale',
            'heroicon-o-server' => 'Server',
            'heroicon-o-shield-check' => 'Shield Check',
            'heroicon-o-shopping-bag' => 'Shopping Bag',
            'heroicon-o-shopping-cart' => 'Shopping Cart',
            'heroicon-o-sparkles' => 'Sparkles',
            'heroicon-o-squares-2x2' => 'Squares 2x2',
            'heroicon-o-star' => 'Star',
            'heroicon-o-table-cells' => 'Table Cells',
            'heroicon-o-tag' => 'Tag',
            'heroicon-o-ticket' => 'Ticket',
            'heroicon-o-trash' => 'Trash',
            'heroicon-o-trophy' => 'Trophy',
            'heroicon-o-truck' => 'Truck',
            'heroicon-o-tv' => 'TV',
            'heroicon-o-user' => 'User',
            'heroicon-o-user-group' => 'User Group',
            'heroicon-o-users' => 'Users',
            'heroicon-o-video-camera' => 'Video Camera',
            'heroicon-o-wallet' => 'Wallet',
            'heroicon-o-wifi' => 'WiFi',
            'heroicon-o-wrench-screwdriver' => 'Wrench Screwdriver',
            'heroicon-o-x-mark' => 'X Mark',
        ];
    }

    protected function validateConfiguration(): array
    {
        $errors = [];

        // Check if a source (model or migration) has been selected
        if (empty($this->selectedModel) && empty($this->selectedMigration)) {
            $errors[] = 'Please select a model or migration as the source for your resource.';
        }

        if (empty($this->generationConfig['class_name'])) {
            $errors[] = 'Resource class name is required.';
        } elseif (!preg_match('/^[A-Z][a-zA-Z0-9]*Resource$/', $this->generationConfig['class_name'])) {
            $errors[] = 'Resource class name must end with "Resource" and be a valid PHP class name.';
        } else {
            // Check if resource file already exists
            $className = $this->generationConfig['class_name'];
            $namespace = $this->generationConfig['namespace'] ?? 'App\\Filament\\Resources';
            $resourcePath = $this->getResourceFilePath($className, $namespace);

            $overwriteError = $this->wouldOverwriteFile($resourcePath, 'resource');
            if ($overwriteError) {
                $errors[] = $overwriteError;
            }

            // Check if any of the resource page files already exist
            $pageErrors = $this->checkResourcePageFiles($className, $namespace);
            $errors = array_merge($errors, $pageErrors);
        }
        if (empty($this->generationConfig['model'])) {
            $errors[] = 'Target model is required.';
        }

        if (empty($this->generationConfig['pages'])) {
            $errors[] = 'At least one page must be selected.';
        }

        return $errors;
    }

    /**
     * Get the file path for a Filament resource based on class name and namespace
     */
    protected function getResourceFilePath(string $className, string $namespace): string
    {
        $namespacePath = $this->namespaceToPath($namespace);
        return base_path($namespacePath . '/' . $className . '.php');
    }

    /**
     * Check if resource page files already exist
     */
    protected function checkResourcePageFiles(string $resourceClassName, string $namespace): array
    {
        $errors = [];
        $selectedPages = $this->generationConfig['pages'] ?? [];

        // Remove 'Resource' suffix to get base name
        $baseName = str_replace('Resource', '', $resourceClassName);

        foreach ($selectedPages as $pageType) {
            switch ($pageType) {
                case 'ListPage':
                    $pageClassName = "List{$baseName}";
                    break;
                case 'CreatePage':
                    $pageClassName = "Create{$baseName}";
                    break;
                case 'EditPage':
                    $pageClassName = "Edit{$baseName}";
                    break;
                case 'ViewPage':
                    $pageClassName = "View{$baseName}";
                    break;
                default:
                    continue 2; // Skip unknown page types
            }

            $pageNamespace = $namespace . '\\' . str_replace('Resource', '', $resourceClassName) . '\\Pages';
            $pagePath = $this->getResourcePageFilePath($pageClassName, $pageNamespace);

            $overwriteError = $this->wouldOverwriteFile($pagePath, 'resource page');
            if ($overwriteError) {
                $errors[] = $overwriteError;
            }
        }

        return $errors;
    }

    /**
     * Get the file path for a resource page
     */
    protected function getResourcePageFilePath(string $pageClassName, string $namespace): string
    {
        $namespacePath = $this->namespaceToPath($namespace);
        return base_path($namespacePath . '/' . $pageClassName . '.php');
    }
    protected function autoSuggestNames(string $className, ?string $tableName = null): void
    {
        // Extract model name from resource class name
        if (str_ends_with($className, 'Resource')) {
            $modelName = str_replace('Resource', '', $className);
            if (empty($this->generationConfig['model'])) {
                $this->generationConfig['model'] = 'App\\Models\\' . $modelName;
            }

            // Auto-suggest navigation label
            if (empty($this->generationConfig['navigation_label'])) {
                $this->generationConfig['navigation_label'] = str(str()->plural($modelName))->title();
            }

            // Auto-suggest slug
            if (empty($this->generationConfig['slug'])) {
                $this->generationConfig['slug'] = str(str()->plural($modelName))->kebab();
            }

            // Auto-suggest table columns and form fields
            if (empty($this->generationConfig['table_columns'])) {
                $this->generationConfig['table_columns'] = $this->getCommonTableColumns($modelName);
            }

            if (empty($this->generationConfig['form_fields'])) {
                $this->generationConfig['form_fields'] = $this->getCommonFormFields($modelName);
            }

            // Sync the configuration arrays
            $this->syncConfigurationArrays();
        }
    }

    protected function getCommonTableColumns(string $modelName): array
    {
        $commonColumns = [
            ['name' => 'id', 'type' => 'text', 'sortable' => true],
        ];

        if (str_contains(strtolower($modelName), 'user')) {
            $commonColumns = array_merge($commonColumns, [
                ['name' => 'name', 'type' => 'text', 'sortable' => true, 'searchable' => true],
                ['name' => 'email', 'type' => 'text', 'sortable' => true, 'searchable' => true],
                ['name' => 'email_verified_at', 'type' => 'datetime', 'sortable' => true],
                ['name' => 'created_at', 'type' => 'datetime', 'sortable' => true],
            ]);
        } elseif (str_contains(strtolower($modelName), 'product')) {
            $commonColumns = array_merge($commonColumns, [
                ['name' => 'name', 'type' => 'text', 'sortable' => true, 'searchable' => true],
                ['name' => 'price', 'type' => 'text', 'sortable' => true],
                ['name' => 'is_active', 'type' => 'boolean', 'sortable' => true],
                ['name' => 'created_at', 'type' => 'datetime', 'sortable' => true],
            ]);
        }

        return $commonColumns;
    }

    protected function getCommonFormFields(string $modelName): array
    {
        $commonFields = [];

        if (str_contains(strtolower($modelName), 'user')) {
            $commonFields = [
                ['name' => 'name', 'type' => 'textInput', 'required' => true],
                ['name' => 'email', 'type' => 'textInput', 'required' => true, 'validation' => 'required|email|unique:users,email'],
                ['name' => 'password', 'type' => 'textInput', 'required' => true, 'validation' => 'required|min:8'],
            ];
        } elseif (str_contains(strtolower($modelName), 'product')) {
            $commonFields = [
                ['name' => 'name', 'type' => 'textInput', 'required' => true],
                ['name' => 'description', 'type' => 'textarea'],
                ['name' => 'price', 'type' => 'textInput', 'required' => true, 'validation' => 'required|numeric|min:0'],
                ['name' => 'is_active', 'type' => 'toggle'],
            ];
        }

        return $commonFields;
    }

    public function getResourcePreviewData(): array
    {
        $resourceName = $this->generationConfig['class_name'] ?? 'ExampleResource';
        $modelName = '';

        if ($this->selectedModel) {
            $modelName = class_basename($this->selectedModel);
        } elseif (!empty($this->generationConfig['model'])) {
            $modelName = class_basename($this->generationConfig['model']);
        }

        if (empty($modelName)) {
            $modelName = str_replace('Resource', '', $resourceName);
        }

        return [
            'resource_name' => $resourceName,
            'model_name' => $modelName,
            'model_class' => $this->selectedModel ?? $this->generationConfig['model'] ?? 'Not selected',
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Code Generators';
    }
}
