@extends('codeforge-studio::layout.docs')

@section('title', 'Performance Optimization - CodeForge Database Studio')
@section('description', 'Learn how to optimize CodeForge Database Studio performance for large databases and high-traffic applications.')

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
    <li class="text-primary-600 font-medium">Performance</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Performance Optimization</h1>
                    <p class="text-xl text-gray-600">Optimize CodeForge Database Studio for maximum performance and
                        scalability</p>
                </div>
            </div>
        </div>

        <!-- Performance Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Architecture</h2>
            <p class="text-gray-600 mb-6">
                CodeForge Database Studio implements comprehensive performance monitoring and optimization features through
                the DatabaseHealthService, intelligent caching strategies, and optimized data collection algorithms designed
                for minimal overhead.</p>

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
                        <h3 class="font-semibold text-gray-900">Real-time Monitoring</h3>
                    </div>
                    <p class="text-sm text-gray-600">DatabaseHealthService provides 24-hour rolling statistics with
                        minute-level granularity and intelligent sampling.</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Query Optimization</h3>
                    </div>
                    <p class="text-sm text-gray-600">Slow query detection with configurable thresholds, execution pattern
                        analysis, and optimization recommendations.</p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Intelligent Caching</h3>
                    </div>
                    <p class="text-sm text-gray-600">Memory-efficient caching strategies with automated cache invalidation
                        and optimized data retention policies.</p>
                </div>
            </div>
        </div>

        <!-- Performance Monitoring -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Monitoring System</h2>
            <p class="text-gray-600 mb-6">The DatabaseHealthService provides comprehensive performance monitoring with
                real-time metrics and historical analysis.</p>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Core Monitoring Features</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Connection Monitoring</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Real-time connectivity testing</li>
                                <li>• Response time measurement</li>
                                <li>• Connection pool monitoring</li>
                                <li>• Multi-connection health tracking</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Query Performance</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Execution time tracking</li>
                                <li>• Slow query identification</li>
                                <li>• Query pattern analysis</li>
                                <li>• Performance regression detection</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Monitoring Configuration</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>// config/codeforge-database-studio.php
    'health_monitoring' => [
        'enabled' => true,
        'check_interval' => 300, // 5 minutes
        'slow_query_threshold' => 1000, // milliseconds
        'connection_timeout' => 5, // seconds
        'metrics_retention_days' => 30,
        'enable_real_time_alerts' => true,
    ],

    'query_logging' => [
        'slow_query_threshold' => 1000,
        'log_all_queries' => false,
        'max_log_entries' => 10000,
        'cleanup_older_than_days' => 30,
        'skip_patterns' => [
            'show tables',
            'show columns',
            'information_schema',
        ],
    ],</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optimization Strategies -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Optimization Strategies</h2>

            <div class="space-y-6">
                <!-- Database Level Optimizations -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Database Level Optimizations</h3>
                    <p class="text-gray-600 mb-3">Optimize database configuration for CodeForge operations:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code># MySQL Configuration (my.cnf)
    [mysqld]
    # Optimize for CodeForge monitoring queries
    innodb_buffer_pool_size = 2G
    query_cache_size = 64M
    query_cache_type = 1
    slow_query_log = 1
    long_query_time = 1
    max_connections = 200

    # Performance Schema for detailed monitoring
    performance_schema = ON
    performance_schema_events_statements_history_size = 20
    performance_schema_events_statements_history_long_size = 1000</code></pre>
                    </div>
                </div>

                <!-- Application Level Optimizations -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Application Level Optimizations</h3>
                    <p class="text-gray-600 mb-3">Laravel configuration optimizations for CodeForge:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code># .env optimizations
    DB_CONNECTION=mysql
    DB_POOL_MIN=5
    DB_POOL_MAX=50
    CACHE_DRIVER=redis
    SESSION_DRIVER=redis
    QUEUE_CONNECTION=redis

    # CodeForge specific optimizations
    CODEFORGE_QUERY_LOGGING=true
    CODEFORGE_HEALTH_MONITORING=true
    CODEFORGE_CACHE_METRICS=true
    CODEFORGE_BACKGROUND_PROCESSING=true</code></pre>
                    </div>
                </div>

                <!-- Caching Optimizations -->
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Intelligent Caching</h3>
                    <p class="text-gray-600 mb-3">CodeForge implements multi-layer caching for optimal performance:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>// Programmatic cache configuration
    $healthService = app(DatabaseHealthService::class);

    // Configure cache strategies
    $healthService->configureCaching([
        'metrics_cache_ttl' => 300, // 5 minutes
        'connection_status_ttl' => 60, // 1 minute
        'performance_data_ttl' => 900, // 15 minutes
        'enable_background_refresh' => true,
    ]);

    // Manual cache management
    $healthService->clearCache(); // Clear all caches
    $healthService->refreshMetrics(); // Force refresh</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Monitoring -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Measurement & Analysis</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Metrics</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code>// Get comprehensive performance metrics
    $healthService = app(DatabaseHealthService::class);

    // Connection performance
    $connectionMetrics = $healthService->getConnectionMetrics([
        'response_time_avg',
        'response_time_max', 
        'connection_pool_usage',
        'active_connections',
        'failed_connections'
    ]);

    // Query performance analysis
    $queryMetrics = $healthService->getQueryPerformanceMetrics([
        'slow_queries_count',
        'avg_execution_time',
        'query_throughput',
        'most_expensive_queries'
    ]);

    // Storage and resource metrics
    $resourceMetrics = $healthService->getResourceMetrics([
        'database_size',
        'table_sizes',
        'index_efficiency',
        'storage_growth_rate'
    ]);</code></pre>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Analysis Tools</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Artisan Commands</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div><code>codeforge:health:check</code> - Run health diagnostics</div>
                                <div><code>codeforge:performance:analyze</code> - Analyze performance metrics</div>
                                <div><code>codeforge:cache:optimize</code> - Optimize cache configuration</div>
                                <div><code>codeforge:metrics:export</code> - Export performance data</div>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Dashboard Widgets</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div>• Real-time performance overview</div>
                                <div>• Query execution timeline</div>
                                <div>• Connection status indicators</div>
                                <div>• Historical trend analysis</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Large Scale Optimizations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Large-Scale Environment Optimizations</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">High-Traffic Configurations</h3>
                    <div class="bg-gray-800 text-white p-4 rounded-lg">
                        <pre class="text-sm overflow-x-auto"><code># High-traffic production configuration
    'performance_mode' => 'production', // Optimize for production
    'batch_size' => 1000, // Larger batch processing
    'concurrent_connections' => 10, // Parallel monitoring
    'cache_strategy' => 'aggressive', // More aggressive caching
    'background_processing' => true, // Use queues for heavy operations

    # Memory optimizations
    'memory_limit' => '512M',
    'enable_gc_optimization' => true,
    'use_streaming_responses' => true,

    # Database optimizations
    'connection_pooling' => true,
    'read_write_splitting' => true,
    'query_result_caching' => true,</code></pre>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Horizontal Scaling</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Load Balancing</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Distribute monitoring across multiple servers</li>
                                <li>• Configure read replicas for metrics collection</li>
                                <li>• Use separate database for CodeForge data</li>
                                <li>• Implement connection pooling strategies</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Queue Configuration</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Process health checks in background queues</li>
                                <li>• Use dedicated queue workers for metrics</li>
                                <li>• Implement queue prioritization</li>
                                <li>• Configure queue retry strategies</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Best Practices -->
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-8 rounded-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Performance Best Practices</h2>
            <p class="text-gray-600 mb-6">Guidelines for maintaining optimal performance in production environments:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Monitoring Best Practices</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Sampling Strategy:</strong> Use intelligent sampling to reduce overhead</li>
                        <li>• <strong>Cache Warming:</strong> Pre-warm caches during deployment</li>
                        <li>• <strong>Threshold Tuning:</strong> Adjust thresholds based on workload</li>
                        <li>• <strong>Resource Limits:</strong> Set appropriate memory and time limits</li>
                        <li>• <strong>Background Processing:</strong> Use queues for heavy operations</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Optimization Guidelines</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Database Indexing:</strong> Optimize queries with proper indexes</li>
                        <li>• <strong>Connection Management:</strong> Use connection pooling</li>
                        <li>• <strong>Memory Management:</strong> Monitor and optimize memory usage</li>
                        <li>• <strong>Caching Strategy:</strong> Implement multi-layer caching</li>
                        <li>• <strong>Regular Maintenance:</strong> Schedule maintenance windows</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 p-4 bg-white rounded-lg border border-orange-200">
                <h4 class="font-semibold text-gray-900 mb-2">⚡ Performance Tips</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Monitor CodeForge's own performance impact using built-in metrics</li>
                    <li>• Use background queues for data generation and analysis tasks</li>
                    <li>• Configure appropriate cache TTL values based on your use case</li>
                    <li>• Regularly review and optimize slow query patterns</li>
                    <li>• Consider using read replicas for monitoring operations</li>
                </ul>
            </div>
        </div>
        can significantly improve response times and resource usage.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Caching</h3>
                <p class="text-blue-700 text-sm mb-4">Intelligent caching reduces database queries and improves response
                    times.</p>
                <div class="space-y-2">
                    <div class="text-sm text-blue-600">• Schema Caching</div>
                    <div class="text-sm text-blue-600">• Query Result Caching</div>
                    <div class="text-sm text-blue-600">• Metadata Caching</div>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-900 mb-3">Background Processing</h3>
                <p class="text-green-700 text-sm mb-4">Heavy operations run in the background for better user
                    experience.</p>
                <div class="space-y-2">
                    <div class="text-sm text-green-600">• Queue Processing</div>
                    <div class="text-sm text-green-600">• Batch Operations</div>
                    <div class="text-sm text-green-600">• Async Tasks</div>
                </div>
            </div>

            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-purple-900 mb-3">Resource Management</h3>
                <p class="text-purple-700 text-sm mb-4">Efficient memory and CPU usage for large datasets.</p>
                <div class="space-y-2">
                    <div class="text-sm text-purple-600">• Memory Optimization</div>
                    <div class="text-sm text-purple-600">• Lazy Loading</div>
                    <div class="text-sm text-purple-600">• Connection Pooling</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Tips -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Configuration Optimizations</h2>

        <div class="space-y-6">
            <div class="border-l-4 border-blue-500 pl-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Cache Configuration</h3>
                <p class="text-gray-600 mb-4">Configure caching for optimal performance:</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        // config/codeforge-database-studio.php<br>
                        'cache' => [<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'enabled' => true,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'ttl' => 3600, // 1 hour<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'driver' => 'redis', // or 'file'<br>
                        ],
                    </div>
                </div>
            </div>

            <div class="border-l-4 border-green-500 pl-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Database Connection</h3>
                <p class="text-gray-600 mb-4">Optimize database connections:</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        'database' => [<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'timeout' => 30,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'max_connections' => 10,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'chunk_size' => 1000,<br>
                        ],
                    </div>
                </div>
            </div>

            <div class="border-l-4 border-purple-500 pl-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Background Processing</h3>
                <p class="text-gray-600 mb-4">Enable queue processing for heavy operations:</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        'queue' => [<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'enabled' => true,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'connection' => 'redis',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'queue' => 'codeforge',<br>
                        ],
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Monitoring -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Monitoring</h2>
        <p class="text-gray-600 mb-6">
            Monitor your CodeForge Database Studio performance with built-in metrics and external tools.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Built-in Metrics</h3>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Query execution times
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Memory usage tracking
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Cache hit rates
                    </li>
                </ul>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">External Tools</h3>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Laravel Telescope
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        New Relic
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Application Performance Monitoring
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="bg-gradient-to-r from-primary-50 to-indigo-50 border border-primary-200 rounded-xl p-8">
        <h2 class="text-2xl font-bold text-primary-900 mb-4">Next Steps</h2>
        <p class="text-primary-700 mb-6">Continue optimizing your CodeForge Database Studio implementation:</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('codeforge.docs.advanced.deployment') }}"
                class="flex items-center p-4 bg-white rounded-lg border border-primary-200 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-900">Deployment Guide</h3>
                    <p class="text-primary-600 text-sm">Deploy to production</p>
                </div>
            </a>
            <a href="{{ route('codeforge.docs.troubleshooting') }}"
                class="flex items-center p-4 bg-white rounded-lg border border-primary-200 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-900">Troubleshooting</h3>
                    <p class="text-primary-600 text-sm">Common issues and solutions</p>
                </div>
            </a>
        </div>
    </div>
    </div>
@endsection