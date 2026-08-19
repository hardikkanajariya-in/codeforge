@extends('codeforge-studio::layout.docs')

@section('title', 'Testing Guide - CodeForge Database Studio')
@section('description', 'Learn how to test your CodeForge Database Studio integrations and extensions effectively.')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('codeforge.docs.home') }}" class="text-gray-500 hover:text-primary-600">Documentation</a>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="flex items-center">
        <span class="text-gray-500">Advanced</span>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-primary-600 font-medium">Testing</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Testing Guide</h1>
                    <p class="text-xl text-gray-600">Comprehensive testing strategies for CodeForge Database Studio
                        integrations and extensions</p>
                </div>
            </div>
        </div>

        <!-- Testing Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Testing Architecture</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio includes a comprehensive test suite with over 500+ test
                cases covering unit tests, integration tests, and feature tests using Orchestra Testbench for package
                testing.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Unit Tests</h3>
                    </div>
                    <p class="text-sm text-gray-600">Test individual services, models, and components in isolation with
                        mocked dependencies.</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Integration Tests</h3>
                    </div>
                    <p class="text-sm text-gray-600">Test service interactions, command execution, and database operations
                        with real components.</p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Feature Tests</h3>
                    </div>
                    <p class="text-sm text-gray-600">End-to-end testing of complete workflows including Filament interfaces
                        and user interactions.</p>
                </div>
            </div>
        </div>

        <!-- Test Environment Setup -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Test Environment Setup</h2>

            <div class="space-y-6">
                <!-- PHPUnit Configuration -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">PHPUnit Configuration</h3>
                    <p class="text-gray-600 mb-3">CodeForge uses PHPUnit 10.0+ with optimized configuration for package
                        testing:</p>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>&lt;?xml version="1.0" encoding="UTF-8"?&gt;
    &lt;phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10.0/phpunit.xsd"
             bootstrap="tests/bootstrap.php"
             colors="true"
             processIsolation="false"
             stopOnFailure="false"
             executionOrder="random"
             failOnWarning="true"
             failOnRisky="true"
             beStrictAboutOutputDuringTests="true"&gt;
        &lt;testsuites&gt;
            &lt;testsuite name="Unit"&gt;
                &lt;directory suffix="Test.php"&gt;./tests/Unit&lt;/directory&gt;
            &lt;/testsuite&gt;
            &lt;testsuite name="Feature"&gt;
                &lt;directory suffix="Test.php"&gt;./tests/Feature&lt;/directory&gt;
            &lt;/testsuite&gt;
            &lt;testsuite name="Integration"&gt;
                &lt;directory suffix="Test.php"&gt;./tests/Integration&lt;/directory&gt;
            &lt;/testsuite&gt;
        &lt;/testsuites&gt;
    &lt;/phpunit&gt;</code></pre>
                    </div>
                </div>

                <!-- Test Case Base Class -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Base Test Case</h3>
                    <p class="text-gray-600 mb-3">All tests extend the CodeForge TestCase which provides common setup and
                        helpers:</p>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace HkDevs\CodeForgeStudio\Tests;

    use Orchestra\Testbench\TestCase as BaseTestCase;
    use HkDevs\CodeForgeStudio\CodeForgeStudioServiceProvider;

    abstract class TestCase extends BaseTestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            // Enable all features for testing
            config(['codeforge-database-studio.features.documentation_generator' => true]);
            config(['codeforge-database-studio.features.schema_designer' => true]);
            config(['codeforge-database-studio.features.migration_manager' => true]);
            config(['codeforge-database-studio.features.health_monitoring' => true]);
            config(['codeforge-database-studio.features.smart_seeding' => true]);
        }

        protected function getPackageProviders($app): array
        {
            return [
                \Livewire\LivewireServiceProvider::class,
                CodeForgeStudioServiceProvider::class,
            ];
        }

        protected function defineEnvironment($app): void
        {
            // Use SQLite in-memory for fast testing
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => false,
            ]);
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit Testing -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Unit Testing Strategies</h2>

            <div class="space-y-6">
                <!-- Service Testing -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Service Testing</h3>
                    <p class="text-gray-600 mb-3">Test core services with mocked dependencies:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>namespace HkDevs\CodeForgeStudio\Tests\Unit\Services;

    use HkDevs\CodeForgeStudio\Tests\TestCase;
    use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
    use Illuminate\Support\Facades\DB;
    use Mockery;

    class DatabaseHealthServiceTest extends TestCase
    {
        protected DatabaseHealthService $service;

        protected function setUp(): void
        {
            parent::setUp();
            $this->service = app(DatabaseHealthService::class);
        }

        public function test_can_check_connection_status()
        {
            // Mock database connection
            DB::shouldReceive('connection')->andReturnSelf();
            DB::shouldReceive('getPdo')->andReturn(new \PDO('sqlite::memory:'));

            $status = $this->service->getConnectionStatus();

            $this->assertIsArray($status);
            $this->assertArrayHasKey('connected', $status);
            $this->assertTrue($status['connected']);
        }

        public function test_can_detect_slow_queries()
        {
            // Create test data
            $this->createQueryLog(['execution_time' => 2000]); // 2 seconds

            $slowQueries = $this->service->getSlowQueries();

            $this->assertCount(1, $slowQueries);
            $this->assertEquals(2000, $slowQueries[0]->execution_time);
        }

        protected function createQueryLog(array $attributes = []): void
        {
            // Helper method to create test data
            \HkDevs\CodeForgeStudio\Models\QueryPerformanceLog::create(
                array_merge([
                    'query' => 'SELECT * FROM users',
                    'execution_time' => 100,
                    'connection' => 'mysql',
                    'user_id' => 1,
                ], $attributes)
            );
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Model Testing -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Model Testing</h3>
                    <p class="text-gray-600 mb-3">Test Eloquent models with factory patterns:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>namespace HkDevs\CodeForgeStudio\Tests\Unit\Models;

    use HkDevs\CodeForgeStudio\Tests\TestCase;
    use HkDevs\CodeForgeStudio\Models\DatabaseHealthMetric;
    use Illuminate\Foundation\Testing\RefreshDatabase;

    class DatabaseHealthMetricTest extends TestCase
    {
        use RefreshDatabase;

        public function test_can_create_health_metric()
        {
            $metric = DatabaseHealthMetric::create([
                'connection' => 'mysql',
                'metric_type' => 'response_time',
                'value' => 150.5,
                'threshold' => 1000,
                'status' => 'healthy',
                'measured_at' => now(),
            ]);

            $this->assertDatabaseHas('database_health_metrics', [
                'connection' => 'mysql',
                'metric_type' => 'response_time',
                'value' => 150.5,
            ]);
        }

        public function test_can_scope_by_connection()
        {
            DatabaseHealthMetric::create(['connection' => 'mysql', 'value' => 100]);
            DatabaseHealthMetric::create(['connection' => 'pgsql', 'value' => 200]);

            $mysqlMetrics = DatabaseHealthMetric::forConnection('mysql')->get();

            $this->assertCount(1, $mysqlMetrics);
            $this->assertEquals('mysql', $mysqlMetrics->first()->connection);
        }

        public function test_can_determine_health_status()
        {
            $healthyMetric = DatabaseHealthMetric::create([
                'value' => 500,
                'threshold' => 1000,
            ]);

            $unhealthyMetric = DatabaseHealthMetric::create([
                'value' => 1500,
                'threshold' => 1000,
            ]);

            $this->assertTrue($healthyMetric->isHealthy());
            $this->assertFalse($unhealthyMetric->isHealthy());
        }
    }</code></pre>
                    </div>
                </div>

                <!-- Command Testing -->
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Command Testing</h3>
                    <p class="text-gray-600 mb-3">Test Artisan commands with output assertions:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto">
    <code>namespace HkDevs\CodeForgeStudio\Tests\Unit\Commands;

    use HkDevs\CodeForgeStudio\Tests\TestCase;
    use Illuminate\Foundation\Testing\RefreshDatabase;

    class InstallCommandTest extends TestCase
    {
        use RefreshDatabase;

        public function test_install_command_creates_config()
        {
            $this->artisan('codeforge:install')
                ->expectsOutput('Installing CodeForge Database Studio...')
                ->expectsOutput('Installation completed successfully!')
                ->assertExitCode(0);

            $this->assertFileExists(config_path('codeforge-database-studio.php'));
        }

        public function test_install_command_runs_migrations()
        {
            $this->artisan('codeforge:install')
                ->assertExitCode(0);

            $this->assertDatabaseHasTable('database_health_metrics');
            $this->assertDatabaseHasTable('query_performance_logs');
            $this->assertDatabaseHasTable('schema_versions');
        }

        public function test_install_command_with_force_flag()
        {
            // Create existing config
            file_put_contents(config_path('codeforge-database-studio.php'), '<&lt;?php return [];');

            $this->artisan('codeforge:install', ['--force' => true])
                ->expectsOutput('Forcing reinstallation...')
                ->assertExitCode(0);

            // Verify config was overwritten
            $config = include config_path('codeforge-database-studio.php');
            $this->assertArrayHasKey('features', $config);
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integration Testing -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Integration Testing</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Integration Tests</h3>
                    <p class="text-gray-600 mb-3">Test interactions between multiple services:</p>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace HkDevs\CodeForgeStudio\Tests\Integration;

    use HkDevs\CodeForgeStudio\Tests\TestCase;
    use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
    use HkDevs\CodeForgeStudio\Services\CodeGenerationService;
    use Illuminate\Foundation\Testing\RefreshDatabase;

    class ServicesIntegrationTest extends TestCase
    {
        use RefreshDatabase;

        public function test_health_service_integrates_with_code_generation()
        {
            $healthService = app(DatabaseHealthService::class);
            $codeService = app(CodeGenerationService::class);

            // Generate model and check health impact
            $codeService->generateModel('TestModel', [
                'table' => 'test_models',
                'fields' => ['name' => 'string', 'email' => 'string'],
            ]);

            $healthMetrics = $healthService->getPerformanceMetrics();

            $this->assertArrayHasKey('generation_time', $healthMetrics);
            $this->assertLessThan(5000, $healthMetrics['generation_time']); // Under 5 seconds
        }

        public function test_database_operations_are_monitored()
        {
            $healthService = app(DatabaseHealthService::class);

            // Perform database operations
            \DB::table('users')->insert(['name' => 'Test User', 'email' => 'test@example.com']);

            // Check monitoring captured the operations
            $queryLogs = $healthService->getRecentQueryLogs();

            $this->assertGreaterThan(0, count($queryLogs));
            $this->assertStringContainsString('INSERT', $queryLogs[0]->query);
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Testing -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Feature Testing</h2>

            <div class="space-y-6">
                <!-- Filament Testing -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filament Interface Testing</h3>
                    <p class="text-gray-600 mb-3">Test Filament pages and widgets:</p>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace HkDevs\CodeForgeStudio\Tests\Feature;

    use HkDevs\CodeForgeStudio\Tests\TestCase;
    use Filament\Testing\TestsActions;
    use Filament\Testing\TestsPages;
    use Livewire\Testing\TestableLivewire;

    class DatabaseOverviewTest extends TestCase
    {
        use TestsActions, TestsPages;

        public function test_database_overview_page_loads()
        {
            $this->get(route('filament.admin.pages.database-overview'))
                ->assertOk()
                ->assertSee('Database Overview')
                ->assertSee('Health Status');
        }

        public function test_health_monitoring_widget_displays_metrics()
        {
            $component = \Livewire\Livewire::test(
                \HkDevs\CodeForgeStudio\Widgets\DatabaseHealthWidget::class
            );

            $component
                ->assertSee('Connection Status')
                ->assertSee('Response Time')
                ->assertSee('Query Performance');
        }

        public function test_can_refresh_health_metrics()
        {
            $component = \Livewire\Livewire::test(
                \HkDevs\CodeForgeStudio\Widgets\DatabaseHealthWidget::class
            );

            $component
                ->call('refreshMetrics')
                ->assertEmitted('metrics-refreshed');
        }
    }</code></pre>
                    </div>
                </div>

                <!-- End-to-End Workflow Testing -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">End-to-End Workflow Testing</h3>
                    <p class="text-gray-600 mb-3">Test complete user workflows:</p>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace HkDevs\CodeForgeStudio\Tests\Feature;

    use HkDevs\CodeForgeStudio\Tests\TestCase;
    use Illuminate\Foundation\Testing\RefreshDatabase;

    class CompleteWorkflowTest extends TestCase
    {
        use RefreshDatabase;

        public function test_complete_model_generation_workflow()
        {
            // 1. Install CodeForge
            $this->artisan('codeforge:install')->assertExitCode(0);

            // 2. Create a schema snapshot (CLI workflow)
            $this->artisan('codeforge:create-snapshot', [
                '--name' => 'test-snapshot',
            ])->assertExitCode(0);

            // 3. Generate documentation from the current schema
            $this->artisan('codeforge:generate-docs', [
                '--format' => 'markdown',
            ])->assertExitCode(0);

            // Model/migration/factory generation is performed via Filament generator pages,
            // not separate codeforge:generate:* Artisan commands.
        }

        public function test_complete_seeding_workflow()
        {
            // 1. Run discovered seeders via CLI
            $this->artisan('codeforge:run-seeders', [
                '--class' => 'DatabaseSeeder',
            ])->assertExitCode(0);

            // 2. Diagnose seeder discovery when troubleshooting
            $this->artisan('codeforge:diagnose-seeders')->assertExitCode(0);

            // 3. Smart data generation (when templates are configured)
            $this->artisan('codeforge:generate-data', [
                '--count' => 10,
            ])->assertExitCode(0);
            $this->assertDatabaseHas('seeder_execution_logs', [
                'seeder_class' => 'UserSeeder',
                'status' => 'completed',
            ]);
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testing Your Extensions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Testing CodeForge Extensions</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Extension Test Setup</h3>
                    <p class="text-gray-600 mb-3">Set up testing for your custom CodeForge extensions:</p>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace App\Tests\Feature;

    use HkDevs\CodeForgeStudio\Tests\TestCase;
    use App\Services\CustomDatabaseHealthService;

    class CustomExtensionTest extends TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            // Register your custom service
            $this->app->bind(
                \HkDevs\CodeForgeStudio\Services\DatabaseHealthService::class,
                CustomDatabaseHealthService::class
            );
        }

        public function test_custom_health_service_adds_metrics()
        {
            $service = app(\HkDevs\CodeForgeStudio\Services\DatabaseHealthService::class);
            $metrics = $service->getCustomMetrics();

            $this->assertArrayHasKey('redis_status', $metrics);
            $this->assertArrayHasKey('queue_size', $metrics);
            $this->assertArrayHasKey('storage_usage', $metrics);
        }

        public function test_custom_event_listener_works()
        {
            // Trigger event
            event(new \HkDevs\CodeForgeStudio\Events\DatabaseHealthChecked([
                'status' => 'healthy',
                'metrics' => ['response_time' => 150]
            ]));

            // Assert your custom listener processed it
            $this->assertDatabaseHas('custom_health_logs', [
                'status' => 'healthy',
                'response_time' => 150,
            ]);
        }
    }</code></pre>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Mock CodeForge Services</h3>
                    <p class="text-gray-600 mb-3">Mock CodeForge services for isolated testing:</p>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>namespace App\Tests\Unit;

    use Tests\TestCase;
    use Mockery;
    use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;

    class MyServiceTest extends TestCase
    {
        public function test_service_uses_codeforge_health_data()
        {
            // Mock CodeForge service
            $mockHealthService = Mockery::mock(DatabaseHealthService::class);
            $mockHealthService->shouldReceive('getConnectionStatus')
                ->andReturn(['connected' => true, 'response_time' => 100]);

            $this->app->instance(DatabaseHealthService::class, $mockHealthService);

            // Test your service that depends on CodeForge
            $myService = app(\App\Services\MyService::class);
            $result = $myService->processHealthData();

            $this->assertTrue($result['processing_successful']);
        }

        public function test_graceful_handling_when_codeforge_unavailable()
        {
            // Mock unavailable CodeForge
            $mockHealthService = Mockery::mock(DatabaseHealthService::class);
            $mockHealthService->shouldReceive('getConnectionStatus')
                ->andThrow(new \Exception('Service unavailable'));

            $this->app->instance(DatabaseHealthService::class, $mockHealthService);

            $myService = app(\App\Services\MyService::class);
            $result = $myService->processHealthData();

            // Should handle gracefully
            $this->assertFalse($result['codeforge_available']);
            $this->assertTrue($result['fallback_used']);
        }
    }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Automation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Test Automation & CI/CD</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Running Tests</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Basic Test Commands</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div><code>./vendor/bin/phpunit</code> - Run all tests</div>
                                <div><code>./vendor/bin/phpunit --testsuite=Unit</code> - Unit tests only</div>
                                <div><code>./vendor/bin/phpunit --testsuite=Feature</code> - Feature tests only</div>
                                <div><code>./vendor/bin/phpunit --coverage-html coverage</code> - With coverage</div>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">CodeForge Specific</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div><code>php tests/run-tests.php</code> - CodeForge test runner</div>
                                <div><code>php artisan test --parallel</code> - Parallel testing</div>
                                <div><code>composer test</code> - Full test suite</div>
                                <div><code>composer test:unit</code> - Unit tests only</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">GitHub Actions Integration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto">
    <code>name: CodeForge Tests

    on: [push, pull_request]

    jobs:
      test:
        runs-on: ubuntu-latest
        strategy:
          matrix:
            php: [8.3]
            laravel: [12.x, 13.x]

        steps:
        - uses: actions/checkout@v3

        - name: Setup PHP
          uses: shivammathur/setup-php@v2
          with:
            php-version: ${ matrix.php }
            extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite

        - name: Install dependencies
          run: |
            composer install --no-interaction --prefer-dist --optimize-autoloader

        - name: Run CodeForge tests
          run: |
            ./vendor/bin/phpunit --coverage-clover=coverage.xml

        - name: Upload coverage to Codecov
          uses: codecov/codecov-action@v3
          with:
            file: ./coverage.xml</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testing Best Practices -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-8 rounded-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Testing Best Practices</h2>
            <p class="text-gray-600 mb-6">Guidelines for effective testing with CodeForge Database Studio:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Test Organization</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Separate Test Types:</strong> Unit, Integration, Feature in different directories</li>
                        <li>• <strong>Use Factories:</strong> Create test data with model factories</li>
                        <li>• <strong>Database Seeding:</strong> Use RefreshDatabase trait for clean state</li>
                        <li>• <strong>Mock External Services:</strong> Mock APIs and external dependencies</li>
                        <li>• <strong>Test Isolation:</strong> Ensure tests don't depend on each other</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">CodeForge Integration</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Service Mocking:</strong> Mock CodeForge services for unit tests</li>
                        <li>• <strong>Configuration Testing:</strong> Test different feature configurations</li>
                        <li>• <strong>Event Testing:</strong> Test event listeners and dispatching</li>
                        <li>• <strong>Performance Testing:</strong> Verify monitoring overhead is minimal</li>
                        <li>• <strong>Extension Testing:</strong> Test custom extensions thoroughly</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 p-4 bg-white rounded-lg border border-green-200">
                <h4 class="font-semibold text-gray-900 mb-2">🧪 Testing Tips</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Use SQLite in-memory database for fast test execution</li>
                    <li>• Test both successful and failure scenarios</li>
                    <li>• Include edge cases in your test coverage</li>
                    <li>• Test configuration changes and feature toggles</li>
                    <li>• Use data providers for testing multiple scenarios</li>
                </ul>
            </div>
        </div>
    </div>
@endsection