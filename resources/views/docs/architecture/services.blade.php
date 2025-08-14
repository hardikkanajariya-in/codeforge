@extends('codeforge-database-studio::layout.docs')

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
    @include('codeforge-database-studio::docs.partials.navigation')
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
                        <li>• <strong>Single Responsibility:</strong> Each service handles one domain area</li>
                        <li>• <strong>Dependency Injection:</strong> Constructor-based dependency resolution</li>
                        <li>• <strong>Interface Contracts:</strong> Consistent method signatures and return types</li>
                    </ul>
                    <ul class="space-y-2 text-gray-700">
                        <li>• <strong>Error Handling:</strong> Comprehensive exception management</li>
                        <li>• <strong>Logging Integration:</strong> Detailed operation logging</li>
                        <li>• <strong>Performance Optimization:</strong> Caching and query optimization</li>
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
                                <li>• <code>getOverallHealthScore()</code> - Calculate overall health rating</li>
                                <li>• <code>performHealthCheck()</code> - Comprehensive health assessment</li>
                                <li>• <code>checkConnectionHealth($connection)</code> - Connection-specific health</li>
                                <li>• <code>analyzeQueryPerformance()</code> - Query performance analysis</li>
                                <li>• <code>generateHealthReport()</code> - Detailed health reporting</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-2">Capabilities</h4>
                            <ul class="space-y-1 text-sm text-blue-800">
                                <li>• Connection timeout detection and monitoring</li>
                                <li>• Performance metrics collection and analysis</li>
                                <li>• Health score calculation with trend analysis</li>
                                <li>• Alert generation based on configurable thresholds</li>
                                <li>• Resource usage monitoring and optimization suggestions</li>
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
                                <li>• <code>discoverRelationships()</code> - Automatic relationship detection</li>
                                <li>• <code>analyzeTablePerformance($table)</code> - Table-specific analysis</li>
                                <li>• <code>getOptimizationRecommendations()</code> - Performance suggestions</li>
                                <li>• <code>generateSchemaSummary()</code> - Complete schema overview</li>
                                <li>• <code>validateSchemaIntegrity()</code> - Integrity checking</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-green-900 mb-2">Capabilities</h4>
                            <ul class="space-y-1 text-sm text-green-800">
                                <li>• Foreign key and implicit relationship discovery</li>
                                <li>• Index analysis and optimization recommendations</li>
                                <li>• Query pattern analysis and performance insights</li>
                                <li>• Schema visualization and dependency mapping</li>
                                <li>• Data integrity validation and reporting</li>
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
                                <li>• <code>generateForTable($table, $count)</code> - Table-specific generation</li>
                                <li>• <code>generateWithTemplate($table, $template)</code> - Template-based generation</li>
                                <li>• <code>seedMultipleTables($config)</code> - Bulk seeding operations</li>
                                <li>• <code>generateRealisticData($field, $type)</code> - Context-aware data</li>
                                <li>• <code>validateRelationships($data)</code> - Relationship validation</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-purple-900 mb-2">Capabilities</h4>
                            <ul class="space-y-1 text-sm text-purple-800">
                                <li>• Relationship-aware data generation</li>
                                <li>• Realistic pattern recognition and application</li>
                                <li>• Custom template system for data patterns</li>
                                <li>• Bulk operations with performance optimization</li>
                                <li>• Constraint validation and integrity checking</li>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Unit Testing</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• Mock dependencies for isolated testing</li>
                        <li>• Test all public methods with various inputs</li>
                        <li>• Validate error handling and edge cases</li>
                        <li>• Performance benchmarking for critical methods</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Integration Testing</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• Test service interactions and workflows</li>
                        <li>• Validate database operations and transactions</li>
                        <li>• Test real database connections and queries</li>
                        <li>• End-to-end feature testing with multiple services</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Performance Considerations -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Considerations</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                    <h4 class="font-semibold text-orange-800 text-lg mb-2">Caching Strategy</h4>
                    <p class="text-orange-700 mb-3">Intelligent caching at multiple levels to optimize performance:</p>
                    <ul class="text-sm text-orange-800 space-y-1">
                        <li>• Service-level result caching</li>
                        <li>• Database query result caching</li>
                        <li>• Configuration and metadata caching</li>
                        <li>• Time-based cache invalidation</li>
                    </ul>
                </div>
                <div class="bg-teal-50 p-6 rounded-lg border border-teal-200">
                    <h4 class="font-semibold text-teal-800 text-lg mb-2">Optimization Techniques</h4>
                    <p class="text-teal-700 mb-3">Performance optimization strategies implemented across services:</p>
                    <ul class="text-sm text-teal-800 space-y-1">
                        <li>• Lazy loading of expensive operations</li>
                        <li>• Batch processing for bulk operations</li>
                        <li>• Connection pooling and reuse</li>
                        <li>• Memory-efficient data structures</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection