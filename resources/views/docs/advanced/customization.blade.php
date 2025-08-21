@extends('codeforge-studio::layout.docs')

@section('title', 'Customization - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Customization</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Plugin Customization</h1>
            <p class="text-lg text-gray-600">CodeForge Database Studio provides extensive customization options through
                configuration files, custom templates, navigation overrides, and feature toggles to adapt the plugin to your
                specific workflow needs.</p>
        </div>

        <!-- Configuration Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Configuration System</h2>
            <p class="text-gray-600 mb-6">The plugin uses a comprehensive configuration system allowing you to customize
                every aspect of functionality, appearance, and behavior.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Core Settings</h3>
                    </div>
                    <p class="text-sm text-gray-600">License configuration, feature toggles, navigation settings, and plugin
                        registration options.</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Feature Control</h3>
                    </div>
                    <p class="text-sm text-gray-600">Enable/disable specific features, configure monitoring thresholds, and
                        customize code generation behavior.</p>
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
                        <h3 class="font-semibold text-gray-900">Templates</h3>
                    </div>
                    <p class="text-sm text-gray-600">Custom stub templates, code generation patterns, and documentation
                        templates for consistent output.</p>
                </div>
            </div>
        </div>

        <!-- Core Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Core Configuration Options</h2>

            <div class="space-y-6">
                <!-- License and Registration -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">License & Registration</h3>
                    <p class="text-gray-600 mb-3">Configure license validation and automatic plugin registration:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>// config/codeforge-database-studio.php
    return [
        'license_key' => env('CODEFORGE_LICENSE_KEY'),
        'license_validation' => [
            'enabled' => env('CODEFORGE_LICENSE_VALIDATION', true),
            'cache_duration' => 3600, // 1 hour
            'grace_period' => 7, // Days after license expiry
        ],

        'auto_register' => true,
        'register_on_panels' => ['admin'], // Filament panels
    ];</code></pre>
                    </div>
                </div>

                <!-- Navigation Customization -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Navigation Customization</h3>
                    <p class="text-gray-600 mb-3">Customize navigation group, icons, and organization:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>'navigation' => [
        'group' => 'Database Studio', // Custom navigation group
        'sort' => 1, // Navigation group sorting
        'icon' => 'heroicon-o-server', // Group icon
    ],

    // Feature toggles for navigation organization
    'features' => [
        'schema_designer' => true,
        'migration_manager' => true,
        'health_monitoring' => true,
        'smart_seeding' => true,
        'documentation_generator' => true,
        'code_generation' => true,
    ],</code></pre>
                    </div>
                </div>

                <!-- Database Configuration -->
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Database Configuration</h3>
                    <p class="text-gray-600 mb-3">Configure database connections and monitoring behavior:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>'connections' => [
        'default' => env('DB_CONNECTION', 'mysql'),
        'allowed' => ['mysql', 'pgsql', 'sqlite', 'sqlsrv'],
    ],

    'health_monitoring' => [
        'enabled' => true,
        'check_interval' => 300, // 5 minutes
        'slow_query_threshold' => 1000, // milliseconds
        'connection_timeout' => 5, // seconds
    ],</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Advanced Configuration</h2>

            <div class="space-y-6">
                <!-- Code Generation Customization -->
                <div class="border-l-4 border-indigo-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Code Generation Paths</h3>
                    <p class="text-gray-600 mb-3">Customize output paths and namespaces for generated code:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>'code_generation' => [
        'output_path' => [
            'models' => 'app/Models',
            'migrations' => 'database/migrations',
            'factories' => 'database/factories',
            'seeders' => 'database/seeders',
            'resources' => 'app/Filament/Resources',
        ],
        'namespace' => [
            'models' => 'App\\Models',
            'factories' => 'Database\\Factories',
            'seeders' => 'Database\\Seeders',
            'resources' => 'App\\Filament\\Resources',
        ],
        'auto_format' => true,
        'backup_existing' => true,
    ],</code></pre>
                    </div>
                </div>

                <!-- Query Logging Configuration -->
                <div class="border-l-4 border-orange-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Query Performance Logging</h3>
                    <p class="text-gray-600 mb-3">Configure automatic query logging and performance monitoring:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>'query_logging' => [
        'slow_query_threshold' => 1000, // Log queries slower than 1s
        'log_all_queries' => false, // Set true to log all queries
        'max_log_entries' => 10000, // Maximum log entries
        'cleanup_older_than_days' => 30, // Auto cleanup
        'skip_patterns' => [
            'show tables',
            'show columns',
            'information_schema',
            'query_performance_logs',
        ],
    ],</code></pre>
                    </div>
                </div>

                <!-- Security Configuration -->
                <div class="border-l-4 border-red-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Security Settings</h3>
                    <p class="text-gray-600 mb-3">Configure security restrictions and confirmation requirements:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>'security' => [
        'require_confirmation' => [
            'drop_table' => true,
            'drop_column' => true,
            'rollback_migration' => true,
        ],
        'allowed_operations' => [
            'create_table' => true,
            'alter_table' => true,
            'drop_table' => false, // Disabled for safety
            'create_migration' => true,
            'rollback_migration' => true,
        ],
    ],</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Template Customization -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Template Customization</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio uses customizable stub templates for all code
                generation. You can override default templates with your own versions.</p>

            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Available Template Types</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="font-semibold text-gray-900 mb-2">Core Templates</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <code>model.stub</code> - Eloquent model template</li>
                                <li>• <code>migration.create.stub</code> - Migration template</li>
                                <li>• <code>factory.stub</code> - Model factory template</li>
                                <li>• <code>seeder.stub</code> - Database seeder template</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="font-semibold text-gray-900 mb-2">Advanced Templates</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <code>resource.stub</code> - Filament resource template</li>
                                <li>• <code>policy.stub</code> - Authorization policy template</li>
                                <li>• Custom documentation templates</li>
                                <li>• API endpoint templates</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Creating Custom Templates</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code># 1. Publish the templates to your application
    php artisan vendor:publish --tag=codeforge-stubs --force

    # 2. Edit templates in resources/stubs/codeforge/
    # Templates support placeholders like:
    # { namespace } - Target namespace
    # { class } - Class name
    # { table } - Table name
    # { relationships } - Model relationships
    # { fillable } - Fillable fields

    # 3. Use StubTemplateService for advanced customization
    $stubService = app(\HkDevs\CodeForgeStudio\Services\StubTemplateService::class);
    $stubService->setCustomTemplate('model', $customTemplate);
    $content = $stubService->generateFromStub('model', $replacements);</code></pre>
                </div>
            </div>
        </div>

        <!-- Plugin Customization -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Plugin-Level Customization</h2>
            <p class="text-gray-600 mb-6">Advanced customization through the plugin class and service provider:</p>

            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Custom Plugin Configuration</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// In your AdminPanelProvider
    use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugins([
                CodeForgeStudioPlugin::make()
                    ->enableSchemaDesigner()
                    ->enableMigrationManager()
                    ->disableHealthMonitoring() // Disable specific features
                    ->customNavigationGroup('Custom Database Tools')
                    ->customIcon('heroicon-o-database'),
            ]);
    }</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Service Container Customization</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// In your AppServiceProvider
    public function register()
    {
        // Override default services with custom implementations
        $this->app->bind(
            \HkDevs\CodeForgeStudio\Services\DatabaseHealthService::class,
            \App\Services\CustomDatabaseHealthService::class
        );

        // Extend existing services
        $this->app->extend(
            \HkDevs\CodeForgeStudio\Services\CodeGenerationService::class,
            function ($service, $app) {
                $service->setCustomTemplate('model', $app['custom.model.template']);
                return $service;
            }
        );
    }</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Event Listener Customization</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// In EventServiceProvider
    protected $listen = [
        \HkDevs\CodeForgeStudio\Events\DatabaseHealthChecked::class => [
            \App\Listeners\CustomHealthListener::class,
        ],
        \HkDevs\CodeForgeStudio\Events\ModelGenerated::class => [
            \App\Listeners\ModelGeneratedListener::class,
        ],
    ];

    // Custom listener example
    class CustomHealthListener
    {
        public function handle(DatabaseHealthChecked $event)
        {
            // Send custom notifications
            // Log to external services
            // Trigger custom actions
        }
    }</code></pre>
                </div>
            </div>
        </div>

        <!-- Environment-Specific Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Environment-Specific Configuration</h2>
            <p class="text-gray-600 mb-6">Configure different behaviors for development, staging, and production
                environments:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Development Environment</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code># .env
    CODEFORGE_LICENSE_VALIDATION=false
    CODEFORGE_QUERY_LOGGING=true
    CODEFORGE_HEALTH_MONITORING=true
    CODEFORGE_AUTO_BACKUP=false

    # Enable all development features
    CODEFORGE_ENABLE_SCHEMA_DESIGNER=true
    CODEFORGE_ENABLE_CODE_GENERATION=true
    CODEFORGE_DEBUG_MODE=true</code></pre>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Production Environment</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code># .env
    CODEFORGE_LICENSE_VALIDATION=true
    CODEFORGE_QUERY_LOGGING=false
    CODEFORGE_HEALTH_MONITORING=true
    CODEFORGE_AUTO_BACKUP=true

    # Restrict dangerous operations
    CODEFORGE_ALLOW_DROP_OPERATIONS=false
    CODEFORGE_REQUIRE_CONFIRMATIONS=true
    CODEFORGE_DEBUG_MODE=false</code></pre>
                </div>
            </div>
        </div>

        <!-- Customization Best Practices -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Customization Best Practices</h2>
            <p class="text-gray-600 mb-6">Guidelines for safe and effective plugin customization:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Configuration Management</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Environment Variables:</strong> Use .env for sensitive settings</li>
                        <li>• <strong>Version Control:</strong> Keep config files in version control</li>
                        <li>• <strong>Documentation:</strong> Document custom configurations</li>
                        <li>• <strong>Validation:</strong> Validate configuration values</li>
                        <li>• <strong>Fallbacks:</strong> Provide sensible defaults</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Template Management</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Backup Originals:</strong> Keep copies of original templates</li>
                        <li>• <strong>Version Templates:</strong> Track template changes</li>
                        <li>• <strong>Test Generation:</strong> Test custom templates thoroughly</li>
                        <li>• <strong>Team Sharing:</strong> Share templates across team members</li>
                        <li>• <strong>Documentation:</strong> Document custom placeholders</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection