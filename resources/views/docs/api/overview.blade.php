@extends('codeforge-studio::layout.docs')

@section('title', 'API Overview - CodeForge Database Studio')
@section('description', 'Complete API reference for CodeForge Database Studio services, commands, and events.')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('codeforge.docs.home') }}" class="text-gray-500 hover:text-primary-600">Documentation</a>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="flex items-center">
        <span class="text-gray-500">API</span>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-primary-600 font-medium">Overview</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">API Reference</h1>
                    <p class="text-xl text-gray-600">Complete API documentation for developers</p>
                </div>
            </div>
            <p class="text-lg text-gray-600">CodeForge Database Studio provides a comprehensive API including 17+ services,
                15+ Artisan commands, and 8+ Filament resources for programmatic access to all plugin functionality.</p>
        </div>

        <!-- API Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('codeforge.docs.api.services') }}"
                class="group bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div
                    class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Services</h3>
                <p class="text-sm text-gray-600">17+ service classes for database operations, code generation, and health
                    monitoring</p>
                <div class="mt-3 text-xs text-blue-600 font-medium">17+ Classes →</div>
            </a>

            <a href="{{ route('codeforge.docs.api.commands') }}"
                class="group bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div
                    class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-200 transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Commands</h3>
                <p class="text-sm text-gray-600">15+ Artisan commands for installation, migrations, and data management</p>
                <div class="mt-3 text-xs text-green-600 font-medium">15+ Commands →</div>
            </a>

            <a href="{{ route('codeforge.docs.api.filament-resources') }}"
                class="group bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div
                    class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Resources</h3>
                <p class="text-sm text-gray-600">8+ Filament resources for admin interface and data management</p>
                <div class="mt-3 text-xs text-purple-600 font-medium">8+ Resources →</div>
            </a>

            <a href="{{ route('codeforge.docs.architecture.events') }}"
                class="group bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div
                    class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-orange-200 transition-colors">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Events</h3>
                <p class="text-sm text-gray-600">Event system for real-time updates and custom integrations</p>
                <div class="mt-3 text-xs text-orange-600 font-medium">View Events →</div>
            </a>
        </div>

        <!-- API Architecture -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">API Architecture</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio follows Laravel best practices with a layered
                architecture that separates concerns and provides clean interfaces for all functionality.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Service Layer</h3>
                    <p class="text-gray-600 text-sm mb-3">Business logic encapsulated in service classes with dependency
                        injection and singleton registration.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Database operations</li>
                        <li>• Code generation</li>
                        <li>• Health monitoring</li>
                        <li>• License validation</li>
                    </ul>
                </div>

                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Command Layer</h3>
                    <p class="text-gray-600 text-sm mb-3">Artisan commands for CLI operations, automation, and maintenance
                        tasks.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Installation & setup</li>
                        <li>• Data generation</li>
                        <li>• Migration management</li>
                        <li>• Health monitoring</li>
                    </ul>
                </div>

                <div class="border-l-4 border-purple-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Resource Layer</h3>
                    <p class="text-gray-600 text-sm mb-3">Filament resources providing admin interface for data management
                        and monitoring.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• CRUD operations</li>
                        <li>• Data visualization</li>
                        <li>• Bulk actions</li>
                        <li>• Export capabilities</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Getting Started -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Getting Started with the API</h2>
            <p class="text-gray-600 mb-6">Quick examples of how to use the different API components:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Using Services</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>// Inject service via constructor
    public function __construct(
        private DatabaseHealthService $healthService,
        private CodeGenerationService $codeService
    ) {}

    // Use service methods
    $health = $this->healthService->getOverallHealth();
    $generated = $this->codeService->generateModel($table);</code></pre>
                </div>

                <div class="bg-white p-6 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Running Commands</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code># Installation command
    php artisan codeforge:install

    # Generate test data
    php artisan codeforge:generate-data users 100

    # Collect health metrics
    php artisan codeforge:collect-health-metrics</code></pre>
                </div>
            </div>
        </div>

        <!-- Core Concepts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Core API Concepts</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Service Registration</h3>
                    <p class="text-gray-600 mb-3">All services are registered as singletons in the Laravel container:</p>
                    <pre class="bg-gray-50 p-4 rounded text-sm text-gray-700 overflow-x-auto"><code>// In CodeForgeStudioServiceProvider
    $this->app->singleton(DatabaseHealthService::class);
    $this->app->singleton(CodeGenerationService::class);
    $this->app->singleton(LicenseValidationService::class);</code></pre>
                </div>

                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Command Registration</h3>
                    <p class="text-gray-600 mb-3">Commands are registered with descriptive signatures and help text:</p>
                    <pre class="bg-gray-50 p-4 rounded text-sm text-gray-700 overflow-x-auto"><code>// Command signature
    protected $signature = 'codeforge:generate-data 
                            {table : Table name to generate data for}
                            {count=10 : Number of records to generate}
                            {--template= : Use custom template}';</code></pre>
                </div>

                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Resource Configuration</h3>
                    <p class="text-gray-600 mb-3">Filament resources provide full CRUD capabilities with relationships:</p>
                    <pre class="bg-gray-50 p-4 rounded text-sm text-gray-700 overflow-x-auto"><code>// Resource navigation
    protected static ?string $navigationGroup = 'Database Management';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 1;</code></pre>
                </div>
            </div>
        </div>

        <!-- Error Handling -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Error Handling & Validation</h2>
            <p class="text-gray-600 mb-6">The API implements comprehensive error handling and validation:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Service Exceptions</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>try {
        $result = $service->performOperation();
    } catch (DatabaseConnectionException $e) {
        Log::error('Database connection failed', [
            'message' => $e->getMessage(),
            'connection' => $e->getConnection()
        ]);
    } catch (LicenseValidationException $e) {
        throw new UnauthorizedAccessException();
    }</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Command Validation</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>// Input validation in commands
    if (!Schema::hasTable($table)) {
        $this->error("Table '{$table}' does not exist");
        return self::FAILURE;
    }

    if ($count < 1 || $count > 10000) {
        $this->error('Count must be between 1 and 10,000');
        return self::FAILURE;
    }</code></pre>
                </div>
            </div>
        </div>

        <!-- API Reference Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Service Reference</h3>
                <p class="text-gray-600 mb-4">Complete documentation for all 17+ service classes including method
                    signatures, parameters, and return types.</p>
                <a href="{{ route('codeforge.docs.api.services') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    View Services
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Command Reference</h3>
                <p class="text-gray-600 mb-4">Detailed guide for all Artisan commands with examples, options, and best
                    practices.</p>
                <a href="{{ route('codeforge.docs.api.commands') }}"
                    class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
                    View Commands
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endsection