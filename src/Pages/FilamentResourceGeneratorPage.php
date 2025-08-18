<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use HkDevs\CodeForgeStudio\Services\FilamentResourceGeneratorService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * FilamentResourceGeneratorPage
 * 
 * Comprehensive Filament resource generator with advanced field type configurations
 * and real-time code preview. Supports all Filament form components and table columns
 * with type-specific configuration panels and professional code generation.
 * 
 * Core Features:
 * - Model-only generation (no migration complexity)
 * - Real-time code preview with tabbed interface
 * - Intelligent model analysis and field suggestion
 * - Single-page workflow for better UX
 * - Always-visible designer sections
 * - Comprehensive relationship support
 * 
 * Form Field Types Supported:
 * - Text Input (min/max length, validation)
 * - Email Input (built-in validation)
 * - Password Input (revealable, confirmation, security)
 * - Number Input (min/max values, step, decimals)
 * - Textarea (rows, character limits)
 * - Select Dropdown (options array, multiple, searchable)
 * - Radio Buttons (options configuration)
 * - Checkbox/Toggle (default state, acceptance)
 * - Date/DateTime/Time Pickers (formats, ranges, timezone)
 * - File Upload (disk, directory, file types, size limits)
 * - Image Upload (preview, optimization, multiple files)
 * - Rich Text Editor (toolbar configuration, features)
 * - Markdown Editor (syntax highlighting, preview)
 * - Color Picker (format support, palettes)
 * - Hidden Fields (session data, tracking)
 * - Relationship Fields (all types: belongsTo, hasMany, belongsToMany, hasOne)
 * 
 * Table Column Types Supported:
 * - Text Columns (basic display, formatting)
 * - Badge Columns (color/icon mapping, dynamic styling)
 * - Boolean Columns (custom icons, color coding)
 * - Image Columns (size control, shapes, stacking)
 * - Date/DateTime Columns (formatting, timezone, relative time)
 * - Color Columns (size options, copy functionality)
 * - Money Columns (currency formatting)
 * - Relationship Columns (related data display, nested access)
 * 
 * Advanced Configuration Features:
 * - Dynamic configuration panels (show/hide based on field type)
 * - Type-specific properties (relevant options only)
 * - JSON-based options for complex configurations
 * - Real-time validation and preview updates
 * - Professional code generation with proper Filament syntax
 * - Comprehensive relationship handling
 * - File upload management with security controls
 * - Visual component configuration (colors, icons, shapes)
 * 
 * Technical Implementation:
 * - Livewire-powered reactive interface
 * - Component-based architecture
 * - Service layer for code generation
 * - Error handling and validation
 * - Performance optimized with intelligent caching
 * - Laravel best practices integration
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 2.0.0
 * @since 1.0.0 Basic resource generation
 * @since 2.0.0 Comprehensive field type configurations and relationship support
 */
class FilamentResourceGeneratorPage extends Page
{
    protected static string $view = 'codeforge-studio::pages.filament-resource-generator';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $title = 'Filament Resource Generator';
    protected static ?string $navigationLabel = 'Filament Resource';
    protected static ?int $navigationSort = 5;

    /**
     * Core Properties
     * 
     * @var string|null $selectedModel Currently selected Laravel model class
     * @var array $previewData Generated code preview data with file contents
     * @var bool $isGenerating Loading state during file generation process
     * @var int $activePreviewTab Currently active tab in preview interface
     */
    // Core properties
    public ?string $selectedModel = null;
    public array $previewData = [];
    public bool $isGenerating = false;
    public int $activePreviewTab = 0;

    /**
     * Designer Toggle States (Legacy - kept for compatibility)
     * Note: Designer sections are now always visible by default
     * 
     * @var bool $showFormDesigner Form designer visibility toggle
     * @var bool $showTableDesigner Table designer visibility toggle  
     * @var bool $showFilterDesigner Filter designer visibility toggle
     */
    // Designer toggles
    public bool $showFormDesigner = false;
    public bool $showTableDesigner = false;
    public bool $showFilterDesigner = false;

    /**
     * Configuration Arrays
     * 
     * @var array $formFields Form field configurations with type-specific properties
     * @var array $tableColumns Table column configurations with display options
     * @var array $filters Filter configurations for table searching/filtering
     * @var array $resourceSettings Global resource settings (navigation, icons, etc.)
     */
    // Configuration arrays
    public array $formFields = [];
    public array $tableColumns = [];
    public array $filters = [];
    public array $resourceSettings = [
        'navigation_icon' => 'heroicon-o-rectangle-stack',
        'navigation_group' => '',
        'navigation_sort' => null,
        'enable_view_page' => false,
        'enable_global_search' => true,
        'generate_policy' => false,
    ];
    public function mount(): void
    {
        $this->resetToDefaults();
    }

