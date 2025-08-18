<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use HkDevs\CodeForgeStudio\Services\SchemaAnalyzerService;
use HkDevs\CodeForgeStudio\Services\SchemaVisualizationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * SchemaDesigner
 * 
 * Advanced visual database schema designer providing interactive
 * entity-relationship diagrams and comprehensive schema management.
 * 
 * Key Features:
 * - Interactive visual schema design with drag-and-drop interface
 * - Entity-relationship diagram (ERD) generation and visualization
 * - Real-time schema analysis and relationship mapping
 * - Multi-connection database support with connection switching
 * - Table filtering and organization capabilities
 * - Dependency graph visualization for complex relationships
 * 
 * Visualization Modes:
 * - Diagram View: Interactive ERD with relationship lines
 * - Table View: Detailed table structure and field information
 * - Dependency View: Relationship dependency graph
 * - Overview View: High-level schema summary and statistics
 * 
 * Interactive Features:
 * - Table selection and detailed inspection
 * - Relationship highlighting and navigation
 * - Field-level detail exploration
 * - Index and constraint visualization
 * - Schema comparison and change detection
 * 
 * Filtering Options:
 * - System table filtering for cleaner views
 * - Plugin table filtering for focused analysis
 * - Connection-specific schema visualization
 * - Table name and type filtering
 * 
 * Schema Analysis:
 * - Automatic relationship detection and mapping
 * - Foreign key constraint analysis
 * - Index optimization recommendations
 * - Schema health and integrity checking
 * - Performance impact analysis
 * 
 * Integration Services:
 * - SchemaAnalyzerService for database introspection
 * - SchemaVisualizationService for diagram generation
 * - Caching system for performance optimization
 * - Real-time updates and change detection
 * 
 * Export Capabilities:
 * - Visual diagram export (PNG, SVG, PDF)
 * - Schema documentation generation
 * - Migration script generation from design changes
 * - Integration with documentation generator
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class SchemaDesigner extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static string $view = 'codeforge-studio::pages.schema-designer';
    protected static ?string $navigationLabel = 'Schema Designer';
    protected static ?string $title = 'Visual Schema Designer';
    protected static ?int $navigationSort = 3;

    public ?array $visualizationData = null;
    public ?array $erdData = null;
    public ?array $dependencyGraph = null;
    public string $currentView = 'diagram';
    public ?string $selectedConnection = null;
    public ?string $selectedTable = null;
    public bool $filterSystemTables = false;
    public bool $filterPluginTables = false;

    protected function getSchemaAnalyzerService(): SchemaAnalyzerService
    {
        return app(SchemaAnalyzerService::class, ['connectionName' => $this->selectedConnection]);
    }

    protected function getSchemaVisualizationService(): SchemaVisualizationService
    {
        $analyzer = $this->getSchemaAnalyzerService();
        return app(SchemaVisualizationService::class, ['analyzer' => $analyzer]);
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Database Tools';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('codeforge-database-studio.features.schema_designer', true);
    }

    public function mount(): void
    {
        $this->selectedConnection = config('database.default');

        // Initialize filter settings from config
        $config = config('filament-database-manager.schema_designer', []);
        $this->filterSystemTables = $config['filter_system_tables'] ?? false;
        $this->filterPluginTables = $config['filter_plugin_tables'] ?? false;

        $this->loadVisualizationData();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function refreshSchema(): void
    {
        $this->clearCache();
        $this->loadVisualizationData();

        Notification::make()
            ->title('Schema refreshed successfully')
            ->success()
            ->send();
    }

    public function exportERD(): void
    {
        try {
            $this->loadERDData();

            $filename = 'schema_erd_' . $this->selectedConnection . '_' . date('Y-m-d_H-i-s') . '.json';

            // Prepare comprehensive export data
            $exportData = [
                'metadata' => [
                    'generated_at' => now()->toISOString(),
                    'connection' => $this->selectedConnection,
                    'database' => config("database.connections.{$this->selectedConnection}.database"),
                    'tables_count' => count($this->visualizationData['tables'] ?? []),
                    'relationships_count' => count($this->visualizationData['relationships'] ?? []),
                    'generator' => 'CodeForge Database Studio',
                    'version' => '1.0.0'
                ],
                'schema' => [
                    'tables' => $this->visualizationData['tables'] ?? [],
                    'relationships' => $this->visualizationData['relationships'] ?? [],
                    'statistics' => $this->visualizationData['statistics'] ?? []
                ],
                'erd_data' => $this->erdData
            ];

            $content = json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            // Store the file temporarily and trigger download via JavaScript
            $tempPath = storage_path('app/temp/' . $filename);

            // Ensure temp directory exists
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            file_put_contents($tempPath, $content);

            // Dispatch browser event to trigger download
            $this->dispatch('download-file', [
                'url' => route('schema.download', ['file' => basename($tempPath)]),
                'filename' => $filename
            ]);

            // Also provide a direct download link as backup
            session()->flash('download_ready', [
                'url' => route('schema.download', ['file' => basename($tempPath)]),
                'filename' => $filename
            ]);

            Notification::make()
                ->title('ERD Export Ready')
                ->body("Schema exported successfully. Download will start automatically.")
                ->success()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('download')
                        ->label('Download Now')
                        ->url(route('schema.download', ['file' => basename($tempPath)]))
                        ->openUrlInNewTab(false)
                ])
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Export Failed')
                ->body('Failed to export ERD: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function selectTable(string $tableName): void
    {
        $this->selectedTable = $tableName;
        $this->dispatch('table-selected', tableName: $tableName);
    }

    public function loadVisualizationData(): void
    {
        try {
            $cacheKey = "schema_visualization_{$this->selectedConnection}";

            $this->visualizationData = Cache::remember($cacheKey, 300, function () {
                $service = $this->getSchemaVisualizationService();
                $data = $service->generateVisualizationData();

                // Debug logging
                Log::info('Schema visualization data generated', [
                    'tables_count' => count($data['tables'] ?? []),
                    'relationships_count' => count($data['relationships'] ?? []),
                    'connection' => $this->selectedConnection
                ]);

                return $data;
            });

            $this->dispatch('visualization-data-updated', data: $this->visualizationData);
        } catch (\Exception $e) {
            Log::error('Error loading schema visualization data', [
                'error' => $e->getMessage(),
                'connection' => $this->selectedConnection
            ]);

            Notification::make()
                ->title('Error loading schema data')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function loadERDData(): void
    {
        try {
            $cacheKey = "schema_erd_{$this->selectedConnection}";

            $this->erdData = Cache::remember($cacheKey, 300, function () {
                $service = $this->getSchemaVisualizationService();
                return $service->generateERDData();
            });
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error loading ERD data')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function loadDependencyGraph(): void
    {
        try {
            $cacheKey = "schema_dependencies_{$this->selectedConnection}";

            $this->dependencyGraph = Cache::remember($cacheKey, 300, function () {
                $service = $this->getSchemaVisualizationService();
                return $service->getTableDependencyGraph();
            });
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error loading dependency graph')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function clearCache(): void
    {
        $patterns = [
            "schema_visualization_{$this->selectedConnection}",
            "schema_erd_{$this->selectedConnection}",
            "schema_dependencies_{$this->selectedConnection}",
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }

    protected function getDatabaseConnections(): array
    {
        $connections = config('database.connections', []);
        $options = [];

        foreach ($connections as $name => $config) {
            $driver = $config['driver'] ?? 'unknown';
            $options[$name] = "{$name} ({$driver})";
        }

        return $options;
    }

    public function getViewData(): array
    {
        $data = [
            'currentView' => $this->currentView,
            'selectedConnection' => $this->selectedConnection,
            'selectedTable' => $this->selectedTable,
        ];

        try {
            switch ($this->currentView) {
                case 'erd':
                    if (!$this->erdData) {
                        $this->loadERDData();
                    }
                    $data['erdData'] = $this->erdData;
                    break;

                case 'dependencies':
                    if (!$this->dependencyGraph) {
                        $this->loadDependencyGraph();
                    }
                    $data['dependencyGraph'] = $this->dependencyGraph;
                    break;

                default:
                    if (!$this->visualizationData) {
                        $this->loadVisualizationData();
                    }
                    $data['visualizationData'] = $this->visualizationData;
                    break;
            }
        } catch (\Exception $e) {
            // Return safe fallback data
            $data['visualizationData'] = [
                'tables' => [],
                'relationships' => [],
                'statistics' => [
                    'total_tables' => 0,
                    'total_columns' => 0,
                    'total_relationships' => 0,
                    'total_rows' => 0,
                    'tables_with_data' => 0,
                    'average_columns_per_table' => 0,
                    'relationship_density' => 0,
                ]
            ];
            $data['error'] = $e->getMessage();
        }

        return $data;
    }

    public function switchView(string $view): void
    {
        $this->currentView = $view;

        // Load data for the new view if not already loaded
        switch ($view) {
            case 'erd':
                if (!$this->erdData) {
                    $this->loadERDData();
                }
                break;

            case 'dependencies':
                if (!$this->dependencyGraph) {
                    $this->loadDependencyGraph();
                }
                break;
        }

        $this->dispatch('view-changed', view: $view);
    }

    public function getTableDetails(string $tableName): ?array
    {
        try {
            $analyzer = $this->getSchemaAnalyzerService();
            return $analyzer->getTableDetails($tableName);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getStatistics(): array
    {
        return $this->visualizationData['statistics'] ?? [
            'total_tables' => 0,
            'total_columns' => 0,
            'total_relationships' => 0,
            'total_rows' => 0,
            'tables_with_data' => 0,
            'average_columns_per_table' => 0,
            'relationship_density' => 0,
        ];
    }

    protected function updateFilterConfig(): void
    {
        // Update the configuration temporarily for this session
        config([
            'filament-database-manager.schema_designer.filter_system_tables' => $this->filterSystemTables,
            'filament-database-manager.schema_designer.filter_plugin_tables' => $this->filterPluginTables,
        ]);
    }
}
