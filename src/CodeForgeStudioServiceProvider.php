<?php

namespace HkDevs\CodeForgeStudio;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use HkDevs\CodeForgeStudio\Commands\AssetDebugCommand;
use HkDevs\CodeForgeStudio\Commands\BatchMigrateCommand;
use HkDevs\CodeForgeStudio\Commands\CleanupDocumentationCommand;
use HkDevs\CodeForgeStudio\Commands\CleanupLogsCommand;
use HkDevs\CodeForgeStudio\Commands\CollectHealthMetricsCommand;
use HkDevs\CodeForgeStudio\Commands\CreateSchemaSnapshotCommand;
use HkDevs\CodeForgeStudio\Commands\DebugSeederDiscoveryCommand;
use HkDevs\CodeForgeStudio\Commands\DiagnoseSeederCommand;
use HkDevs\CodeForgeStudio\Commands\FixSeederPathsCommand;
use HkDevs\CodeForgeStudio\Commands\GenerateDataCommand;
use HkDevs\CodeForgeStudio\Commands\GenerateDocumentationCommand;
use HkDevs\CodeForgeStudio\Commands\InstallCommand;
use HkDevs\CodeForgeStudio\Commands\ManageAssetsCommand;
use HkDevs\CodeForgeStudio\Commands\MigrationCommand;
use HkDevs\CodeForgeStudio\Commands\RunSeedersCommand;
use HkDevs\CodeForgeStudio\Commands\SyncMigrationHistoryCommand;
use HkDevs\CodeForgeStudio\Commands\TestDataGenerationCommand;
use HkDevs\CodeForgeStudio\Commands\ToggleQueryLoggingCommand;
use HkDevs\CodeForgeStudio\Listeners\QueryPerformanceListener;
use HkDevs\CodeForgeStudio\Services\AdvancedCodeGenerationService;
use HkDevs\CodeForgeStudio\Services\AssetService;
use HkDevs\CodeForgeStudio\Services\CodeGenerationService;
use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use HkDevs\CodeForgeStudio\Services\DataGenerationService;
use HkDevs\CodeForgeStudio\Services\FactoryGeneratorService;
use HkDevs\CodeForgeStudio\Services\FilamentResourceGeneratorService;
use HkDevs\CodeForgeStudio\Services\LaravelTypesService;
use HkDevs\CodeForgeStudio\Services\MigrationGeneratorService;
use HkDevs\CodeForgeStudio\Services\MigrationTrackingService;
use HkDevs\CodeForgeStudio\Services\ModelGeneratorService;
use HkDevs\CodeForgeStudio\Services\SchemaAnalyzerService;
use HkDevs\CodeForgeStudio\Services\SchemaDocumentationService;
use HkDevs\CodeForgeStudio\Services\SeederDiscoveryService;
use HkDevs\CodeForgeStudio\Services\SeederExecutionService;
use HkDevs\CodeForgeStudio\Services\SeederGeneratorService;
use HkDevs\CodeForgeStudio\Services\StubTemplateService;
use HkDevs\CodeForgeStudio\Services\TrackingMigrationRepository;
use HkDevs\CodeForgeStudio\Widgets\CodeGenerationStatsWidget;
use HkDevs\CodeForgeStudio\Widgets\DatabaseHealthMetricsWidget;
use HkDevs\CodeForgeStudio\Widgets\DatabaseHealthWidget;
use HkDevs\CodeForgeStudio\Widgets\DatabaseStatsWidget;
use HkDevs\CodeForgeStudio\Widgets\GeneratorStatsWidget;
use HkDevs\CodeForgeStudio\Widgets\MigrationStatsWidget;
use HkDevs\CodeForgeStudio\Widgets\QueryPerformanceChart;
use HkDevs\CodeForgeStudio\Widgets\RecentMigrationsWidget;
use HkDevs\CodeForgeStudio\Widgets\SeederStatsWidget;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CodeForgeStudioServiceProvider extends PackageServiceProvider
{
    public static string $name = 'codeforge-database-studio';

    protected static string $viewNamespace = 'codeforge-studio';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace)
            ->discoversMigrations()
            ->runsMigrations()
            ->hasCommands($this->getPackageCommands());
    }

    public function packageRegistered(): void
    {
        $this->registerServices();
        $this->registerMigrationRepository();
    }

    public function packageBooted(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/docs.php');

        $this->registerPublishableAssets();
        $this->registerFilamentAssets();
        $this->registerLivewireComponents();
        $this->registerEventListeners();
    }

    /**
     * @return array<int, class-string>
     */
    protected function getPackageCommands(): array
    {
        return [
            InstallCommand::class,
            GenerateDocumentationCommand::class,
            CreateSchemaSnapshotCommand::class,
            CleanupDocumentationCommand::class,
            CollectHealthMetricsCommand::class,
            CleanupLogsCommand::class,
            ToggleQueryLoggingCommand::class,
            MigrationCommand::class,
            BatchMigrateCommand::class,
            RunSeedersCommand::class,
            DiagnoseSeederCommand::class,
            DebugSeederDiscoveryCommand::class,
            FixSeederPathsCommand::class,
            GenerateDataCommand::class,
            TestDataGenerationCommand::class,
            SyncMigrationHistoryCommand::class,
            ManageAssetsCommand::class,
            AssetDebugCommand::class,
        ];
    }

    protected function registerServices(): void
    {
        $this->app->singleton(SeederExecutionService::class);
        $this->app->singleton(DataGenerationService::class);
        $this->app->singleton(SchemaAnalyzerService::class);
        $this->app->singleton(SchemaDocumentationService::class);
        $this->app->singleton(MigrationGeneratorService::class);
        $this->app->singleton(ModelGeneratorService::class);
        $this->app->singleton(CodeGenerationService::class);
        $this->app->singleton(FilamentResourceGeneratorService::class);
        $this->app->singleton(AdvancedCodeGenerationService::class);
        $this->app->singleton(StubTemplateService::class);
        $this->app->singleton(LaravelTypesService::class);
        $this->app->singleton(FactoryGeneratorService::class);
        $this->app->singleton(SeederGeneratorService::class);
        $this->app->singleton(SeederDiscoveryService::class);
        $this->app->singleton(DatabaseHealthService::class);
        $this->app->singleton(MigrationTrackingService::class);
        $this->app->singleton(AssetService::class);
    }

    protected function registerMigrationRepository(): void
    {
        $this->app->extend('migration.repository', function ($repository, $app) {
            return new TrackingMigrationRepository(
                $repository,
                $app[MigrationTrackingService::class]
            );
        });
    }

    protected function registerPublishableAssets(): void
    {
        $this->publishes([
            __DIR__.'/../resources/css' => public_path('vendor/codeforge/css'),
            __DIR__.'/../resources/js' => public_path('vendor/codeforge/js'),
        ], 'codeforge-database-studio-assets');

        // Legacy publish tag aliases
        $this->publishes([
            __DIR__.'/../config/codeforge-database-studio.php' => config_path('codeforge-database-studio.php'),
        ], 'codeforge-studio-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'codeforge-studio-migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/codeforge'),
        ], 'codeforge-studio-views');

        $this->publishes([
            __DIR__.'/../resources/css' => public_path('vendor/codeforge/css'),
            __DIR__.'/../resources/js' => public_path('vendor/codeforge/js'),
        ], 'codeforge-studio-assets');
    }

    protected function registerFilamentAssets(): void
    {
        if (! class_exists(FilamentAsset::class)) {
            return;
        }

        FilamentAsset::register(
            [
                Css::make('codeforge-schema-designer-v2', __DIR__.'/../resources/css/schema-designer-v2.css'),
                Js::make('codeforge-schema-designer', __DIR__.'/../resources/js/schema-designer.js'),
                Js::make('codeforge-schema-designer-v2', __DIR__.'/../resources/js/schema-designer-v2.js'),
            ],
            package: 'hkdevs/codeforge-database-studio'
        );
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('hk-devs.code-forge-studio.widgets.database-stats-widget', DatabaseStatsWidget::class);
        Livewire::component('hk-devs.code-forge-studio.widgets.database-health-metrics-widget', DatabaseHealthMetricsWidget::class);
        Livewire::component('hk-devs.code-forge-studio.widgets.database-health-widget', DatabaseHealthWidget::class);
        Livewire::component('hk-devs.code-forge-studio.widgets.seeder-stats-widget', SeederStatsWidget::class);
        Livewire::component('hk-devs.code-forge-studio.widgets.migration-stats-widget', MigrationStatsWidget::class);
        Livewire::component('hk-devs.code-forge-studio.widgets.query-performance-chart', QueryPerformanceChart::class);
        Livewire::component('hk-devs.code-forge-studio.widgets.recent-migrations-widget', RecentMigrationsWidget::class);
        Livewire::component('hk-devs.code-forge-studio.widgets.code-generation-stats-widget', CodeGenerationStatsWidget::class);
        Livewire::component('hk-devs.code-forge-studio.widgets.generator-stats-widget', GeneratorStatsWidget::class);
    }

    protected function registerEventListeners(): void
    {
        Event::listen(QueryExecuted::class, [QueryPerformanceListener::class, 'handle']);

        Event::listen(CommandFinished::class, function ($event) {
            if (in_array($event->command, ['migrate:fresh', 'migrate:refresh']) && $event->exitCode === 0) {
                try {
                    app(Kernel::class)->call('codeforge:sync-migration-history');
                } catch (\Exception $e) {
                    // Silently handle any errors during auto-sync
                }
            }
        });
    }
}
