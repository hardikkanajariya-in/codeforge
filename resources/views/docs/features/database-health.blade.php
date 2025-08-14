@extends('codeforge-database-studio::layout.docs')

@section('title', 'Database Health - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='flex items-center'>
        <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='flex items-center'>
        <span class='text-gray-500'>Features</span>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Database Health</li>
@endsection

@section('navigation')
    @include('codeforge-database-studio::docs.partials.navigation')
@endsection


@section('title', 'Database Health Monitoring')

    @section('content')
        <div class="animate-fade-in-up">
            <!-- Header -->
            <div class="mb-12">
                <div class="flex items-center space-x-4 mb-6">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Database Health Monitoring</h1>
                        <p class="text-xl text-gray-600">Real-time database health monitoring with performance metrics and
                            intelligent alerting</p>
                    </div>
                </div>
            </div>

            <!-- Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
                <p class="text-gray-600 mb-6">The Health Monitoring system continuously monitors your database performance,
                    connection health, and system metrics to provide real-time insights and alerts.</p>
            </div>

            <!-- Key Features -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Key Features</h2>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Connection Health Testing</h3>
                        <ul class="space-y-2 text-gray-700">
                            <li><strong>Real-time Connection Testing:</strong> Continuous monitoring of database connections
                            </li>
                            <li><strong>Connection Pool Management:</strong> Monitoring of connection pool utilization</li>
                            <li><strong>Timeout Detection:</strong> Early warning for connection timeouts</li>
                            <li><strong>Multi-Database Support:</strong> Monitor multiple database connections simultaneously
                            </li>
                        </ul>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Performance Metrics Collection</h3>
                        <ul class="space-y-2 text-blue-800">
                            <li><strong>Query Performance Tracking:</strong> Monitor slow queries and execution times</li>
                            <li><strong>Resource Usage Monitoring:</strong> CPU, memory, and I/O utilization tracking</li>
                            <li><strong>Throughput Analysis:</strong> Database transaction and query throughput metrics</li>
                            <li><strong>Lock Monitoring:</strong> Deadlock detection and lock wait analysis</li>
                        </ul>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h3 class="text-lg font-semibold text-green-900 mb-4">Health Scoring System</h3>
                        <ul class="space-y-2 text-green-800">
                            <li><strong>Comprehensive Health Score:</strong> Overall database health rating (0-100)</li>
                            <li><strong>Component-Level Scoring:</strong> Individual scores for connections, performance,
                                storage</li>
                            <li><strong>Trend Analysis:</strong> Historical health score tracking and trend identification</li>
                            <li><strong>Threshold-Based Alerts:</strong> Configurable health score thresholds for notifications
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Dashboard Features -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Health Metrics Dashboard</h2>
                <p class="text-gray-600 mb-6">Access real-time health metrics through the integrated dashboard:</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="bg-white p-4 rounded-lg border text-center">
                        <h4 class="font-semibold text-gray-900">Real-time Health Score</h4>
                    </div>
                    <div class="bg-white p-4 rounded-lg border text-center">
                        <h4 class="font-semibold text-gray-900">Connection Status</h4>
                    </div>
                    <div class="bg-white p-4 rounded-lg border text-center">
                        <h4 class="font-semibold text-gray-900">Performance Trends</h4>
                    </div>
                    <div class="bg-white p-4 rounded-lg border text-center">
                        <h4 class="font-semibold text-gray-900">Alert Notifications</h4>
                    </div>
                    <div class="bg-white p-4 rounded-lg border text-center">
                        <h4 class="font-semibold text-gray-900">Historical Analysis</h4>
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Configuration</h2>
                <p class="text-gray-600 mb-6">Enable health monitoring in your configuration:</p>

                <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>'features' => [
            'health_monitoring' => true,
        ],

        'health_monitoring' => [
            'check_interval' => 60, // seconds
            'alert_thresholds' => [
                'connection_timeout' => 5,
                'slow_query_threshold' => 2000, // milliseconds
                'health_score_warning' => 70,
                'health_score_critical' => 50,
            ],
            'retention_days' => 30,
        ],</code></pre>
            </div>

            <!-- Usage Example -->
            <div class="bg-gray-50 p-8 rounded-xl mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Usage Example</h2>
                <p class="text-gray-600 mb-6">Access health monitoring programmatically:</p>

                <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;

        $healthService = app(DatabaseHealthService::class);

        // Get current health score
        $healthScore = $healthService->getOverallHealthScore();

        // Perform comprehensive health check
        $healthReport = $healthService->performHealthCheck();

        // Get connection status
        $connectionStatus = $healthService->checkConnectionHealth('mysql');</code></pre>
            </div>

            <!-- Benefits -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Benefits</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h4 class="font-semibold text-green-800 text-lg mb-2">Proactive Monitoring</h4>
                        <p class="text-green-700">Detect issues before they impact your application with real-time monitoring
                            and intelligent alerts.</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h4 class="font-semibold text-blue-800 text-lg mb-2">Performance Insights</h4>
                        <p class="text-blue-700">Gain deep insights into database performance patterns and optimization
                            opportunities.</p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                        <h4 class="font-semibold text-purple-800 text-lg mb-2">Historical Analysis</h4>
                        <p class="text-purple-700">Track performance trends over time and identify long-term optimization
                            strategies.</p>
                    </div>
                    <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                        <h4 class="font-semibold text-orange-800 text-lg mb-2">Automated Alerts</h4>
                        <p class="text-orange-700">Receive intelligent notifications for critical issues with configurable
                            thresholds.</p>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    <ul>
        <li><strong>Query Performance Tracking:</strong> Monitor slow queries and execution times</li>
        <li><strong>Resource Usage Monitoring:</strong> CPU, memory, and I/O utilization tracking</li>
        <li><strong>Throughput Analysis:</strong> Database transaction and query throughput metrics</li>
        <li><strong>Lock Monitoring:</strong> Deadlock detection and lock wait analysis</li>
    </ul>
    </div>

    <h3>Health Scoring System</h3>
    <div class="bg-green-50 p-4 rounded-md">
        <ul>
            <li><strong>Comprehensive Health Score:</strong> Overall database health rating (0-100)</li>
            <li><strong>Component-Level Scoring:</strong> Individual scores for connections, performance, storage</li>
            <li><strong>Trend Analysis:</strong> Historical health score tracking and trend identification</li>
            <li><strong>Threshold-Based Alerts:</strong> Configurable health score thresholds for notifications</li>
        </ul>
    </div>

    <h2>Health Metrics Dashboard</h2>
    <p>Access real-time health metrics through the integrated dashboard:</p>

    <div class="bg-gray-100 p-4 rounded-md">
        <strong>Dashboard Features:</strong>
        <ul>
            <li>Real-time health score display</li>
            <li>Connection status indicators</li>
            <li>Performance trend charts</li>
            <li>Alert notifications and warnings</li>
            <li>Historical data analysis</li>
        </ul>
    </div>

    <h2>Automated Health Checks</h2>
    <p>The system performs automated health checks including:</p>

    <h3>Connection Monitoring</h3>
    <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>✓ Database connection availability
    ✓ Connection response time analysis
    ✓ Connection pool utilization
    ✓ Authentication and permission validation</code></pre>

    <h3>Performance Analysis</h3>
    <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>✓ Query execution time monitoring
    ✓ Resource usage assessment
    ✓ Index utilization analysis
    ✓ Table fragmentation detection</code></pre>

    <h2>Alert System</h2>
    <p>Configure intelligent alerts for proactive monitoring:</p>

    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <ul>
            <li><strong>Threshold-Based Alerts:</strong> Set custom thresholds for performance metrics</li>
            <li><strong>Real-time Notifications:</strong> Immediate alerts for critical issues</li>
            <li><strong>Escalation Policies:</strong> Configurable alert escalation based on severity</li>
            <li><strong>Integration Support:</strong> Connect with external monitoring systems</li>
        </ul>
    </div>

    <h2>Configuration</h2>
    <p>Enable health monitoring in your configuration:</p>

    <pre class="bg-gray-800 text-white p-4 rounded-md"><code>'features' => [
        'health_monitoring' => true,
    ],

    'health_monitoring' => [
        'check_interval' => 60, // seconds
        'alert_thresholds' => [
            'connection_timeout' => 5,
            'slow_query_threshold' => 2000, // milliseconds
            'health_score_warning' => 70,
            'health_score_critical' => 50,
        ],
        'retention_days' => 30,
    ],</code></pre>

    <h2>Usage Example</h2>
    <p>Access health monitoring programmatically:</p>

    <pre class="bg-gray-800 text-white p-4 rounded-md"><code>use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;

    $healthService = app(DatabaseHealthService::class);

    // Get current health score
    $healthScore = $healthService->getOverallHealthScore();

    // Perform comprehensive health check
    $healthReport = $healthService->performHealthCheck();

    // Get connection status
    $connectionStatus = $healthService->checkConnectionHealth('mysql');</code></pre>

    <h2>Benefits</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <div class="bg-green-50 p-4 rounded-md">
            <h4 class="font-semibold text-green-800">Proactive Monitoring</h4>
            <p>Detect issues before they impact your application</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-md">
            <h4 class="font-semibold text-blue-800">Performance Insights</h4>
            <p>Gain deep insights into database performance patterns</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-md">
            <h4 class="font-semibold text-purple-800">Historical Analysis</h4>
            <p>Track performance trends and identify optimization opportunities</p>
        </div>
        <div class="bg-orange-50 p-4 rounded-md">
            <h4 class="font-semibold text-orange-800">Automated Alerts</h4>
            <p>Receive intelligent notifications for critical issues</p>
        </div>
    </div>
    </div>
@endsection