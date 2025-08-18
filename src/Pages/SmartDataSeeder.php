<?php

namespace HkDevs\CodeForgeStudio\Pages;

use HkDevs\CodeForgeStudio\Services\DataGenerationService;
use HkDevs\CodeForgeStudio\Models\DataGenerationTemplate;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * SmartDataSeeder
 * 
 * Intelligent data seeding page providing automated, realistic test data
 * generation with advanced analysis and template-based customization.
 * 
 * Key Features:
 * - Intelligent table analysis and field type detection
 * - Template-based data generation with reusable patterns
 * - Real-time preview capabilities with generated data samples
 * - Relationship-aware seeding with foreign key management
 * - Customizable data patterns and generation rules
 * - Batch processing for large dataset generation
 * 
 * Smart Analysis:
 * - Automatic table structure analysis and field introspection
 * - Foreign key relationship detection and dependency mapping
 * - Data type analysis for appropriate generation methods
 * - Constraint analysis for validation and data integrity
 * - Index analysis for performance optimization
 * 
 * Template System:
 * - Reusable data generation templates with configuration
 * - Field mapping customization for specialized data types
 * - Relationship template support for complex scenarios
 * - Custom data provider integration
 * - Template sharing and import/export capabilities
 * 
 * Preview Functionality:
 * - Real-time data generation preview before execution
 * - Sample data display with formatting and validation
 * - Relationship data preview with linked records
 * - Performance estimation and execution planning
 * 
 * Generation Options:
 * - Configurable record counts with batch processing
 * - Custom field value patterns and constraints
 * - Unique constraint handling and duplicate prevention
 * - Date range configuration for temporal data
 * - Localization support for international data
 * 
 * Advanced Features:
 * - Transaction support for safe data generation
 * - Rollback capabilities for testing scenarios
 * - Progress tracking for long-running operations
 * - Error handling with detailed reporting
 * - Performance monitoring and optimization
 * 
 * Integration:
 * - DataGenerationService for intelligent data creation
 * - DataGenerationTemplate model for template management
 * - Database analysis services for schema introspection
 * - Notification system for user feedback
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class SmartDataSeeder extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static string $view = 'codeforge-studio::pages.smart-data-seeder';
    protected static ?string $title = 'Smart Data Seeder';
    protected static ?string $navigationLabel = 'Smart Seeder';
    protected static ?int $navigationSort = 4;

    public ?array $data = [];
    public string $selectedTable = '';
    public ?array $previewData = [];
    public ?array $tableAnalysis = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Table Selection')
                    ->description('Select a table to generate data for')
                    ->schema([
                        Forms\Components\Select::make('table_name')
                            ->label('Table')
                            ->options($this->getAvailableTables())
                            ->searchable()
                            ->live(debounce: 300)
                            ->afterStateUpdated(function ($state) {
                                if ($state) {
                                    $this->selectedTable = $state;
                                    $this->analyzeTable($state);
                                }
                            })
                            ->required(),

                        Forms\Components\TextInput::make('record_count')
                            ->label('Number of Records')
                            ->numeric()
                            ->default(10)
                            ->minValue(1)
                            ->maxValue(1000)
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Template Selection')
                    ->description('Choose how to generate data')
                    ->schema([
                        Forms\Components\Radio::make('generation_mode')
                            ->options([
                                'auto' => 'Auto Generate - Create template automatically',
                                'existing' => 'Use Existing Template',
                                'custom' => 'Custom Configuration',
                            ])
                            ->default('auto')
                            ->live(debounce: 300)
                            ->required(),

                        Forms\Components\Select::make('template_id')
                            ->label('Existing Template')
                            ->options(function () {
                                if (!$this->selectedTable) {
                                    return [];
                                }

                                return DataGenerationTemplate::forTable($this->selectedTable)
                                    ->active()
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->visible(fn(Forms\Get $get) => $get('generation_mode') === 'existing'),
                    ]),

                Forms\Components\Section::make('Preview')
                    ->description('Preview generated data before insertion')
                    ->schema([
                        Forms\Components\Placeholder::make('preview_table')
                            ->content(function () {
                                if (empty($this->previewData)) {
                                    return 'No preview available. Select a table and click "Generate Preview".';
                                }

                                // Generate HTML table directly
                                $html = '<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">';
                                $html .= '<table class="min-w-full divide-y divide-gray-300">';

                                if (!empty($this->previewData)) {
                                    // Header
                                    $html .= '<thead class="bg-gray-50"><tr>';
                                    foreach (array_keys($this->previewData[0]) as $column) {
                                        $html .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">' . htmlspecialchars($column) . '</th>';
                                    }
                                    $html .= '</tr></thead>';

                                    // Body
                                    $html .= '<tbody class="bg-white divide-y divide-gray-200">';
                                    foreach ($this->previewData as $row) {
                                        $html .= '<tr>';
                                        foreach ($row as $value) {
                                            $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">';
                                            if (is_array($value) || is_object($value)) {
                                                $html .= '<code class="text-xs bg-gray-100 px-2 py-1 rounded">' . htmlspecialchars(json_encode($value)) . '</code>';
                                            } elseif (is_bool($value)) {
                                                $class = $value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                                $html .= '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $class . '">' . ($value ? 'true' : 'false') . '</span>';
                                            } elseif (is_null($value)) {
                                                $html .= '<span class="text-gray-400 italic">null</span>';
                                            } else {
                                                $html .= htmlspecialchars((string)$value);
                                            }
                                            $html .= '</td>';
                                        }
                                        $html .= '</tr>';
                                    }
                                    $html .= '</tbody>';
                                }

                                $html .= '</table></div>';
                                $html .= '<div class="mt-2 text-sm text-gray-500">Showing preview of ' . count($this->previewData) . ' records for table: <code class="font-mono">' . htmlspecialchars($this->selectedTable) . '</code></div>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ])
                    ->visible(fn() => !empty($this->previewData)),

                Forms\Components\Section::make('Actions')
                    ->schema([
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('preview')
                                ->label('Generate Preview')
                                ->icon('heroicon-o-eye')
                                ->color('info')
                                ->action('generatePreview'),

                            Forms\Components\Actions\Action::make('generate')
                                ->label('Generate & Insert Data')
                                ->icon('heroicon-o-play')
                                ->color('success')
                                ->requiresConfirmation()
                                ->modalHeading('Confirm Data Generation')
                                ->modalDescription(function () {
                                    $count = $this->data['record_count'] ?? 0;
                                    return "This will insert {$count} records into the '{$this->selectedTable}' table.";
                                })
                                ->action('generateAndInsertData'),

                            Forms\Components\Actions\Action::make('save_template')
                                ->label('Save as Template')
                                ->icon('heroicon-o-bookmark')
                                ->color('warning')
                                ->visible(fn() => !empty($this->tableAnalysis))
                                ->action('saveAsTemplate'),
                        ])
                            ->alignEnd(),
                    ]),
            ])
            ->statePath('data');
    }

    public function generatePreview(): void
    {
        try {
            // Don't validate the entire form, just check if we have the essential data
            $tableName = $this->data['table_name'] ?? $this->selectedTable;

            // Debug information
            Log::info('GeneratePreview called', [
                'form_data' => $this->data,
                'selectedTable' => $this->selectedTable,
                'tableName' => $tableName
            ]);

            if (!$tableName) {
                Notification::make()
                    ->title('Error')
                    ->body('Please select a table first.')
                    ->danger()
                    ->send();
                return;
            }

            // Update selectedTable to ensure consistency
            $this->selectedTable = $tableName;

            $service = app(DataGenerationService::class);

            if (($this->data['generation_mode'] ?? 'auto') === 'existing' && !empty($this->data['template_id'])) {
                $template = DataGenerationTemplate::findOrFail($this->data['template_id']);
                $this->previewData = $service->previewData($template, 5);
            } else {
                // Auto generate or custom mode
                $template = $service->createTemplateFromTable($tableName, 'preview_template_' . time());
                $this->previewData = $service->previewData($template, 5);
                $template->delete(); // Clean up temporary template
            }

            // Debug the preview data
            Log::info('Preview data generated', [
                'count' => count($this->previewData),
                'sample' => array_slice($this->previewData, 0, 1)
            ]);

            Notification::make()
                ->title('Preview Generated')
                ->body('Preview data has been generated successfully. Count: ' . count($this->previewData))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Log::error('Preview generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Notification::make()
                ->title('Preview Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function generateAndInsertData(): void
    {
        try {
            $this->validate();

            $tableName = $this->data['table_name'] ?? $this->selectedTable;
            if (!$tableName) {
                throw new \Exception('Please select a table first.');
            }

            // Update selectedTable to ensure consistency
            $this->selectedTable = $tableName;

            $service = app(DataGenerationService::class);
            $count = $this->data['record_count'] ?? 10;

            if (($this->data['generation_mode'] ?? 'auto') === 'existing' && !empty($this->data['template_id'])) {
                $template = DataGenerationTemplate::findOrFail($this->data['template_id']);
                $result = $service->insertGeneratedData($template, $count);
            } else {
                // Auto generate template
                $template = $service->createTemplateFromTable($tableName, 'auto_' . $tableName . '_' . time());
                $result = $service->insertGeneratedData($template, $count);
            }

            $message = "Generated {$result['total_generated']} records, successfully inserted {$result['successfully_inserted']}";
            if ($result['failed_inserts'] > 0) {
                $message .= ", {$result['failed_inserts']} failed";
            }

            Notification::make()
                ->title('Data Generation Completed')
                ->body($message)
                ->success()
                ->send();

            // Reset preview
            $this->previewData = [];
        } catch (\Exception $e) {
            Notification::make()
                ->title('Generation Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function saveAsTemplate(): void
    {
        try {
            $tableName = $this->data['table_name'] ?? $this->selectedTable;
            if (!$tableName || empty($this->tableAnalysis)) {
                throw new \Exception('Please analyze a table first.');
            }

            // Update selectedTable to ensure consistency
            $this->selectedTable = $tableName;

            $service = app(DataGenerationService::class);
            $template = $service->createTemplateFromTable(
                $tableName,
                'template_' . $tableName . '_' . time()
            );

            Notification::make()
                ->title('Template Saved')
                ->body("Template '{$template->name}' has been created and can be reused.")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Save Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function analyzeTable(string $tableName): void
    {
        try {
            $service = app(DataGenerationService::class);
            $this->tableAnalysis = $service->analyzeTable($tableName);
        } catch (\Exception $e) {
            $this->tableAnalysis = [];
            Notification::make()
                ->title('Analysis Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getAvailableTables(): array
    {
        try {
            // Use DB::select to get table names directly
            $tables = DB::select('SHOW TABLES');
            $tableNames = [];

            foreach ($tables as $table) {
                // Get the table name from the object property
                $tableArray = (array) $table;
                $tableName = array_values($tableArray)[0];

                // Skip system tables
                if (!in_array($tableName, [
                    'migrations',
                    'personal_access_tokens',
                    'password_reset_tokens',
                    'failed_jobs',
                    'data_seeders',
                    'seeder_execution_logs',
                    'data_generation_templates',
                    'database_manager_logs',
                    'database_health_metrics',
                    'migration_histories',
                    'query_performance_logs',
                    'cache',
                    'cache_locks',
                    'sessions',
                    'jobs',
                    'job_batches',
                ])) {
                    $tableNames[$tableName] = ucfirst(str_replace('_', ' ', $tableName));
                }
            }

            return $tableNames;
        } catch (\Exception $e) {
            // Fallback to DB connection methods if SHOW TABLES fails
            try {
                $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
                $tableNames = [];

                foreach ($tables as $tableName) {
                    // Skip system tables
                    if (!in_array($tableName, [
                        'migrations',
                        'personal_access_tokens',
                        'password_reset_tokens',
                        'failed_jobs',
                        'data_seeders',
                        'seeder_execution_logs',
                        'data_generation_templates',
                        'database_manager_logs',
                        'database_health_metrics',
                        'migration_histories',
                        'query_performance_logs',
                        'cache',
                        'cache_locks',
                        'sessions',
                        'jobs',
                        'job_batches',
                    ])) {
                        $tableNames[$tableName] = ucfirst(str_replace('_', ' ', $tableName));
                    }
                }

                return $tableNames;
            } catch (\Exception $e2) {
                return [];
            }
        }
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Seeder Manager';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }
}