    public function resetToDefaults(): void
    {
        $this->selectedModel = null;
        $this->previewData = [];
        $this->formFields = [];
        $this->tableColumns = [];
        $this->filters = [];
        $this->activePreviewTab = 0;
        $this->showFormDesigner = false;
        $this->showTableDesigner = false;
        $this->showFilterDesigner = false;
    }

    public function getAvailableModelsProperty(): array
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
                    'value' => $className,
                    'label' => $fileName,
                    'description' => $this->getModelDescription($className),
                ];
            }
        }

        return $models;
    }

    protected function getModelDescription(string $modelClass): string
    {
        try {
            $reflection = new \ReflectionClass($modelClass);
            $model = new $modelClass;

            $table = $model->getTable() ?? 'unknown';
            $fillable = count($model->getFillable());

            return "Table: {$table} • {$fillable} fillable fields";
        } catch (\Exception $e) {
            return 'Model details unavailable';
        }
    }

    public function updatedSelectedModel($value): void
    {
        if (!$value) {
            $this->resetToDefaults();
            return;
        }

        $this->analyzeModel($value);
        $this->generatePreview();
    }

    /**
     * Analyze selected Laravel model and generate intelligent field suggestions
     * 
     * Performs comprehensive analysis of the selected model to automatically
     * suggest appropriate form fields, table columns, and filters based on:
     * - Database column types and constraints
     * - Model relationships (belongsTo, hasMany, etc.)
     * - Fillable attributes
     * - Validation rules (if defined)
     * - Naming conventions and patterns
     * 
     * Intelligent Suggestions:
     * - email columns → email input fields
     * - password columns → password fields with security options
     * - foreign keys → relationship selects with proper models
     * - boolean columns → toggle switches or checkboxes
     * - text columns → text inputs with appropriate length limits
     * - timestamp columns → datetime pickers
     * - enum columns → select dropdowns with options
     * 
     * @param string $modelClass Fully qualified model class name
     * @return void
     */
    protected function analyzeModel(string $modelClass): void
    {
        try {
            $generatorService = app(FilamentResourceGeneratorService::class);
            $modelConfig = $generatorService->generateConfigurationFromModel($modelClass);

            // Convert to our simplified format
            $this->formFields = $this->convertFormFields($modelConfig['suggested_form_fields'] ?? []);
            $this->tableColumns = $this->convertTableColumns($modelConfig['suggested_table_columns'] ?? []);
            $this->filters = $this->convertFilters($modelConfig['suggested_filters'] ?? []);

            // Set resource settings
            $modelName = class_basename($modelClass);
            $this->resourceSettings['navigation_label'] = Str::title(Str::plural($modelName));
        } catch (\Exception $e) {
            Log::error('Model analysis failed: ' . $e->getMessage());
            $this->addError('model', 'Failed to analyze model: ' . $e->getMessage());
        }
    }

    protected function convertFormFields(array $fields): array
    {
        $converted = [];
        foreach ($fields as $field) {
            $converted[] = [
                'name' => $field['name'],
                'type' => $field['type'],
                'label' => $field['label'] ?? Str::title(str_replace('_', ' ', $field['name'])),
                'required' => $field['required'] ?? false,
                'enabled' => true,
            ];
        }
        return $converted;
    }

    protected function convertTableColumns(array $columns): array
    {
        $converted = [];
        foreach ($columns as $column) {
            $converted[] = [
                'name' => $column['name'],
                'type' => $column['type'],
                'label' => $column['label'] ?? Str::title(str_replace('_', ' ', $column['name'])),
                'sortable' => $column['sortable'] ?? false,
                'searchable' => $column['searchable'] ?? false,
                'enabled' => true,
            ];
        }
        return $converted;
    }

    protected function convertFilters(array $filters): array
    {
        $converted = [];
        foreach ($filters as $filter) {
            $converted[] = [
                'name' => $filter['name'],
                'type' => $filter['type'],
                'label' => Str::title(str_replace('_', ' ', $filter['name'])),
                'enabled' => false, // Start with filters disabled by default
            ];
        }
        return $converted;
    }

    /**
     * Generate real-time code preview for all configured components
     * 
     * Creates a complete preview of all files that will be generated based on
     * current configuration. This includes the main resource file and all
     * associated page files (Create, Edit, List, View if enabled).
     * 
     * Preview Features:
     * - Real-time updates as configuration changes
     * - Tabbed interface for multiple files
     * - Syntax-highlighted code display
     * - Copy-to-clipboard functionality
     * - Error handling and validation feedback
     * 
     * Generated Files:
     * - Main Resource file with form schema, table configuration, filters
     * - Create page (if applicable)
     * - Edit page (if applicable)
     * - List page (always included)
     * - View page (if enabled)
     * - Policy file (if enabled)
     * 
     * @return void
     */
    public function generatePreview(): void
    {
        if (!$this->selectedModel) {
            $this->previewData = [];
            return;
        }

        try {
            $config = $this->buildGenerationConfig();
            $generatorService = app(FilamentResourceGeneratorService::class);

            $this->previewData = $generatorService->generatePreview($config);
            $this->activePreviewTab = 0; // Reset to first tab

            Log::info('Preview generated successfully', [
                'model' => $this->selectedModel,
                'files_count' => count($this->previewData)
            ]);
        } catch (\Exception $e) {
            Log::error('Preview generation failed: ' . $e->getMessage());
            $this->addError('preview', 'Failed to generate preview: ' . $e->getMessage());
            $this->previewData = [];
        }
    }

    protected function buildGenerationConfig(): array
    {
        $modelName = class_basename($this->selectedModel);

        return [
            'enabled' => true,
            'model' => $this->selectedModel,
            'class_name' => $modelName . 'Resource',
            'namespace' => 'App\\Filament\\Resources',
            'navigation_icon' => $this->resourceSettings['navigation_icon'],
            'navigation_label' => $this->resourceSettings['navigation_label'] ?? Str::title(Str::plural($modelName)),
            'navigation_group' => $this->resourceSettings['navigation_group'] ?: null,
            'navigation_sort' => $this->resourceSettings['navigation_sort'],
            'enable_view_page' => $this->resourceSettings['enable_view_page'],
            'enable_global_search' => $this->resourceSettings['enable_global_search'],
            'generate_policy' => $this->resourceSettings['generate_policy'],
            'form_fields' => array_filter($this->formFields, fn($field) => $field['enabled'] ?? true),
            'table_columns' => array_filter($this->tableColumns, fn($column) => $column['enabled'] ?? true),
            'filters' => array_filter($this->filters, fn($filter) => $filter['enabled'] ?? false),
            'pages' => $this->getEnabledPages(),
        ];
    }

    protected function getEnabledPages(): array
    {
        $pages = ['index', 'create', 'edit'];

        if ($this->resourceSettings['enable_view_page']) {
            $pages[] = 'view';
        }

        return $pages;
    }

    /**
     * Generate and save all resource files to the filesystem
     * 
     * Creates all configured resource files and saves them to appropriate
     * locations within the Laravel application structure. Handles file
     * creation, directory management, and success/error reporting.
     * 
     * File Generation Process:
     * 1. Validate configuration and selected model
     * 2. Build complete generation configuration
     * 3. Generate all resource and page files
     * 4. Save files to appropriate directories
     * 5. Report success/failure with detailed feedback
     * 
     * Generated File Locations:
     * - Resource: app/Filament/Resources/{Model}Resource.php
     * - Pages: app/Filament/Resources/{Model}Resource/Pages/
     * - Policy: app/Policies/{Model}Policy.php (if enabled)
     * 
     * Features:
     * - Automatic directory creation
     * - File conflict detection
     * - Progress indication with loading state
     * - Success notifications with file count
     * - Detailed error reporting and logging
     * 
     * @return void
     */
    public function generateFiles(): void
    {
        if (!$this->selectedModel) {
            $this->addError('generation', 'Please select a model first.');
            return;
        }

        $this->isGenerating = true;

        try {
            $config = $this->buildGenerationConfig();
            $generatorService = app(FilamentResourceGeneratorService::class);

            $results = $generatorService->generateFiles($config);

            $this->isGenerating = false;

            // Show success notification using Filament notification
            Notification::make()
                ->title('Resource Generated Successfully!')
                ->body(count($results) . ' files have been created.')
                ->success()
                ->send();

            Log::info('Resource files generated successfully', [
                'model' => $this->selectedModel,
                'files' => $results
            ]);
        } catch (\Exception $e) {
            $this->isGenerating = false;
            $this->addError('generation', 'Failed to generate files: ' . $e->getMessage());
            Log::error('Resource generation failed: ' . $e->getMessage());
        }
    }

    public function refreshPreview(): void
    {
        $this->generatePreview();
    }

    public function toggleFormField(int $index): void
    {
        if (isset($this->formFields[$index])) {
            $this->formFields[$index]['enabled'] = !($this->formFields[$index]['enabled'] ?? true);
            $this->generatePreview();
        }
    }

    public function toggleTableColumn(int $index): void
    {
        if (isset($this->tableColumns[$index])) {
            $this->tableColumns[$index]['enabled'] = !($this->tableColumns[$index]['enabled'] ?? true);
            $this->generatePreview();
        }
    }

    public function toggleFilter(int $index): void
    {
        if (isset($this->filters[$index])) {
            $this->filters[$index]['enabled'] = !($this->filters[$index]['enabled'] ?? false);
            $this->generatePreview();
        }
    }

    public function updateResourceSettings(): void
    {
        $this->generatePreview();
    }

    /**
     * Add a new form field with comprehensive default configuration
     * 
     * Creates a new form field with all possible configuration options initialized
     * to sensible defaults. Each field type has specific properties that become
     * relevant when the field type is selected in the UI.
     * 
     * Supported Field Types & Their Configurations:
     * - text: min_length, max_length, placeholder, validation
     * - email: built-in email validation, placeholder
     * - password: revealable, confirmation, min_length, security
     * - number: min_value, max_value, step, default_value
     * - textarea: rows, min_length, max_length
     * - select/radio: options (JSON), multiple, searchable, native
     * - file/image: disk, directory, accepted_file_types, max_size, multiple
     * - date/datetime/time: display_format, min_date, max_date
     * - toggle/checkbox: default_state, inline, accepted
     * - rich_editor/markdown: toolbar_buttons, disable_features
     * - relationship: relationship_type, related_model, title_attribute
     * 
     * @return void
     */
    // Form Designer Methods
    public function addFormField(): void
    {
        $this->formFields[] = [
            'name' => '',
            'type' => 'text',
            'label' => '',
            'required' => false,
            'enabled' => true,
            'placeholder' => '',
            'helper_text' => '',
            'validation' => '',

            // Select/Radio properties
            'options' => '',
            'multiple' => false,
            'searchable' => false,
            'native' => false,

            // File Upload properties
            'disk' => 'public',
            'directory' => 'uploads',
            'max_size' => 10,
            'accepted_file_types' => '',
            'image_preview' => true,

            // Date/DateTime properties
            'display_format' => '',
            'min_date' => '',
            'max_date' => '',

            // Number properties
            'min_value' => '',
            'max_value' => '',
            'step' => '',
            'default_value' => '',

            // Textarea properties
            'rows' => 3,
            'min_length' => '',
            'max_length' => '',

            // Rich Editor properties
            'toolbar_buttons' => '',
            'disable_toolbar_buttons' => false,
            'disable_styling' => false,

            // Toggle/Checkbox properties
            'default_state' => 'false',
            'inline' => false,
            'accepted' => false,

            // Password properties
            'revealable' => true,
            'confirmation' => false,

            // Relationship-specific properties
            'relationship_name' => '',
            'related_model' => '',
            'title_attribute' => 'name',
            'relationship_type' => 'belongsTo',
            'preload' => false,
        ];
    }

    public function removeFormField(int $index): void
    {
        if (isset($this->formFields[$index])) {
            unset($this->formFields[$index]);
            $this->formFields = array_values($this->formFields);
            $this->generatePreview();
        }
    }

    public function moveFormFieldUp(int $index): void
    {
        if ($index > 0 && isset($this->formFields[$index])) {
            $temp = $this->formFields[$index];
            $this->formFields[$index] = $this->formFields[$index - 1];
            $this->formFields[$index - 1] = $temp;
            $this->generatePreview();
        }
    }

    public function moveFormFieldDown(int $index): void
    {
        if ($index < count($this->formFields) - 1 && isset($this->formFields[$index])) {
            $temp = $this->formFields[$index];
            $this->formFields[$index] = $this->formFields[$index + 1];
            $this->formFields[$index + 1] = $temp;
            $this->generatePreview();
        }
    }

    /**
     * Add a new table column with comprehensive default configuration
     * 
     * Creates a new table column with all possible configuration options initialized
     * to sensible defaults. Each column type has specific properties for display
     * and interaction customization.
     * 
     * Supported Column Types & Their Configurations:
     * - text: basic text display, formatting, suffix
     * - badge: colors (JSON), icons (JSON), dynamic styling
     * - boolean: true_icon, false_icon, true_color
     * - image: height, width, shape, stacked display
     * - date/datetime: date_format, timezone, since (relative time)
     * - color: size, copy_message, copy_message_text
     * - relationship: relationship_type, related_model, title_attribute
     * 
     * All columns support: sortable, searchable, toggleable, enabled state
     * 
     * @return void
     */
    // Table Designer Methods
    public function addTableColumn(): void
    {
        $this->tableColumns[] = [
            'name' => '',
            'type' => 'text',
            'label' => '',
            'sortable' => false,
            'searchable' => false,
            'enabled' => true,
            'toggleable' => false,
            'format' => '',
            'suffix' => '',

            // Date/DateTime column properties
            'date_format' => '',
            'timezone' => '',
            'since' => false,

            // Image column properties
            'height' => '',
            'width' => '',
            'shape' => '',
            'stacked' => false,

            // Badge column properties
            'colors' => '',
            'icons' => '',

            // Boolean column properties
            'true_icon' => 'heroicon-o-check-circle',
            'false_icon' => 'heroicon-o-x-circle',
            'true_color' => 'success',

            // Color column properties
            'size' => 'md',
            'copy_message' => false,
            'copy_message_text' => false,

            // Relationship-specific properties
            'relationship_name' => '',
            'related_model' => '',
            'title_attribute' => 'name',
            'relationship_type' => 'belongsTo',
        ];
    }

    public function removeTableColumn(int $index): void
    {
        if (isset($this->tableColumns[$index])) {
            unset($this->tableColumns[$index]);
            $this->tableColumns = array_values($this->tableColumns);
            $this->generatePreview();
        }
    }

    public function moveTableColumnUp(int $index): void
    {
        if ($index > 0 && isset($this->tableColumns[$index])) {
            $temp = $this->tableColumns[$index];
            $this->tableColumns[$index] = $this->tableColumns[$index - 1];
            $this->tableColumns[$index - 1] = $temp;
            $this->generatePreview();
        }
    }

    public function moveTableColumnDown(int $index): void
    {
        if ($index < count($this->tableColumns) - 1 && isset($this->tableColumns[$index])) {
            $temp = $this->tableColumns[$index];
            $this->tableColumns[$index] = $this->tableColumns[$index + 1];
            $this->tableColumns[$index + 1] = $temp;
            $this->generatePreview();
        }
    }

    // Filter Designer Methods
    public function addFilter(): void
    {
        $this->filters[] = [
            'name' => '',
            'type' => 'text',
            'label' => '',
            'enabled' => true,
            'options' => '',
            'relationship' => '',
        ];
    }

    public function removeFilter(int $index): void
    {
        if (isset($this->filters[$index])) {
            unset($this->filters[$index]);
            $this->filters = array_values($this->filters);
            $this->generatePreview();
        }
    }

    public function moveFilterUp(int $index): void
    {
        if ($index > 0 && isset($this->filters[$index])) {
            $temp = $this->filters[$index];
            $this->filters[$index] = $this->filters[$index - 1];
            $this->filters[$index - 1] = $temp;
            $this->generatePreview();
        }
    }

    public function moveFilterDown(int $index): void
    {
        if ($index < count($this->filters) - 1 && isset($this->filters[$index])) {
            $temp = $this->filters[$index];
            $this->filters[$index] = $this->filters[$index + 1];
            $this->filters[$index + 1] = $temp;
            $this->generatePreview();
        }
    }

    public function toggleFormDesigner(): void
    {
        $this->showFormDesigner = !$this->showFormDesigner;
    }

    public function toggleTableDesigner(): void
    {
        $this->showTableDesigner = !$this->showTableDesigner;
    }

    public function toggleFilterDesigner(): void
    {
        $this->showFilterDesigner = !$this->showFilterDesigner;
    }

    /**
     * Get available form field types with comprehensive support
     * 
     * Returns all supported Filament form field types with their display names.
     * Each type triggers different configuration panels in the UI with relevant
     * options for that specific field type.
     * 
     * Field Categories:
     * - Text Inputs: text, email, password, number, textarea
     * - Selection: select, radio, checkbox, toggle
     * - Date/Time: date, datetime, time
     * - File Handling: file, image
     * - Rich Content: rich_editor, markdown, color
     * - Special: hidden, relationship
     * 
     * @return array<string, string> Field type => Display name mapping
     */
    // Available field types for dropdowns
    public function getAvailableFormFieldTypes(): array
    {
        return [
            'text' => 'Text Input',
            'email' => 'Email Input',
            'password' => 'Password Input',
            'number' => 'Number Input',
            'textarea' => 'Textarea',
            'select' => 'Select Dropdown',
            'checkbox' => 'Checkbox',
            'radio' => 'Radio Buttons',
            'toggle' => 'Toggle Switch',
            'date' => 'Date Picker',
            'datetime' => 'DateTime Picker',
            'time' => 'Time Picker',
            'file' => 'File Upload',
            'image' => 'Image Upload',
            'rich_editor' => 'Rich Text Editor',
            'markdown' => 'Markdown Editor',
            'color' => 'Color Picker',
            'hidden' => 'Hidden Field',
            'relationship' => 'Relationship Select',
        ];
    }

    /**
     * Get available table column types with comprehensive support
     * 
     * Returns all supported Filament table column types with their display names.
     * Each type provides different visualization and interaction capabilities.
     * 
     * Column Categories:
     * - Text Display: text, badge, select, url, email, phone
     * - Visual: boolean (icons), color, image
     * - Data Types: date, datetime, money, number
     * - Relationships: relationship (with nested attribute access)
     * 
     * @return array<string, string> Column type => Display name mapping
     */
    public function getAvailableTableColumnTypes(): array
    {
        return [
            'text' => 'Text Column',
            'badge' => 'Badge Column',
            'boolean' => 'Boolean (Icon)',
            'color' => 'Color Column',
            'image' => 'Image Column',
            'date' => 'Date Column',
            'datetime' => 'DateTime Column',
            'money' => 'Money Column',
            'number' => 'Number Column',
            'select' => 'Select Column',
            'url' => 'URL Column',
            'email' => 'Email Column',
            'phone' => 'Phone Column',
            'relationship' => 'Relationship Column',
        ];
    }

    public function getAvailableFilterTypes(): array
    {
        return [
            'text' => 'Text Filter',
            'select' => 'Select Filter',
            'boolean' => 'Boolean Filter',
            'date' => 'Date Filter',
            'date_range' => 'Date Range Filter',
            'number' => 'Number Filter',
            'number_range' => 'Number Range Filter',
            'relationship' => 'Relationship Filter',
        ];
    }

    /**
     * Handle form field configuration updates
     * 
     * Triggered when any form field property is modified in the UI.
     * Automatically regenerates the code preview to reflect changes.
     * 
     * @return void
     */
    public function updateFormField(): void
    {
        $this->generatePreview();
    }

    /**
     * Handle table column configuration updates
     * 
     * Triggered when any table column property is modified in the UI.
     * Automatically regenerates the code preview to reflect changes.
     * 
     * @return void
     */
    public function updateTableColumn(): void
    {
        $this->generatePreview();
    }

    /**
     * Handle filter configuration updates
     * 
     * Triggered when any filter property is modified in the UI.
     * Automatically regenerates the code preview to reflect changes.
     * 
     * @return void
     */
    public function updateFilter(): void
    {
        $this->generatePreview();
    }

    public function getAvailableModelsForRelationships(): array
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
                $models[$className] = $fileName;
            }
        }

        return $models;
    }

    /**
     * Get available relationship types for form fields and table columns
     * 
     * Returns all Eloquent relationship types supported by the generator
     * with descriptive labels explaining their purpose and cardinality.
     * 
     * Relationship Types:
     * - belongsTo: Many-to-One (e.g., Post belongs to User)
     * - hasMany: One-to-Many (e.g., User has many Posts)
     * - belongsToMany: Many-to-Many (e.g., User belongs to many Roles)
     * - hasOne: One-to-One (e.g., User has one Profile)
     * 
     * Each relationship type generates appropriate form components:
     * - belongsTo/hasOne: Select dropdown with relationship() method
     * - belongsToMany: CheckboxList for multiple selections
     * - hasMany: Read-only display in tables, managed via separate resources
     * 
     * @return array<string, string> Relationship type => Description mapping
     */
    public function getRelationshipTypes(): array
    {
        return [
            'belongsTo' => 'Belongs To (Many-to-One)',
            'hasMany' => 'Has Many (One-to-Many)',
            'belongsToMany' => 'Belongs To Many (Many-to-Many)',
            'hasOne' => 'Has One (One-to-One)',
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Code Generators';
    }
}
