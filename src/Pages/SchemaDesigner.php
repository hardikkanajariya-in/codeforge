<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use HkDevs\CodeForgeStudio\Services\SchemaAnalyzerService;
use HkDevs\CodeForgeStudio\Services\SchemaVisualizationService;
use HkDevs\CodeForgeStudio\Services\AssetService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

/**
 * Advanced Visual Schema Designer
 * 
 * A comprehensive, interactive database schema visualization tool with modern UI/UX
 * and advanced functionality for database design and analysis.
 * 
 * Core Features:
 * ✨ Interactive Drag & Drop Interface with smooth animations and grid snapping
 * 🎨 Advanced Visual Design Tools with customizable themes and layouts
 * 🔍 Real-time Schema Analysis with performance insights and optimization suggestions
 * 📊 Multiple Visualization Modes including ERD, dependency graphs, and table views
 * 🔗 Intelligent Relationship Mapping with automatic detection and validation
 * 📱 Responsive Design optimized for all screen sizes and devices
 * 
 * Visualization Modes:
 * - Interactive Diagram View: Drag-and-drop table positioning with relationship mapping
 * - Table Structure View: Detailed column information with constraints and indexes
 * - Dependency Graph: Hierarchical visualization of table dependencies
 * - Schema Overview: High-level metrics and performance indicators
 * - Relationship Matrix: Grid-based relationship visualization
 * - Performance View: Real-time performance analysis and bottleneck identification
 * 
 * Advanced Interactions:
 * - Multi-select operations for bulk table management
 * - Context-sensitive right-click menus for quick actions
 * - Keyboard shortcuts for power users
 * - Real-time collaborative editing (future enhancement)
 * - Undo/redo functionality for design changes
 * - Auto-save and session persistence
 * 
 * Design Tools:
 * - Grid system with snap-to-grid functionality
 * - Alignment tools for professional diagram layout
 * - Zoom controls with focus areas and minimap navigation
 * - Layer management for complex schema organization
 * - Custom color coding and visual grouping
 * - Export to multiple formats (SVG, PNG, PDF, JSON)
 * 
 * Filtering & Organization:
 * - Advanced search with fuzzy matching and regex support
 * - Dynamic filtering by table types, relationships, and attributes
 * - Custom grouping and categorization
 * - Saved filter presets for quick access
 * - Tag-based organization system
 * - Connection-specific schema isolation
 * 
 * Performance & Optimization:
 * - Lazy loading for large schemas with thousands of tables
 * - Virtualized rendering for optimal performance
 * - Intelligent caching with automatic invalidation
 * - Background analysis with progress indicators
 * - Memory-efficient data structures
 * - Progressive enhancement for slower connections
 * 
 * Integration & Extensions:
 * - Multi-database connection management
 * - Laravel model integration and relationship mapping
 * - Migration script generation from visual changes
 * - Documentation export with customizable templates
 * - API endpoints for external tool integration
 * - Plugin architecture for custom extensions
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 2.0.0
 * @since 1.0.0
 */
