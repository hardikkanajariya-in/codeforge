@extends('codeforge-studio::layout.docs')

@section('title', 'Filament Resources - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='flex items-center'>
        <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='flex items-center'>
        <span class='text-gray-500'>API</span>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Filament Resources</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Filament Resources</h1>
            <p class="text-lg text-gray-600">CodeForge Database Studio provides 8+ Filament resources that create a
                comprehensive admin interface for database management, monitoring, and data operations.</p>
        </div>

        <!-- Resources Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Resource Architecture</h2>
            <p class="text-gray-600 mb-6">All resources follow Filament v4/v5 Schema conventions with organized navigation groups,
                comprehensive CRUD operations, and advanced features like bulk actions and data export.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Database Management</h3>
                    </div>
                    <p class="text-sm text-gray-600">Resources for managing health metrics, migration tracking, and query
                        performance.</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Data Operations</h3>
                    </div>
                    <p class="text-sm text-gray-600">Resources for data generation, seeder management, and template
                        configuration.</p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Documentation</h3>
                    </div>
                    <p class="text-sm text-gray-600">Resources for managing documentation generation and schema snapshots.
                    </p>
                </div>
            </div>
        </div>

        <!-- Database Management Resources -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Database Management Resources</h2>

            <div class="space-y-6">
                <!-- Database Health Metric Resource -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">DatabaseHealthMetricResource</h3>
                    <p class="text-gray-600 mb-3">Monitors and displays database health metrics with real-time performance
                        data.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Navigation</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• <strong>Group:</strong> Database Overview</li>
                                    <li>• <strong>Icon:</strong> heroicon-o-chart-bar</li>
                                    <li>• <strong>Sort:</strong> 1</li>
                                    <li>• <strong>Badge:</strong> Live health score</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Features</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Real-time health monitoring</li>
                                    <li>• Performance trend charts</li>
                                    <li>• Connection status tracking</li>
                                    <li>• Export health reports</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Query Performance Log Resource -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">QueryPerformanceLogResource</h3>
                    <p class="text-gray-600 mb-3">Tracks and analyzes database query performance with execution time
                        monitoring.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Table Columns</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• SQL query with syntax highlighting</li>
                                    <li>• Execution time with color coding</li>
                                    <li>• Database connection</li>
                                    <li>• Query hash for grouping</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Actions</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• View full query details</li>
                                    <li>• Analyze query performance</li>
                                    <li>• Export performance data</li>
                                    <li>• Bulk delete old logs</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Migration History Resource -->
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">MigrationHistoryResource</h3>
                    <p class="text-gray-600 mb-3">Comprehensive migration tracking with execution history and rollback
                        capabilities.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Form Fields</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Migration name (readonly)</li>
                                    <li>• Batch number</li>
                                    <li>• Action (up/down)</li>
                                    <li>• Schema changes (JSON viewer)</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Bulk Actions</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Rollback selected migrations</li>
                                    <li>• Export migration history</li>
                                    <li>• Generate documentation</li>
                                    <li>• Cleanup old records</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Management Resources -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Data Management Resources</h2>

            <div class="space-y-6">
                <!-- Data Seeder Resource -->
                <div class="border-l-4 border-indigo-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">DataSeederResource</h3>
                    <p class="text-gray-600 mb-3">Manages database seeders with intelligent execution and dependency
                        resolution.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>class DataSeederResource extends Resource
    {
        protected static ?string $model = DataSeeder::class;
        protected static ?string $navigationGroup = 'Database Tools';
        protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

        // Custom actions for seeder execution
        public static function getHeaderActions(): array
        {
            return [
                Actions\Action::make('runAll')
                    ->label('Run All Seeders')
                    ->icon('heroicon-o-play')
                    ->requiresConfirmation(),
            ];
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Data Generation Template Resource -->
                <div class="border-l-4 border-teal-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">DataGenerationTemplateResource</h3>
                    <p class="text-gray-600 mb-3">Creates and manages custom data generation templates with relationship
                        handling.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Template Configuration</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Template name and description</li>
                                    <li>• Target table selection</li>
                                    <li>• Field mapping configuration</li>
                                    <li>• Relationship handling rules</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Advanced Features</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Custom faker providers</li>
                                    <li>• Conditional data generation</li>
                                    <li>• Template versioning</li>
                                    <li>• Preview generated data</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seeder Execution Log Resource -->
                <div class="border-l-4 border-cyan-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">SeederExecutionLogResource</h3>
                    <p class="text-gray-600 mb-3">Tracks seeder execution with detailed logging and error handling.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>// Table columns with status indicators
    Table::make()
        ->columns([
            TextColumn::make('seeder_class')
                ->searchable()
                ->sortable(),
            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'success' => 'success',
                    'failed' => 'danger',
                    'running' => 'warning',
                }),
            TextColumn::make('records_inserted')
                ->numeric()
                ->sortable(),
            TextColumn::make('execution_time')
                ->suffix(' ms')
                ->sortable(),
        ])</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentation & Schema Resources -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Documentation & Schema Resources</h2>

            <div class="space-y-6">
                <!-- Documentation Generation Resource -->
                <div class="border-l-4 border-orange-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">DocumentationGenerationResource</h3>
                    <p class="text-gray-600 mb-3">Manages documentation generation with multiple output formats and
                        customization options.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Generation Options</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Documentation type (Schema, API, Full)</li>
                                    <li>• Output format (Markdown, HTML, PDF)</li>
                                    <li>• Template selection</li>
                                    <li>• Include/exclude options</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Export Features</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Download generated files</li>
                                    <li>• Schedule automatic generation</li>
                                    <li>• Version control integration</li>
                                    <li>• Custom branding options</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schema Snapshot Resource -->
                <div class="border-l-4 border-pink-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">SchemaSnapshotResource</h3>
                    <p class="text-gray-600 mb-3">Creates and manages database schema snapshots for version control and
                        comparison.</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>// Schema comparison features
    Actions\Action::make('compare')
        ->label('Compare Snapshots')
        ->icon('heroicon-o-scale')
        ->form([
            Select::make('compare_with')
                ->label('Compare with Snapshot')
                ->options(SchemaSnapshot::pluck('name', 'id')),
        ])
        ->action(function (array $data) {
            // Generate schema diff
            return redirect()->route('schema.compare', [
                'current' => $this->record->id,
                'compare' => $data['compare_with'],
            ]);
        })</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resource Customization -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Resource Customization</h2>
            <p class="text-gray-600 mb-6">All resources support extensive customization for specific workflow requirements:
            </p>

            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Navigation Customization</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// Custom navigation configuration
    protected static ?string $navigationGroup = 'Custom Group';
    protected static ?string $navigationIcon = 'heroicon-o-custom-icon';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationBadge = null;

    // Dynamic badge with live data
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Custom Actions</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// Custom resource actions
    public static function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('execute')
                ->label('Execute Seeder')
                ->icon('heroicon-o-play')
                ->requiresConfirmation()
                ->action(fn (Model $record) => $record->execute()),

            Tables\Actions\Action::make('export')
                ->label('Export Data')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (Model $record): string => route('export', $record)),
        ];
    }</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Advanced Filtering</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// Custom table filters
    public static function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->options([
                    'success' => 'Success',
                    'failed' => 'Failed',
                    'pending' => 'Pending',
                ]),
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                }),
        ];
    }</code></pre>
                </div>
            </div>
        </div>

        <!-- Resource Integration -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-8 rounded-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Resource Integration</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio resources integrate seamlessly with your existing
                Filament admin panel:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Plugin Registration</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>// Automatic resource registration
    class CodeForgeStudioPlugin implements Plugin
    {
        public function getResources(): array
        {
            return [
                DatabaseHealthMetricResource::class,
                QueryPerformanceLogResource::class,
                MigrationHistoryResource::class,
                DataSeederResource::class,
                // ... other resources
            ];
        }
    }</code></pre>
                </div>

                <div class="bg-white p-6 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Panel Integration</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>// In AdminPanelProvider
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugins([
                CodeForgeStudioPlugin::make(),
            ])
            ->navigationGroups([
                'Database Overview',
                'Database Tools', 
                'Database Management',
            ]);
    }</code></pre>
                </div>
            </div>
        </div>
    </div>
@endsection