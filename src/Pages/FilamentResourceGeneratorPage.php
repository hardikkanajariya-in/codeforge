<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
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

            // Show success notification
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Resource Generated Successfully!',
                'message' => count($results) . ' files have been created.',
            ]);

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

    public static function getNavigationGroup(): ?string
    {
        return 'Code Generators';
    }
}