class SchemaDesigner extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static string $view = 'codeforge-studio::pages.schema-designer';
    protected static ?string $navigationLabel = 'Schema Designer';
    protected static ?string $title = 'Advanced Schema Designer';
    protected static ?int $navigationSort = 3;

    // Core Properties
    public string $activeView = 'interactive';
    public string $selectedConnection = '';
    public array $availableConnections = [];
    public array $schemaData = [];
    public array $visualizationSettings = [];
    public array $filterSettings = [];
    public array $layoutSettings = [];

    // State Management
    public ?string $selectedTable = null;
    public array $selectedTables = [];
    public array $tablePositions = [];
    public bool $showRelationships = true;
    public bool $showIndexes = true;
    public bool $showConstraints = true;
    public string $searchQuery = '';
    public array $activeFilters = [];

    // UI State
    public bool $isLoading = false;
    public bool $showSidebar = true;
    public bool $showMinimap = false;
    public string $currentTheme = 'light';
    public int $zoomLevel = 100;
    public array $viewportPosition = ['x' => 0, 'y' => 0];

    // Performance Settings
    public bool $enableVirtualization = true;
    public bool $enableAnimations = true;
    public int $maxTablesPerView = 50;

    // Advanced Features
    public array $savedLayouts = [];
    public array $bookmarkedTables = [];
    public array $recentActions = [];
    public bool $enableRealTimeUpdates = true;

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
        $this->initializeDefaults();
        $this->loadAvailableConnections();
        $this->loadSchemaData();
        $this->loadSavedSettings();
    }

    protected function initializeDefaults(): void
    {
        $this->selectedConnection = config('database.default');

        $this->visualizationSettings = [
            'algorithm' => 'force-directed',
            'spacing' => 200,
            'repulsion' => 1000,
            'attraction' => 0.1,
            'iterations' => 100,
            'stabilization' => true,
        ];

        $this->filterSettings = [
            'show_system_tables' => false,
            'show_empty_tables' => true,
            'show_laravel_tables' => true,
            'table_name_pattern' => '',
            'min_columns' => 0,
            'max_columns' => 999,
        ];

        $this->layoutSettings = [
            'grid_size' => 20,
            'snap_to_grid' => true,
            'auto_layout' => true,
            'preserve_positions' => true,
            'show_grid' => false,
        ];
    }

    protected function loadAvailableConnections(): void
    {
        $connections = config('database.connections', []);
        $this->availableConnections = [];

        foreach ($connections as $name => $config) {
            $this->availableConnections[] = [
                'name' => $name,
                'driver' => $config['driver'] ?? 'unknown',
                'host' => $config['host'] ?? 'localhost',
                'database' => $config['database'] ?? '',
                'active' => $name === $this->selectedConnection,
            ];
        }
    }

    protected function loadSchemaData(): void
    {
        try {
            $this->isLoading = true;

            $cacheKey = "schema_designer_data_{$this->selectedConnection}_v2";

            $this->schemaData = Cache::remember($cacheKey, 300, function () {
                $service = $this->getSchemaVisualizationService();

                $data = [
                    'tables' => $this->getSchemaAnalyzerService()->getAllTables(),
                    'relationships' => $this->getSchemaAnalyzerService()->getAllRelationships(),
                    'statistics' => $this->generateSchemaStatistics(),
                    'metadata' => [
                        'connection' => $this->selectedConnection,
                        'database' => config("database.connections.{$this->selectedConnection}.database"),
                        'generated_at' => now()->toISOString(),
                        'version' => '2.0.0',
                    ],
                ];

                // Apply intelligent positioning
                $data['table_positions'] = $this->calculateOptimalPositions($data['tables'], $data['relationships']);

                return $data;
            });

            $this->dispatch('schema-data-loaded', $this->schemaData);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Schema Loading Error')
                ->body("Failed to load schema data: {$e->getMessage()}")
                ->danger()
                ->send();

            $this->schemaData = $this->getEmptySchemaData();
        } finally {
            $this->isLoading = false;
        }
    }

    protected function loadSavedSettings(): void
    {
        $settingsKey = "schema_designer_settings_{$this->selectedConnection}";
        $savedSettings = Cache::get($settingsKey, []);

        if (!empty($savedSettings)) {
            $this->tablePositions = $savedSettings['table_positions'] ?? [];
            $this->savedLayouts = $savedSettings['saved_layouts'] ?? [];
            $this->bookmarkedTables = $savedSettings['bookmarked_tables'] ?? [];
            $this->visualizationSettings = array_merge($this->visualizationSettings, $savedSettings['visualization_settings'] ?? []);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Connection Management
                Select::make('selectedConnection')
                    ->label('Database Connection')
                    ->options(collect($this->availableConnections)->pluck('name', 'name')->toArray())
                    ->default($this->selectedConnection)
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->switchConnection($state)),

                // View Mode Selection
                Select::make('activeView')
                    ->label('Visualization Mode')
                    ->options([
                        'interactive' => '🎨 Interactive Designer',
                        'table_detail' => '📋 Table Details',
                        'dependencies' => '🔗 Dependencies',
                        'performance' => '⚡ Performance',
                        'matrix' => '🗂️ Relationship Matrix',
                        'overview' => '📊 Schema Overview',
                    ])
                    ->default($this->activeView)
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->switchView($state)),

                // Quick Search
                TextInput::make('searchQuery')
                    ->label('Search Tables & Columns')
                    ->placeholder('Type to search...')
                    ->suffixIcon('heroicon-m-magnifying-glass')
                    ->live(debounce: 300)
                    ->afterStateUpdated(fn($state) => $this->updateSearch($state)),

                // Visualization Controls
                Toggle::make('showRelationships')
                    ->label('Show Relationships')
                    ->default(true)
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->toggleRelationships($state)),

                Toggle::make('showIndexes')
                    ->label('Show Indexes')
                    ->default(true)
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->toggleIndexes($state)),

                Toggle::make('showSidebar')
                    ->label('Show Sidebar')
                    ->default(true)
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->toggleSidebar($state)),

                // Action Buttons
                Actions::make([
                    Action::make('refresh')
                        ->label('Refresh Schema')
                        ->icon('heroicon-m-arrow-path')
                        ->color('primary')
                        ->action('refreshSchema'),

                    Action::make('autoLayout')
                        ->label('Auto Layout')
                        ->icon('heroicon-m-sparkles')
                        ->color('success')
                        ->action('applyAutoLayout'),

                    Action::make('resetView')
                        ->label('Reset View')
                        ->icon('heroicon-m-arrow-uturn-left')
                        ->color('gray')
                        ->action('resetView'),

                    Action::make('export')
                        ->label('Export')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('warning')
                        ->action('openExportModal'),

                    Action::make('settings')
                        ->label('Settings')
                        ->icon('heroicon-m-cog-6-tooth')
                        ->color('gray')
                        ->action('openSettingsModal'),
                ])->columnSpanFull(),
            ])
            ->columns(3);
    }

    // Event Handlers and Actions

    public function switchConnection(string $connection): void
    {
        if ($connection !== $this->selectedConnection) {
            $this->selectedConnection = $connection;
            $this->selectedTable = null;
            $this->selectedTables = [];
            $this->clearCache();
            $this->loadSchemaData();

            Notification::make()
                ->title('Connection Switched')
                ->body("Now viewing {$connection} database")
                ->success()
                ->send();
        }
    }

    public function switchView(string $view): void
    {
        $this->activeView = $view;
        $this->dispatch('view-changed', ['view' => $view]);

        // Load view-specific data if needed
        $this->loadViewData($view);
    }

    public function updateSearch(string $query): void
    {
        $this->searchQuery = $query;
        $this->dispatch('search-updated', ['query' => $query]);
    }

    public function toggleRelationships(bool $show): void
    {
        $this->showRelationships = $show;
        $this->dispatch('relationships-toggled', ['show' => $show]);
    }

    public function toggleIndexes(bool $show): void
    {
        $this->showIndexes = $show;
        $this->dispatch('indexes-toggled', ['show' => $show]);
    }

    public function toggleSidebar(bool $show): void
    {
        $this->showSidebar = $show;
        $this->dispatch('sidebar-toggled', ['show' => $show]);
    }

    public function refreshSchema(): void
    {
        $this->clearCache();
        $this->loadSchemaData();

        Notification::make()
            ->title('Schema Refreshed')
            ->body('Database schema has been reloaded successfully')
            ->success()
            ->send();
    }

    public function applyAutoLayout(): void
    {
        try {
            $this->tablePositions = $this->calculateOptimalPositions(
                $this->schemaData['tables'] ?? [],
                $this->schemaData['relationships'] ?? []
            );

            $this->dispatch('layout-applied', ['positions' => $this->tablePositions]);
            $this->saveSettings();

            Notification::make()
                ->title('Auto Layout Applied')
                ->body('Tables have been automatically positioned')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Layout Error')
                ->body("Failed to apply auto layout: {$e->getMessage()}")
                ->danger()
                ->send();
        }
    }

    public function resetView(): void
    {
        $this->selectedTable = null;
        $this->selectedTables = [];
        $this->searchQuery = '';
        $this->zoomLevel = 100;
        $this->viewportPosition = ['x' => 0, 'y' => 0];

        $this->dispatch('view-reset');

        Notification::make()
            ->title('View Reset')
            ->body('Designer view has been reset to defaults')
            ->success()
            ->send();
    }

    #[On('table-selected')]
    public function selectTable(string $tableName): void
    {
        $this->selectedTable = $tableName;
        $this->dispatch('table-selection-changed', ['table' => $tableName]);
    }

    #[On('table-position-updated')]
    public function updateTablePosition(string $tableName, array $position): void
    {
        $this->tablePositions[$tableName] = $position;
        $this->saveSettings();
    }

    #[On('viewport-changed')]
    public function updateViewport(array $viewport): void
    {
        $this->zoomLevel = $viewport['zoom'] ?? $this->zoomLevel;
        $this->viewportPosition = [
            'x' => $viewport['x'] ?? $this->viewportPosition['x'],
            'y' => $viewport['y'] ?? $this->viewportPosition['y'],
        ];
    }

    // Utility Methods

    protected function calculateOptimalPositions(array $tables, array $relationships): array
    {
        if (empty($tables)) {
            return [];
        }

        // Force-directed layout algorithm
        $positions = [];
        $tableCount = count($tables);
        $width = 1200;
        $height = 800;

        // Initial random positioning
        foreach ($tables as $index => $table) {
            $angle = (2 * M_PI * $index) / $tableCount;
            $radius = min($width, $height) * 0.3;

            $positions[$table['name']] = [
                'x' => $width / 2 + $radius * cos($angle),
                'y' => $height / 2 + $radius * sin($angle),
                'vx' => 0,
                'vy' => 0,
            ];
        }

        // Apply force-directed positioning
        $iterations = $this->visualizationSettings['iterations'];
        for ($i = 0; $i < $iterations; $i++) {
            $this->applyForces($positions, $relationships);
        }

        // Clean up and return final positions
        return collect($positions)->map(function ($pos) {
            return ['x' => round($pos['x']), 'y' => round($pos['y'])];
        })->toArray();
    }

    protected function applyForces(array &$positions, array $relationships): void
    {
        $repulsion = $this->visualizationSettings['repulsion'];
        $attraction = $this->visualizationSettings['attraction'];
        $damping = 0.9;

        // Reset forces
        foreach ($positions as &$pos) {
            $pos['fx'] = 0;
            $pos['fy'] = 0;
        }

        // Repulsion between all nodes
        $tables = array_keys($positions);
        for ($i = 0; $i < count($tables); $i++) {
            for ($j = $i + 1; $j < count($tables); $j++) {
                $table1 = $tables[$i];
                $table2 = $tables[$j];

                $dx = $positions[$table1]['x'] - $positions[$table2]['x'];
                $dy = $positions[$table1]['y'] - $positions[$table2]['y'];
                $distance = sqrt($dx * $dx + $dy * $dy) + 0.01; // Avoid division by zero

                $force = $repulsion / ($distance * $distance);
                $fx = $force * $dx / $distance;
                $fy = $force * $dy / $distance;

                $positions[$table1]['fx'] += $fx;
                $positions[$table1]['fy'] += $fy;
                $positions[$table2]['fx'] -= $fx;
                $positions[$table2]['fy'] -= $fy;
            }
        }

        // Attraction for connected nodes
        foreach ($relationships as $rel) {
            $from = $rel['from_table'];
            $to = $rel['to_table'];

            if (!isset($positions[$from]) || !isset($positions[$to])) {
                continue;
            }

            $dx = $positions[$to]['x'] - $positions[$from]['x'];
            $dy = $positions[$to]['y'] - $positions[$from]['y'];
            $distance = sqrt($dx * $dx + $dy * $dy) + 0.01;

            $force = $attraction * $distance;
            $fx = $force * $dx / $distance;
            $fy = $force * $dy / $distance;

            $positions[$from]['fx'] += $fx;
            $positions[$from]['fy'] += $fy;
            $positions[$to]['fx'] -= $fx;
            $positions[$to]['fy'] -= $fy;
        }

        // Update positions
        foreach ($positions as &$pos) {
            $pos['vx'] = ($pos['vx'] + $pos['fx']) * $damping;
            $pos['vy'] = ($pos['vy'] + $pos['fy']) * $damping;
            $pos['x'] += $pos['vx'];
            $pos['y'] += $pos['vy'];
        }
    }

    protected function generateSchemaStatistics(): array
    {
        $tables = $this->schemaData['tables'] ?? [];
        $relationships = $this->schemaData['relationships'] ?? [];

        $totalColumns = collect($tables)->sum(fn($table) => count($table['columns'] ?? []));
        $totalRows = collect($tables)->sum(fn($table) => $table['row_count'] ?? 0);
        $tablesWithData = collect($tables)->filter(fn($table) => ($table['row_count'] ?? 0) > 0)->count();

        return [
            'total_tables' => count($tables),
            'total_columns' => $totalColumns,
            'total_relationships' => count($relationships),
            'total_rows' => $totalRows,
            'tables_with_data' => $tablesWithData,
            'average_columns_per_table' => count($tables) > 0 ? round($totalColumns / count($tables), 2) : 0,
            'relationship_density' => count($tables) > 0 ? round(count($relationships) / count($tables), 2) : 0,
            'largest_table' => $this->findLargestTable($tables),
            'most_connected_table' => $this->findMostConnectedTable($tables, $relationships),
        ];
    }

    protected function findLargestTable(array $tables): ?array
    {
        return collect($tables)->sortByDesc('row_count')->first();
    }

    protected function findMostConnectedTable(array $tables, array $relationships): ?array
    {
        $connections = [];

        foreach ($relationships as $rel) {
            $connections[$rel['from_table']] = ($connections[$rel['from_table']] ?? 0) + 1;
            $connections[$rel['to_table']] = ($connections[$rel['to_table']] ?? 0) + 1;
        }

        if (empty($connections)) {
            return null;
        }

        $mostConnectedTableName = array_keys($connections, max($connections))[0];
        return collect($tables)->firstWhere('name', $mostConnectedTableName);
    }

    protected function loadViewData(string $view): void
    {
        switch ($view) {
            case 'dependencies':
                $this->dispatch('load-dependency-data');
                break;
            case 'performance':
                $this->dispatch('load-performance-data');
                break;
            case 'matrix':
                $this->dispatch('load-matrix-data');
                break;
        }
    }

    protected function getEmptySchemaData(): array
    {
        return [
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
            ],
            'metadata' => [
                'connection' => $this->selectedConnection,
                'generated_at' => now()->toISOString(),
                'version' => '2.0.0',
            ],
        ];
    }

    protected function clearCache(): void
    {
        $patterns = [
            "schema_designer_data_{$this->selectedConnection}_v2",
            "schema_visualization_{$this->selectedConnection}",
            "schema_dependencies_{$this->selectedConnection}",
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }

    protected function saveSettings(): void
    {
        $settingsKey = "schema_designer_settings_{$this->selectedConnection}";

        Cache::put($settingsKey, [
            'table_positions' => $this->tablePositions,
            'saved_layouts' => $this->savedLayouts,
            'bookmarked_tables' => $this->bookmarkedTables,
            'visualization_settings' => $this->visualizationSettings,
            'filter_settings' => $this->filterSettings,
            'layout_settings' => $this->layoutSettings,
        ], 3600); // Cache for 1 hour
    }

    // Public getters for view data

    public function getSchemaData(): array
    {
        return $this->schemaData;
    }

    public function getVisualizationConfig(): array
    {
        return [
            'view' => $this->activeView,
            'settings' => $this->visualizationSettings,
            'filters' => $this->filterSettings,
            'layout' => $this->layoutSettings,
            'ui_state' => [
                'show_relationships' => $this->showRelationships,
                'show_indexes' => $this->showIndexes,
                'show_sidebar' => $this->showSidebar,
                'show_minimap' => $this->showMinimap,
                'theme' => $this->currentTheme,
                'zoom' => $this->zoomLevel,
                'viewport' => $this->viewportPosition,
            ],
        ];
    }

    public function getTableData(string $tableName): ?array
    {
        return collect($this->schemaData['tables'] ?? [])->firstWhere('name', $tableName);
    }

    public function getMaxWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    // Modal Actions (to be implemented)
    public function openExportModal(): void
    {
        $this->dispatch('open-export-modal');
    }

    public function openSettingsModal(): void
    {
        $this->dispatch('open-settings-modal');
    }

    /**
     * Get asset URL with fallback to package directory
     */
    public function getAssetUrl(string $path): string
    {
        return AssetService::asset($path);
    }

    /**
     * Get schema designer CSS URL
     */
    public function getSchemaDesignerCssUrl(): string
    {
        return $this->getAssetUrl('css/schema-designer-v2.css');
    }

    /**
     * Get schema designer JS URL
     */
    public function getSchemaDesignerJsUrl(): string
    {
        return $this->getAssetUrl('js/schema-designer-v2.js');
    }
}
