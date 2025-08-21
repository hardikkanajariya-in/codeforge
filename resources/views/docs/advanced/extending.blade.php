@extends('codeforge-studio::layout.docs')

@section('title', 'Extending - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='flex items-center'>
        <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='flex items-center'>
        <span class='text-gray-500'>Advanced</span>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Extending</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Extending the Plugin</h1>
            <p class="text-lg text-gray-600">CodeForge Database Studio is designed for extensibility. Add custom
                functionality, hook into events, create custom pages, override services, and integrate with your existing
                Laravel architecture.</p>
        </div>

        <!-- Extension Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Extension Methods</h2>
            <p class="text-gray-600 mb-6">Multiple approaches to extend the plugin functionality to meet your specific
                requirements.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Event Listeners</h3>
                    </div>
                    <p class="text-sm text-gray-600">Hook into plugin events for custom actions and integrations.</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Service Extension</h3>
                    </div>
                    <p class="text-sm text-gray-600">Override or extend core services with custom implementations.</p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Custom Pages</h3>
                    </div>
                    <p class="text-sm text-gray-600">Add custom Filament pages and widgets to the plugin navigation.</p>
                </div>

                <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Plugin Architecture</h3>
                    </div>
                    <p class="text-sm text-gray-600">Create plugins that extend or integrate with CodeForge functionality.
                    </p>
                </div>
            </div>
        </div>

        <!-- Event System -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Event System Integration</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio dispatches events throughout its operation lifecycle.
                Hook into these events to add custom functionality.</p>

            <div class="space-y-6">
                <!-- Available Events -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Events</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Database Events</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <code>DatabaseHealthChecked</code> - After health check completion</li>
                                <li>• <code>QueryPerformanceLogged</code> - When slow queries are detected</li>
                                <li>• <code>ConnectionStatusChanged</code> - Database connection changes</li>
                                <li>• <code>SchemaAnalyzed</code> - After schema analysis</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Code Generation Events</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <code>ModelGenerated</code> - After model creation</li>
                                <li>• <code>MigrationGenerated</code> - After migration creation</li>
                                <li>• <code>ResourceGenerated</code> - After Filament resource creation</li>
                                <li>• <code>CodeGenerationCompleted</code> - After batch generation</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Event Listener Example -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Creating Event Listeners</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># Generate a listener
    php artisan make:listener DatabaseHealthNotificationListener --event=HkDevs\\CodeForgeStudio\\Events\\DatabaseHealthChecked

    # Register in EventServiceProvider
    protected $listen = [
        \HkDevs\CodeForgeStudio\Events\DatabaseHealthChecked::class => [
            \App\Listeners\DatabaseHealthNotificationListener::class,
        ],
        \HkDevs\CodeForgeStudio\Events\QueryPerformanceLogged::class => [
            \App\Listeners\SlowQueryAlertListener::class,
        ],
    ];</code></pre>
                    </div>
                </div>

                <!-- Practical Example -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Example: Custom Query Performance Listener</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace App\Listeners;

    use HkDevs\CodeForgeStudio\Events\QueryPerformanceLogged;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Mail;

    class SlowQueryAlertListener
    {
        public function handle(QueryPerformanceLogged $event)
        {
            $queryLog = $event->queryLog;

            // Log to custom channel
            Log::channel('performance')->critical('Slow query detected', [
                'query' => $queryLog->query,
                'execution_time' => $queryLog->execution_time,
                'connection' => $queryLog->connection,
                'user_id' => auth()->id(),
            ]);

            // Send alert for extremely slow queries
            if ($queryLog->execution_time > 5000) { // 5 seconds
                Mail::to('admin@yourapp.com')->send(
                    new SlowQueryAlert($queryLog)
                );
            }

            // Trigger external monitoring (e.g., Sentry, DataDog)
            if (class_exists(\Sentry\Laravel\Facade::class)) {
                \Sentry\captureMessage('Slow Query Alert', 'warning', [
                    'extra' => $queryLog->toArray()
                ]);
            }
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Extension -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Service Extension & Override</h2>
            <p class="text-gray-600 mb-6">Override or extend core services to modify plugin behavior without modifying the
                core package.</p>

            <div class="space-y-6">
                <!-- Service Override -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Overriding Core Services</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># Create custom service
    php artisan make:class Services/CustomDatabaseHealthService

    namespace App\Services;

    use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
    use Illuminate\Support\Facades\Cache;

    class CustomDatabaseHealthService extends DatabaseHealthService
    {
        public function checkHealth(): array
        {
            // Call parent method
            $health = parent::checkHealth();

            // Add custom checks
            $health['custom_metrics'] = $this->getCustomMetrics();
            $health['third_party_status'] = $this->checkThirdPartyServices();

            // Cache results longer for better performance
            Cache::put('custom_health_check', $health, now()->addMinutes(10));

            return $health;
        }

        protected function getCustomMetrics(): array
        {
            return [
                'redis_status' => $this->checkRedisConnection(),
                'queue_size' => $this->getQueueSize(),
                'storage_usage' => $this->getStorageUsage(),
            ];
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Service Registration -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Registering Custom Services</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>// In AppServiceProvider
    public function register()
    {
        // Complete service override
        $this->app->bind(
            \HkDevs\CodeForgeStudio\Services\DatabaseHealthService::class,
            \App\Services\CustomDatabaseHealthService::class
        );

        // Extend existing service
        $this->app->extend(
            \HkDevs\CodeForgeStudio\Services\CodeGenerationService::class,
            function ($service, $app) {
                // Add custom templates
                $service->registerTemplate('api-controller', $app['path.stubs'] . '/api-controller.stub');
                $service->registerTemplate('custom-model', $app['path.stubs'] . '/custom-model.stub');

                return $service;
            }
        );

        // Register additional services
        $this->app->singleton('codeforge.custom.metrics', function ($app) {
            return new CustomMetricsService($app['db'], $app['cache']);
        });
    }</code></pre>
                    </div>
                </div>

                <!-- Service Interface Implementation -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Implementing Service Interfaces</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>// Custom data generation service
    namespace App\Services;

    use HkDevs\CodeForgeStudio\Contracts\DataGenerationContract;
    use Illuminate\Database\Eloquent\Model;

    class CustomDataGenerationService implements DataGenerationContract
    {
        public function generateForModel(Model $model, int $count = 10): array
        {
            // Custom logic for specific models
            if ($model instanceof \App\Models\User) {
                return $this->generateUsers($count);
            }

            if ($model instanceof \App\Models\Product) {
                return $this->generateProducts($count);
            }

            // Fallback to default generation
            return $this->generateGenericData($model, $count);
        }

        protected function generateUsers(int $count): array
        {
            // Custom user generation with real-world data
            return collect(range(1, $count))->map(function () {
                return [
                    'name' => $this->generateRealisticName(),
                    'email' => $this->generateValidEmail(),
                    'role' => $this->selectWeightedRole(),
                    'created_at' => $this->generateSequentialDate(),
                ];
            })->toArray();
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Pages and Widgets -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Custom Pages & Widgets</h2>
            <p class="text-gray-600 mb-6">Extend the plugin's Filament interface with custom pages, widgets, and resources
                integrated into the CodeForge navigation.</p>

            <div class="space-y-6">
                <!-- Custom Page -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Creating Custom Pages</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># Generate custom page
    php artisan make:filament-page CustomDatabaseAnalytics --resource=

    namespace App\Filament\Pages;

    use Filament\Pages\Page;
    use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;

    class CustomDatabaseAnalytics extends Page
    {
        protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
        protected static ?string $navigationGroup = 'Database Studio';
        protected static ?int $navigationSort = 10;
        protected static string $view = 'filament.pages.custom-database-analytics';

        public function mount(): void
        {
            // Check if user has CodeForge license
            if (!app('codeforge.license')->isValid()) {
                abort(403, 'Valid CodeForge license required');
            }
        }

        protected function getHeaderWidgets(): array
        {
            return [
                CustomDatabaseMetricsWidget::class,
                CustomQueryAnalyticsWidget::class,
            ];
        }

        public function getAnalyticsData(): array
        {
            $healthService = app(DatabaseHealthService::class);

            return [
                'performance_metrics' => $healthService->getPerformanceMetrics(),
                'usage_statistics' => $this->getUsageStatistics(),
                'custom_insights' => $this->generateCustomInsights(),
            ];
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Custom Widget -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Creating Custom Widgets</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># Generate custom widget
    php artisan make:filament-widget CustomQueryMetricsWidget --stats-overview

    namespace App\Filament\Widgets;

    use Filament\Widgets\StatsOverviewWidget as BaseWidget;
    use Filament\Widgets\StatsOverviewWidget\Stat;
    use HkDevs\CodeForgeStudio\Services\QueryPerformanceService;

    class CustomQueryMetricsWidget extends BaseWidget
    {
        protected static ?int $sort = 1;
        protected int | string | array $columnSpan = 'full';

        protected function getStats(): array
        {
            $queryService = app(QueryPerformanceService::class);
            $metrics = $queryService->getMetricsForLast24Hours();

            return [
                Stat::make('Average Query Time', $metrics['avg_time'] . 'ms')
                    ->description('Last 24 hours')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('primary')
                    ->chart($metrics['hourly_averages']),

                Stat::make('Slow Queries', $metrics['slow_queries'])
                    ->description('Queries > 1000ms')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color($metrics['slow_queries'] > 10 ? 'danger' : 'success'),

                Stat::make('Total Queries', number_format($metrics['total_queries']))
                    ->description('24 hour volume')
                    ->descriptionIcon('heroicon-m-database')
                    ->color('info'),
            ];
        }

        public function getPollingInterval(): ?string
        {
            return '30s'; // Auto-refresh every 30 seconds
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Integration with CodeForge Navigation -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Navigation Integration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>// In your custom page or resource
    protected static ?string $navigationGroup = 'Database Studio'; // Groups with CodeForge

    // Or create a separate group that appears alongside CodeForge
    protected static ?string $navigationGroup = 'Custom Database Tools';
    protected static ?int $navigationSort = 2; // After CodeForge group

    // Register custom pages in AdminPanelProvider
    use App\Filament\Pages\CustomDatabaseAnalytics;
    use App\Filament\Resources\CustomDatabaseLogResource;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->pages([
                CustomDatabaseAnalytics::class,
            ])
            ->resources([
                CustomDatabaseLogResource::class,
            ])
            ->plugins([
                CodeForgeStudioPlugin::make(),
            ]);
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plugin Architecture -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Creating Extension Plugins</h2>
            <p class="text-gray-600 mb-6">Build companion plugins that extend CodeForge functionality while maintaining
                separation of concerns.</p>

            <div class="space-y-6">
                <!-- Plugin Structure -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Extension Plugin Structure</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># Create extension package
    composer init # Create composer.json for your extension

    # Package structure
    your-extension/
    ├── src/
    │   ├── YourExtensionPlugin.php
    │   ├── YourExtensionServiceProvider.php
    │   ├── Pages/
    │   ├── Resources/
    │   ├── Services/
    │   └── Events/
    ├── config/
    │   └── your-extension.php
    ├── resources/
    │   └── views/
    └── composer.json</code></pre>
                    </div>
                </div>

                <!-- Extension Plugin Example -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Example Extension Plugin</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace YourCompany\CodeForgeExtension;

    use Filament\Contracts\Plugin;
    use Filament\Panel;
    use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

    class CodeForgeAnalyticsExtension implements Plugin
    {
        public function getId(): string
        {
            return 'codeforge-analytics-extension';
        }

        public function register(Panel $panel): void
        {
            // Ensure CodeForge is installed
            if (!$panel->getPlugin('codeforge-studio')) {
                throw new \Exception('CodeForge Database Studio is required for this extension');
            }

            $panel
                ->pages([
                    Pages\AdvancedAnalytics::class,
                    Pages\CustomReports::class,
                ])
                ->resources([
                    Resources\AnalyticsReportResource::class,
                ]);
        }

        public function boot(Panel $panel): void
        {
            // Register event listeners that hook into CodeForge events
            app('events')->listen(
                \HkDevs\CodeForgeStudio\Events\DatabaseHealthChecked::class,
                Listeners\AnalyticsDataCollector::class
            );

            // Extend CodeForge services
            app()->extend(
                \HkDevs\CodeForgeStudio\Services\DatabaseHealthService::class,
                function ($service) {
                    $service->addAnalyticsCollector(new AnalyticsCollector());
                    return $service;
                }
            );
        }

        public static function make(): static
        {
            return app(static::class);
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Service Provider Integration -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Provider Integration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace YourCompany\CodeForgeExtension;

    use Illuminate\Support\ServiceProvider;
    use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;

    class CodeForgeExtensionServiceProvider extends ServiceProvider
    {
        public function register()
        {
            // Register extension config
            $this->mergeConfigFrom(
                __DIR__.'/../config/codeforge-extension.php',
                'codeforge-extension'
            );

            // Register extension services
            $this->app->singleton(AnalyticsService::class);
            $this->app->singleton(CustomReportService::class);
        }

        public function boot()
        {
            // Publish extension config
            $this->publishes([
                __DIR__.'/../config/codeforge-extension.php' => config_path('codeforge-extension.php'),
            ], 'codeforge-extension-config');

            // Register custom commands
            if ($this->app->runningInConsole()) {
                $this->commands([
                    Commands\GenerateAnalyticsReport::class,
                    Commands\ExportDatabaseMetrics::class,
                ]);
            }

            // Hook into CodeForge events after it's loaded
            $this->app->booted(function () {
                if (class_exists(\HkDevs\CodeForgeStudio\CodeForgeStudioServiceProvider::class)) {
                    $this->registerCodeForgeIntegration();
                }
            });
        }

        protected function registerCodeForgeIntegration()
        {
            // Extend existing services
            $this->app->extend(DatabaseHealthService::class, function ($service) {
                $service->addHealthCheck('analytics', function () {
                    return app(AnalyticsService::class)->getHealthStatus();
                });
                return $service;
            });
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Integration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Advanced Integration Patterns</h2>
            <p class="text-gray-600 mb-6">Advanced techniques for deep integration with CodeForge functionality.</p>

            <div class="space-y-6">
                <!-- Custom Command Integration -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Custom Command Integration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
    use HkDevs\CodeForgeStudio\Services\CodeGenerationService;

    class CustomDatabaseMaintenance extends Command
    {
        protected $signature = 'codeforge:custom-maintenance {--report} {--optimize}';
        protected $description = 'Custom database maintenance with CodeForge integration';

        public function handle()
        {
            $healthService = app(DatabaseHealthService::class);
            $codeService = app(CodeGenerationService::class);

            $this->info('Starting custom maintenance...');

            // Run health check
            $health = $healthService->checkHealth();
            $this->table(['Metric', 'Status'], collect($health)->map(function ($value, $key) {
                return [$key, is_bool($value) ? ($value ? 'OK' : 'FAIL') : $value];
            }));

            if ($this->option('optimize')) {
                $this->optimizeDatabase($healthService);
            }

            if ($this->option('report')) {
                $this->generateMaintenanceReport($healthService, $codeService);
            }
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Middleware Integration -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Middleware Integration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace App\Http\Middleware;

    use Closure;
    use HkDevs\CodeForgeStudio\Services\QueryPerformanceService;

    class DatabasePerformanceMiddleware
    {
        public function handle($request, Closure $next)
        {
            $start = microtime(true);

            $response = $next($request);

            $executionTime = (microtime(true) - $start) * 1000;

            // Log request performance to CodeForge
            if ($executionTime > 500) { // Log slow requests
                app(QueryPerformanceService::class)->logRequestPerformance([
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'execution_time' => $executionTime,
                    'user_id' => auth()->id(),
                    'ip_address' => $request->ip(),
                ]);
            }

            return $response;
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Job Queue Integration -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Queue Integration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace App\Jobs;

    use Illuminate\Bus\Queueable;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use HkDevs\CodeForgeStudio\Services\DataGenerationService;

    class GenerateTestDataJob implements ShouldQueue
    {
        use InteractsWithQueue, Queueable, SerializesModels;

        public function __construct(
            public string $modelClass,
            public int $count,
            public array $constraints = []
        ) {}

        public function handle()
        {
            $dataService = app(DataGenerationService::class);

            // Use CodeForge's smart data generation
            $data = $dataService->generateSmartData(
                $this->modelClass,
                $this->count,
                $this->constraints
            );

            // Create records in batches
            collect($data)->chunk(100)->each(function ($chunk) {
                $this->modelClass::insert($chunk->toArray());
            });

            // Dispatch follow-up jobs if needed
            if ($this->shouldGenerateRelatedData()) {
                GenerateRelatedDataJob::dispatch($this->modelClass);
            }
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Extension Best Practices -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-8 rounded-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Extension Best Practices</h2>
            <p class="text-gray-600 mb-6">Guidelines for creating maintainable and efficient extensions:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Architecture Guidelines</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Service Contracts:</strong> Use interfaces for loose coupling</li>
                        <li>• <strong>Event-Driven:</strong> Prefer events over direct service calls</li>
                        <li>• <strong>Configuration:</strong> Make extensions configurable</li>
                        <li>• <strong>Namespacing:</strong> Use clear, unique namespaces</li>
                        <li>• <strong>Dependency Injection:</strong> Leverage Laravel's container</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Integration Guidelines</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Version Compatibility:</strong> Test with CodeForge versions</li>
                        <li>• <strong>Error Handling:</strong> Graceful degradation if CodeForge unavailable</li>
                        <li>• <strong>Performance:</strong> Monitor impact on CodeForge performance</li>
                        <li>• <strong>Documentation:</strong> Document extension APIs and usage</li>
                        <li>• <strong>Testing:</strong> Include integration tests with CodeForge</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 p-4 bg-white rounded-lg border border-green-200">
                <h4 class="font-semibold text-gray-900 mb-2">⚠️ Important Considerations</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Always check for CodeForge availability before using its services</li>
                    <li>• Use service container binding to avoid tight coupling</li>
                    <li>• Implement graceful fallbacks when CodeForge features are disabled</li>
                    <li>• Test extensions thoroughly with different CodeForge configurations</li>
                    <li>• Follow Laravel and Filament best practices for consistency</li>
                </ul>
            </div>
        </div>
    </div>
@endsection