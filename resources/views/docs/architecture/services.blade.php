@extends('codeforge-studio::layout.docs')

@section('title', 'Services - CodeForge Database Studio')

    @section('breadcrumbs')
        <li class='flex items-center'>
            <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
            <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
            </svg>
        </li>
        <li class='flex items-center'>
            <span class='text-gray-500'>Architecture</span>
            <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
            </svg>
        </li>
        <li class='text-primary-600 font-medium'>Services</li>
    @endsection

    @section('navigation')
        @include('codeforge-studio::docs.partials.navigation')
    @endsection

    @section('content')
        <div class="animate-fade-in-up">
            <!-- Header -->
            <div class="mb-12">
                <div class="flex items-center space-x-4 mb-6">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Service Architecture</h1>
                        <p class="text-xl text-gray-600">Comprehensive overview of service layer design and implementation
                            patterns</p>
                    </div>
                </div>
            </div>

            <!-- Overview -->
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-8 rounded-xl mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Layer Overview</h2>
                <p class="text-gray-600 mb-6">
                    CodeForge Database Studio implements a comprehensive service layer that handles business logic, data
                    processing, and complex operations. All services are registered as singletons for optimal performance and
                    resource management.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-4 rounded-lg border border-emerald-200">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">Business Logic</h3>
                        </div>
                        <p class="text-sm text-gray-600">Encapsulated business logic with clear separation of concerns</p>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-teal-200">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">Performance</h3>
                        </div>
                        <p class="text-sm text-gray-600">Singleton registration for optimal memory usage and performance</p>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900">Dependency Injection</h3>
                        </div>
                        <p class="text-sm text-gray-600">Laravel's container for automatic dependency resolution</p>
                    </div>
                </div>
            </div>

            <!-- Core Services -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Core Services Architecture</h2>
                <p class="text-gray-600 mb-6">CodeForge Database Studio implements a comprehensive service architecture with 17+
                    specialized services, each registered as singletons for optimal performance and memory efficiency.</p>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Core Services Column 1 -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-emerald-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Database Health & Monitoring</h3>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-sm">DatabaseHealthService</code>
                                </div>
                                <p class="text-gray-600 text-sm">Real-time database health monitoring, performance metrics
                                    collection, and query analysis with configurable thresholds.</p>
                            </div>
                        </div>

                        <div class="border-l-4 border-blue-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Code Generation Engine</h3>
                            <div class="space-y-2">
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">ModelGeneratorService</code>
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">MigrationGeneratorService</code>
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">FactoryGeneratorService</code>
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">SeederGeneratorService</code>
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">FilamentResourceGeneratorService</code>
                                </div>
                                <p class="text-gray-600 text-sm">Automated Laravel component generation with intelligent pattern
                                    recognition and relationship analysis.</p>
                            </div>
                        </div>

                        <div class="border-l-4 border-purple-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Data Management</h3>
                            <div class="space-y-2">
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">DataGenerationService</code>
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">SeederExecutionService</code>
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">SeederDiscoveryService</code>
                                </div>
                                <p class="text-gray-600 text-sm">Smart test data generation with relationship awareness,
                                    template-based patterns, and bulk optimization.</p>
                            </div>
                        </div>

                        <div class="border-l-4 border-green-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Documentation Generation</h3>
                            <div class="space-y-2">
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">DocumentationGenerationService</code>
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">SchemaDocumentationService</code>
                                </div>
                                <p class="text-gray-600 text-sm">Multi-format documentation generation (HTML, PDF, Markdown,
                                    JSON) with automated schema analysis.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Core Services Column 2 -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-orange-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Schema & Migration</h3>
                            <div class="space-y-2">
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">SchemaAnalyzerService</code>
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">MigrationTrackingService</code>
                                </div>
                                <p class="text-gray-600 text-sm">Database schema analysis, relationship discovery, migration
                                    tracking, and constraint validation.</p>
                            </div>
                        </div>

                        <div class="border-l-4 border-red-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">System & Validation</h3>
                            <div class="space-y-2">
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">IntelligentSuggestionService</code>
                                </div>
                                <p class="text-gray-600 text-sm">Smart code suggestions and intelligent analysis capabilities.</p>
                            </div>
                        </div>

                        <div class="border-l-4 border-teal-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Infrastructure Services</h3>
                            <div class="space-y-2">
                                <div class="flex flex-wrap gap-1">
                                    <code class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">AssetService</code>
                                    <code class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">StubTemplateService</code>
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    <code class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">LaravelTypesService</code>
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">CodeGenerationService</code>
                                </div>
                                <p class="text-gray-600 text-sm">Asset management, code template handling, Laravel type system
                                    integration, and core generation utilities.</p>
                            </div>
                        </div>

                        <div class="border-l-4 border-yellow-500 pl-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Additional Services</h3>
                            <div class="space-y-2">
                                <div class="flex flex-wrap gap-1">
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">DatabaseSeedingService</code>
                                    <code
                                        class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">DatabaseConnectionService</code>
                                </div>
                                <p class="text-gray-600 text-sm">Specialized database seeding operations and connection
                                    management with multi-database support.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Registration Pattern -->
                <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3">Service Registration Pattern</h4>
                    <p class="text-gray-600 text-sm mb-3">All services are registered as singletons for optimal performance:</p>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>// CodeForgeStudioServiceProvider.php
        $this->app->singleton(DatabaseHealthService::class);
        $this->app->singleton(ModelGeneratorService::class);
        $this->app->singleton(DataGenerationService::class);
        // ... and 14+ more services</code></pre>
                </div>
            </div>

            <!-- Service Registration -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Registration</h2>
                <p class="text-gray-600 mb-6">All services are registered as singletons in the Laravel service container for
                    optimal performance:</p>

                <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>// CodeForgeStudioServiceProvider.php
            public function register(): void
            {
                // Health and monitoring services
                $this->app->singleton(DatabaseHealthService::class);

                // Code generation services
                $this->app->singleton(ModelGeneratorService::class);
                $this->app->singleton(MigrationGeneratorService::class);
                $this->app->singleton(FactoryGeneratorService::class);
                $this->app->singleton(SeederGeneratorService::class);
                $this->app->singleton(FilamentResourceGeneratorService::class);

                // Data management services
                $this->app->singleton(DataGenerationService::class);
                $this->app->singleton(SeederExecutionService::class);
                $this->app->singleton(SeederDiscoveryService::class);

                // Documentation services
                $this->app->singleton(DocumentationGenerationService::class);
                $this->app->singleton(SchemaDocumentationService::class);

                // Utility services
                $this->app->singleton(MigrationTrackingService::class);
                $this->app->singleton(LicenseValidationService::class);
                $this->app->singleton(AssetService::class);
            }</code></pre>
            </div>

            <!-- Service Architecture Patterns -->
            <div class="bg-gray-50 p-8 rounded-xl mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Architecture Patterns</h2>
                <p class="text-gray-600 mb-6">Services follow established patterns for consistency and maintainability:</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-semibold text-gray-900 mb-2">Single Responsibility</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Each service has a clear, focused purpose</li>
                            <li>• Separation of data access and business logic</li>
                            <li>• Modular design for easy testing and maintenance</li>
                            <li>• Clear interfaces and contracts</li>
                        </ul>
                    </div>

                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-semibold text-gray-900 mb-2">Dependency Injection</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Constructor injection for required dependencies</li>
                            <li>• Interface-based contracts for flexibility</li>
                            <li>• Automatic resolution through Laravel's container</li>
                            <li>• Easy mocking for unit testing</li>
                        </ul>
                    </div>

                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-semibold text-gray-900 mb-2">Error Handling</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Comprehensive exception handling</li>
                            <li>• Graceful degradation for non-critical features</li>
                            <li>• Detailed error logging and reporting</li>
                            <li>• User-friendly error messages</li>
                        </ul>
                    </div>

                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-semibold text-gray-900 mb-2">Performance Optimization</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Singleton registration for memory efficiency</li>
                            <li>• Lazy loading of heavy dependencies</li>
                            <li>• Caching strategies for expensive operations</li>
                            <li>• Background processing for heavy tasks</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Service Layer Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Layer Overview</h2>
                <p class="text-gray-600 mb-6">The service layer acts as the business logic orchestration layer, providing a
                    clean separation between controllers and data access while implementing complex operations and workflows.
                </p>

                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Core Service Principles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <ul class="space-y-2 text-gray-700">
                            <li>� <strong>Single Responsibility:</strong> Each service handles one domain area</li>
                            <li>� <strong>Dependency Injection:</strong> Constructor-based dependency resolution</li>
                            <li>� <strong>Interface Contracts:</strong> Consistent method signatures and return types</li>
                        </ul>
                        <ul class="space-y-2 text-gray-700">
                            <li>� <strong>Error Handling:</strong> Comprehensive exception management</li>
                            <li>� <strong>Logging Integration:</strong> Detailed operation logging</li>
                            <li>� <strong>Performance Optimization:</strong> Caching and query optimization</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Core Services -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Core Services</h2>

                <div class="space-y-6">
                    <!-- DatabaseHealthService -->
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-blue-900">DatabaseHealthService</h3>
                                <p class="text-blue-700">Real-time database health monitoring and performance analysis</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-2">Key Methods</h4>
                                <ul class="space-y-1 text-sm text-blue-800">
                                    <li>� <code>getOverallHealthScore()</code> - Calculate overall health rating</li>
                                    <li>� <code>performHealthCheck()</code> - Comprehensive health assessment</li>
                                    <li>� <code>checkConnectionHealth($connection)</code> - Connection-specific health</li>
                                    <li>� <code>analyzeQueryPerformance()</code> - Query performance analysis</li>
                                    <li>� <code>generateHealthReport()</code> - Detailed health reporting</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-2">Capabilities</h4>
                                <ul class="space-y-1 text-sm text-blue-800">
                                    <li>� Connection timeout detection and monitoring</li>
                                    <li>� Performance metrics collection and analysis</li>
                                    <li>� Health score calculation with trend analysis</li>
                                    <li>� Alert generation based on configurable thresholds</li>
                                    <li>� Resource usage monitoring and optimization suggestions</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- SchemaAnalyzerService -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-green-900">SchemaAnalyzerService</h3>
                                <p class="text-green-700">Advanced database schema analysis and relationship discovery</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-green-900 mb-2">Key Methods</h4>
                                <ul class="space-y-1 text-sm text-green-800">
                                    <li>� <code>discoverRelationships()</code> - Automatic relationship detection</li>
                                    <li>� <code>analyzeTablePerformance($table)</code> - Table-specific analysis</li>
                                    <li>� <code>getOptimizationRecommendations()</code> - Performance suggestions</li>
                                    <li>� <code>generateSchemaSummary()</code> - Complete schema overview</li>
                                    <li>� <code>validateSchemaIntegrity()</code> - Integrity checking</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-green-900 mb-2">Capabilities</h4>
                                <ul class="space-y-1 text-sm text-green-800">
                                    <li>� Foreign key and implicit relationship discovery</li>
                                    <li>� Index analysis and optimization recommendations</li>
                                    <li>� Query pattern analysis and performance insights</li>
                                    <li>� Schema visualization and dependency mapping</li>
                                    <li>� Data integrity validation and reporting</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- DataGenerationService -->
                    <div class="bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-purple-900">DataGenerationService</h3>
                                <p class="text-purple-700">Intelligent data generation with relationship awareness</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-purple-900 mb-2">Key Methods</h4>
                                <ul class="space-y-1 text-sm text-purple-800">
                                    <li>� <code>generateForTable($table, $count)</code> - Table-specific generation</li>
                                    <li>� <code>generateWithTemplate($table, $template)</code> - Template-based generation</li>
                                    <li>� <code>seedMultipleTables($config)</code> - Bulk seeding operations</li>
                                    <li>� <code>generateRealisticData($field, $type)</code> - Context-aware data</li>
                                    <li>� <code>validateRelationships($data)</code> - Relationship validation</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-purple-900 mb-2">Capabilities</h4>
                                <ul class="space-y-1 text-sm text-purple-800">
                                    <li>� Relationship-aware data generation</li>
                                    <li>� Realistic pattern recognition and application</li>
                                    <li>� Custom template system for data patterns</li>
                                    <li>� Bulk operations with performance optimization</li>
                                    <li>� Constraint validation and integrity checking</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Communication Patterns -->
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-8 rounded-xl mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Service Communication Patterns</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-semibold text-gray-900 mb-2">Direct Service Calls</h4>
                        <p class="text-sm text-gray-700 mb-3">Services directly inject and call other services for immediate
                            operations</p>
                        <pre class="text-xs bg-gray-100 p-2 rounded"><code>public function __construct(
                    DatabaseHealthService $health
                ) {
                    $this->health = $health;
                }</code></pre>
                    </div>

                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-semibold text-gray-900 mb-2">Event-Driven Communication</h4>
                        <p class="text-sm text-gray-700 mb-3">Services communicate through Laravel events for loose coupling</p>
                        <pre class="text-xs bg-gray-100 p-2 rounded"><code>event(new SchemaAnalyzed(
                    $results, $metadata
                ));</code></pre>
                    </div>

                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-semibold text-gray-900 mb-2">Queue-Based Processing</h4>
                        <p class="text-sm text-gray-700 mb-3">Heavy operations are processed asynchronously through queues</p>
                        <pre class="text-xs bg-gray-100 p-2 rounded"><code>GenerateSchemaDocumentation::
                    dispatch($config);</code></pre>
                    </div>
                </div>
            </div>

            <!-- Service Testing Strategy -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Testing Strategy</h2>
                <p class="text-gray-600 mb-6">Comprehensive testing approach ensuring service reliability and performance:</p>

                <!-- Service Usage Examples -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Service Usage Examples</h2>
                    <p class="text-gray-600 mb-6">Practical examples of how to use CodeForge Database Studio services in your
                        application:</p>

                    <div class="space-y-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Constructor Injection (Recommended)</h4>
                            <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
        use HkDevs\CodeForgeStudio\Services\DataGenerationService;

        class DatabaseController extends Controller
        {
            public function __construct(
                private DatabaseHealthService $healthService,
                private DataGenerationService $dataService
            ) {}

            public function healthCheck()
            {
                $healthScore = $this->healthService->getOverallHealthScore();
                $report = $this->healthService->generateHealthReport();

                return response()->json([
                    'health_score' => $healthScore,
                    'report' => $report
                ]);
            }
        }</code></pre>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Service Container Resolution</h4>
                            <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// Resolve service from Laravel container
        $modelGenerator = app(ModelGeneratorService::class);

        // Generate a model with relationships
        $modelCode = $modelGenerator->generateModel('Post', [
            'title' => 'string',
            'content' => 'text', 
            'published_at' => 'timestamp',
            'user_id' => 'bigInteger'
        ]);

        // Generate factory and seeder
        $factoryCode = app(FactoryGeneratorService::class)->generateFactory('Post');
        $seederCode = app(SeederGeneratorService::class)->generateSeeder('Post');</code></pre>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Data Generation with Templates</h4>
                            <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// Generate realistic test data
        $dataService = app(DataGenerationService::class);

        // Generate data for specific table
        $userData = $dataService->generateForTable('users', 100);

        // Use custom template for specific patterns
        $blogData = $dataService->generateWithTemplate('posts', 'blog_template');

        // Generate data for multiple related tables
        $dataService->seedMultipleTables([
            'users' => ['count' => 50, 'template' => 'user_template'],
            'posts' => ['count' => 200, 'template' => 'blog_template'],
            'comments' => ['count' => 500, 'template' => 'comment_template']
        ]);</code></pre>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Schema Analysis and Documentation</h4>
                            <pre class="bg-gray-800 text-white p-4 rounded-lg overflow-x-auto"><code>// Analyze database schema
        $schemaService = app(SchemaAnalyzerService::class);
        $relationships = $schemaService->discoverRelationships();
        $recommendations = $schemaService->getOptimizationRecommendations();

        // Generate comprehensive documentation
        $docService = app(DocumentationGenerationService::class);
        $htmlDocs = $docService->generateDocumentation('html');
        $pdfDocs = $docService->generateDocumentation('pdf');

        // Schema-specific documentation
        $schemaDocService = app(SchemaDocumentationService::class);
        $schemaDocs = $schemaDocService->generateSchemaDocumentation();</code></pre>
                        </div>
                    </div>
                </div>

                <!-- Service Performance & Best Practices -->
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-8 rounded-xl mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Performance & Best Practices</h2>
                    <p class="text-gray-600 mb-6">Guidelines for optimal service usage and performance:</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-4 rounded-lg border">
                            <h4 class="font-semibold text-gray-900 mb-3">Memory Optimization</h4>
                            <ul class="space-y-2 text-gray-700 text-sm">
                                <li>• <strong>Singleton Pattern:</strong> All services are registered as singletons</li>
                                <li>• <strong>Lazy Loading:</strong> Dependencies are loaded only when needed</li>
                                <li>• <strong>Memory Management:</strong> Services implement efficient memory usage patterns
                                </li>
                                <li>• <strong>Resource Cleanup:</strong> Automatic cleanup of temporary resources</li>
                            </ul>
                        </div>
                        <div class="bg-white p-4 rounded-lg border">
                            <h4 class="font-semibold text-gray-900 mb-3">Best Practices</h4>
                            <ul class="space-y-2 text-gray-700 text-sm">
                                <li>• <strong>Constructor Injection:</strong> Prefer dependency injection over manual resolution
                                </li>
                                <li>• <strong>Error Handling:</strong> Always handle service exceptions appropriately</li>
                                <li>• <strong>Caching:</strong> Leverage built-in caching for expensive operations</li>
                                <li>• <strong>Background Processing:</strong> Use queues for heavy operations</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Integration Patterns -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Integration Patterns</h2>
                    <p class="text-gray-600 mb-6">Common patterns for integrating CodeForge Database Studio services into your
                        Laravel application:</p>

                    <div class="space-y-6">
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-2">Middleware Integration</h4>
                            <p class="text-blue-800 text-sm mb-3">Use services in custom middleware for request processing:</p>
                            <pre class="bg-gray-800 text-white p-3 rounded text-sm overflow-x-auto"><code>class DatabaseHealthMiddleware
        {
            public function __construct(private DatabaseHealthService $health) {}

            public function handle($request, Closure $next)
            {
                if (!$this->health->performHealthCheck()) {
                    return response()->json(['error' => 'Database unavailable'], 503);
                }
                return $next($request);
            }
        }</code></pre>
                        </div>

                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <h4 class="font-semibold text-green-900 mb-2">Artisan Command Integration</h4>
                            <p class="text-green-800 text-sm mb-3">Leverage services in custom Artisan commands:</p>
                            <pre class="bg-gray-800 text-white p-3 rounded text-sm overflow-x-auto"><code>class GenerateTestDataCommand extends Command
        {
            public function __construct(private DataGenerationService $dataService) 
            {
                parent::__construct();
            }

            public function handle()
            {
                $this->dataService->seedMultipleTables([
                    'users' => ['count' => 100],
                    'posts' => ['count' => 500]
                ]);
                $this->info('Test data generated successfully!');
            }
        }</code></pre>
                        </div>

                        <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                            <h4 class="font-semibold text-purple-900 mb-2">Event Listener Integration</h4>
                            <p class="text-purple-800 text-sm mb-3">Use services in event listeners for automated workflows:</p>
                            <pre class="bg-gray-800 text-white p-3 rounded text-sm overflow-x-auto"><code>class ModelCreatedListener
        {
            public function __construct(
                private DocumentationGenerationService $docService
            ) {}

            public function handle(ModelCreated $event)
            {
                // Auto-generate documentation when new models are created
                $this->docService->generateDocumentation('html');
            }
        }</code></pre>
                        </div>
                    </div>
                </div>
            </div>
    @endsection