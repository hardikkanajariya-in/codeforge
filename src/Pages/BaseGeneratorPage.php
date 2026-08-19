<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use HkDevs\CodeForgeStudio\Services\LaravelTypesService;
use HkDevs\CodeForgeStudio\Services\StubTemplateService;
use Illuminate\Support\Str;

/**
 * BaseGeneratorPage
 *
 * Abstract base class for all CodeForge Database Studio code generator pages
 * providing common functionality, UI state management, and generation workflows.
 *
 * Key Features:
 * - Standardized generator UI state management with step tracking
 * - Common generation workflow with preview and validation
 * - Service integration for stub templates and Laravel type handling
 * - Reusable generation configuration and error handling
 * - Multi-step generation process with progress tracking
 * - Template service integration for code generation
 * - Validation framework for generator inputs
 *
 * UI State Management:
 * - isGenerating: Generation process status tracking
 * - generationResults: Results storage and display
 * - validationErrors: Error collection and presentation
 * - currentStep: Multi-step workflow navigation
 * - showPreview: Preview mode toggle functionality
 * - previewData: Generated code preview storage
 * - generationConfig: Common configuration management
 *
 * Common Services:
 * - StubTemplateService: Template management and processing
 * - LaravelTypesService: Laravel-specific type handling
 * - Form interaction capabilities with validation
 * - Notification system for user feedback
 *
 * Generator Workflow:
 * - Configuration: Input collection and validation
 * - Preview: Generated code preview and review
 * - Generation: Actual file creation and processing
 * - Results: Success/failure reporting and file listings
 *
 * Abstract Methods:
 * - Child classes must implement specific generator logic
 * - Template configuration and processing workflows
 * - Form schema definition for generator inputs
 * - Validation rules for generator-specific requirements
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * class CustomGeneratorPage extends BaseGeneratorPage
 * {
 *     protected function initializeConfiguration(): void
 *     {
 *         $this->generationConfig = ['type' => 'custom'];
 *     }
 * }
 */
abstract class BaseGeneratorPage extends Page implements HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    // Common UI State
    public bool $isGenerating = false;

    public ?array $generationResults = null;

    public ?array $validationErrors = null;

    public string $currentStep = 'configuration';

    public bool $showPreview = false;

    public ?array $previewData = null;

    // Common configuration
    public ?array $generationConfig = [];

    protected static string|\UnitEnum|null $navigationGroup = 'Code Generators';

    protected function getStubTemplateService(): StubTemplateService
    {
        return app(StubTemplateService::class);
    }

    protected function getLaravelTypesService(): LaravelTypesService
    {
        return app(LaravelTypesService::class);
    }

    public function closeValidationModal(): void
    {
        $this->validationErrors = null;
    }

    public function mount(): void
    {
        $this->initializeConfiguration();
        $this->form->fill($this->generationConfig);
        $this->isGenerating = false;
        $this->currentStep = 'configuration';
        $this->dispatch('isGeneratingChanged', false);
    }

    public function validationErrorsAction(): Action
    {
        return Action::make('validationErrors')
            ->label('Validation Errors')
            ->icon('heroicon-o-exclamation-triangle')
            ->color('danger')
            ->size(ActionSize::Large)
            ->modal()
            ->modalHeading('Configuration Errors Found')
            ->modalDescription('Please fix the following errors before proceeding:')
            ->modalContent(view('codeforge-studio::components.validation-errors-modal', [
                'errors' => $this->validationErrors ?? [],
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close')
            ->closeModalByClickingAway(false);
    }

    abstract protected function initializeConfiguration(): void;

    abstract protected function getGeneratorService();

    abstract protected function validateConfiguration(): array;

    /**
     * Helper method to convert Laravel namespace to file path
     */
    protected function namespaceToPath(string $namespace): string
    {
        return str_replace(['App\\', 'Database\\', '\\'], ['app/', 'database/', '/'], $namespace);
    }

    /**
     * Helper method to check if a file would overwrite an existing file
     */
    protected function wouldOverwriteFile(string $filePath, string $fileType = 'file'): ?string
    {
        if (file_exists($filePath)) {
            $relativePath = str_replace(base_path().DIRECTORY_SEPARATOR, '', $filePath);

            return "This {$fileType} already exists at: {$relativePath}. Please choose a different name or delete the existing file first.";
        }

        return null;
    }

    /**
     * Check if name conflicts with existing files (case-insensitive)
     */
    protected function checkCaseInsensitiveConflicts(string $filePath, string $fileName, string $fileType): ?string
    {
        $directory = dirname($filePath);

        if (! is_dir($directory)) {
            return null;
        }

        $files = scandir($directory);
        $targetFileName = pathinfo($filePath, PATHINFO_FILENAME);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $existingFileName = pathinfo($file, PATHINFO_FILENAME);

            // Check for case-insensitive match that's not exact match
            if (strtolower($existingFileName) === strtolower($targetFileName) && $existingFileName !== $targetFileName) {
                $relativePath = str_replace(base_path().DIRECTORY_SEPARATOR, '', $directory.DIRECTORY_SEPARATOR.$file);

                return "A {$fileType} with similar name already exists: {$relativePath}. Consider using a different name to avoid confusion.";
            }
        }

        return null;
    }

    /**
     * Check if the name is a reserved Laravel/PHP name
     */
    protected function isReservedName(string $name, string $type = 'class'): ?string
    {
        $reservedNames = [
            'class' => [
                'Model',
                'Factory',
                'Seeder',
                'Resource',
                'Controller',
                'Middleware',
                'Request',
                'Rule',
                'Command',
                'Job',
                'Event',
                'Listener',
                'Mail',
                'Notification',
                'Policy',
                'Provider',
                'Exception',
                'Kernel',
                'Handler',
                'Trait',
                'Interface',
                'Abstract',
            ],
            'table' => [
                'users',
                'password_resets',
                'failed_jobs',
                'migrations',
                'model_has_permissions',
                'model_has_roles',
                'role_has_permissions',
                'permissions',
                'roles',
            ],
        ];

        $reserved = $reservedNames[$type] ?? [];

        if (in_array(strtolower($name), array_map('strtolower', $reserved))) {
            return "'{$name}' is a reserved Laravel name. Please choose a different name.";
        }

        return null;
    }

    public function previewGeneration(): void
    {
        try {
            // Clear previous validation errors
            $this->validationErrors = null;

            // Get form data first
            $formData = $this->form->getState();
            $this->generationConfig = array_merge($this->generationConfig, $formData);

            // Validate configuration
            $validationErrors = $this->validateConfiguration();

            if (! empty($validationErrors)) {
                $this->validationErrors = $validationErrors;

                // Trigger the validation errors modal
                $this->mountAction('validationErrors');

                Notification::make()
                    ->title('Validation Failed')
                    ->body('Please fix the validation errors before generating preview.')
                    ->danger()
                    ->send();

                return;
            }

            $this->previewData = $this->getGeneratorService()
                ->generatePreview($this->generationConfig);

            $this->showPreview = true;
            $this->currentStep = 'preview';

            Notification::make()
                ->title('Preview Generated')
                ->body('Code preview has been generated successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            $this->validationErrors = [$e->getMessage()];
            $this->mountAction('validationErrors');

            Notification::make()
                ->title('Preview Failed')
                ->body('Error: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function generateFiles(): void
    {
        $this->isGenerating = true;

        try {
            // Clear previous validation errors and get fresh form data
            $this->validationErrors = null;
            $formData = $this->form->getState();
            $this->generationConfig = array_merge($this->generationConfig, $formData);

            $validationErrors = $this->validateConfiguration();

            if (! empty($validationErrors)) {
                $this->validationErrors = $validationErrors;
                $this->mountAction('validationErrors');
                throw new \Exception('Configuration validation failed. Please fix the errors and try again.');
            }

            $this->generationResults = $this->getGeneratorService()
                ->generateFiles($this->generationConfig);

            if ($this->generationResults['success']) {
                $filesCount = count($this->generationResults['files_created']);

                Notification::make()
                    ->title('Generation Successful')
                    ->body("Successfully generated {$filesCount} file(s).")
                    ->success()
                    ->persistent()
                    ->send();

                $this->currentStep = 'results';
            } else {
                throw new \Exception('Generation failed: '.implode(', ', $this->generationResults['errors'] ?? []));
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Generation Failed')
                ->body('Error: '.$e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        } finally {
            $this->isGenerating = false;
        }
    }

    public function resetConfiguration(): void
    {
        $this->initializeConfiguration();
        $this->showPreview = false;
        $this->previewData = null;
        $this->generationResults = null;
        $this->validationErrors = null;
        $this->currentStep = 'configuration';

        Notification::make()
            ->title('Configuration Reset')
            ->body('All settings have been reset to defaults.')
            ->info()
            ->send();
    }

    public function generatePreview(): void
    {
        $this->isGenerating = true;
        $this->validationErrors = null;

        try {
            // Get fresh form data
            $formData = $this->form->getState();
            $this->generationConfig = array_merge($this->generationConfig, $formData);

            // Validate configuration first
            $validationErrors = $this->validateConfiguration();

            if (! empty($validationErrors)) {
                $this->validationErrors = $validationErrors;
                $this->mountAction('validationErrors');

                return;
            }

            $this->previewData = $this->getGeneratorService()->generatePreview(
                $this->generationConfig
            );

            if (! empty($this->previewData)) {
                $this->currentStep = 'preview';
            } else {
                $this->validationErrors = ['No preview data generated. Please check your configuration.'];
                $this->mountAction('validationErrors');
            }
        } catch (\Exception $e) {
            $this->validationErrors = [$e->getMessage()];
            $this->mountAction('validationErrors');
        } finally {
            $this->isGenerating = false;
        }
    }

    public function getViewData(): array
    {
        return [
            'currentStep' => $this->currentStep,
            'showPreview' => $this->showPreview,
            'previewData' => $this->previewData,
            'generationResults' => $this->generationResults,
            'validationErrors' => $this->validationErrors,
            'isGenerating' => $this->isGenerating,
        ];
    }

    public function resetLoadingState(): void
    {
        $this->isGenerating = false;
        $this->dispatch('isGeneratingChanged', false);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview Code')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->action('generatePreview')
                ->visible(fn () => $this->currentStep === 'configuration'),

            Action::make('generate')
                ->label('Generate Files')
                ->icon('heroicon-o-code-bracket-square')
                ->color('success')
                ->action('generateFiles')
                ->visible(fn () => $this->currentStep === 'preview'),

            Action::make('back')
                ->label('Back to Configuration')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->action(fn () => $this->currentStep = 'configuration')
                ->visible(fn () => $this->currentStep === 'preview'),

            Action::make('reset')
                ->label('Reset')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->action('resetConfiguration')
                ->requiresConfirmation(),
        ];
    }

    protected function autoSuggestFromTableName(string $tableName): void
    {
        $modelName = Str::studly(Str::singular($tableName));
        $this->autoSuggestNames($modelName, $tableName);
    }

    protected function autoSuggestNames(string $modelName, ?string $tableName = null): void
    {
        // Can be overridden by specific generators
    }

    protected function suggestColumnConfiguration(string $columnName): array
    {
        $suggestions = [];

        if (str_ends_with($columnName, '_id')) {
            $suggestions['type'] = 'bigInteger';
            $suggestions['unsigned'] = true;
            $suggestions['index'] = true;
        } elseif (str_ends_with($columnName, '_at')) {
            $suggestions['type'] = 'timestamp';
            $suggestions['nullable'] = true;
        } elseif (in_array($columnName, ['email', 'phone', 'mobile'])) {
            $suggestions['type'] = 'string';
            $suggestions['unique'] = $columnName === 'email';
        } elseif (in_array($columnName, ['name', 'title', 'subject'])) {
            $suggestions['type'] = 'string';
            $suggestions['length'] = 255;
        } elseif (in_array($columnName, ['description', 'content', 'body', 'text'])) {
            $suggestions['type'] = 'text';
        } elseif (in_array($columnName, ['price', 'amount', 'cost'])) {
            $suggestions['type'] = 'decimal';
            $suggestions['length'] = '8,2';
        } elseif (in_array($columnName, ['is_active', 'is_published', 'is_verified'])) {
            $suggestions['type'] = 'boolean';
            $suggestions['default'] = 'false';
        }

        return $suggestions;
    }
}
