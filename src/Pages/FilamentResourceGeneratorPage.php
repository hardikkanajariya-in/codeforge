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
 * Simple, single-page Filament resource generator that focuses on creating
 * complete admin resources from existing Laravel models with real-time preview.
 * 
 * Features:
 * - Model-only generation (no migration complexity)
 * - Real-time code preview with tabbed interface
 * - Intelligent model analysis and field suggestion
 * - Single-page workflow for better UX
 * - Advanced configuration options
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 2.0.0
 */
class FilamentResourceGeneratorPage extends Page
{
    protected static string $view = 'codeforge-studio::pages.filament-resource-generator';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $title = 'Filament Resource Generator';
    protected static ?string $navigationLabel = 'Filament Resource';
    protected static ?int $navigationSort = 5;

    // Core properties
    public ?string $selectedModel = null;
    public array $previewData = [];
    public bool $isGenerating = false;
    public int $activePreviewTab = 0;

    // Designer toggles
    public bool $showFormDesigner = false;
    public bool $showTableDesigner = false;
    public bool $showFilterDesigner = false;

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
            'options' => '',
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
            'badge_colors' => '',
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

    public function updateFormField(): void
    {
        $this->generatePreview();
    }

    public function updateTableColumn(): void
    {
        $this->generatePreview();
    }

    public function updateFilter(): void
    {
        $this->generatePreview();
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Code Generators';
    }
}
