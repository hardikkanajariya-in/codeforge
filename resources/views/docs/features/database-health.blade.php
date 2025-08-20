@extends('codeforge-studio::layout.docs')

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
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Database Health Dashboard</h1>
                    <p class="text-xl text-gray-600">Real-time database health monitoring with performance metrics and
                        analytics</p>
                </div>
            </div>
        </div>

        <!-- Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
            <p class="text-gray-600 mb-6">
                The Database Health Dashboard provides comprehensive monitoring of your database performance through
                real-time metrics collection,
                interactive widgets, and automated health assessments. Monitor connection status, query performance, and
                system metrics from a centralized dashboard.
            </p>
        </div>

        <!-- Current Implementation -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Current Implementation</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Database Health Metrics Widget</h3>
                    <ul class="space-y-2 text-blue-800 text-sm">
                        <li><strong>Connection Status:</strong> Real-time connection health monitoring</li>
                        <li><strong>Response Time Tracking:</strong> Latest connection response time metrics</li>
                        <li><strong>24-Hour Query Statistics:</strong> Total queries executed in the last 24 hours</li>
                        <li><strong>Average Response Time:</strong> Calculated from recent query performance logs</li>
                        <li><strong>Slow Query Detection:</strong> Count of queries exceeding 1000ms execution time</li>
                        <li><strong>Error Rate Monitoring:</strong> Failed query detection and counting</li>
                        <li><strong>Database Size Tracking:</strong> Current database size from health metrics</li>
                    </ul>
                </div>

                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-green-900 mb-4">Query Performance Chart Widget</h3>
                    <ul class="space-y-2 text-green-800 text-sm">
                        <li><strong>Performance Visualization:</strong> Interactive charts showing query performance trends
                        </li>
                        <li><strong>Historical Data:</strong> Query performance data over time</li>
                        <li><strong>Execution Time Analysis:</strong> Visual representation of query execution patterns</li>
                        <li><strong>Performance Trends:</strong> Identify performance improvements or degradations</li>
                    </ul>
                </div>

                <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                    <h3 class="text-lg font-semibold text-purple-900 mb-4">Database Health Widget</h3>
                    <ul class="space-y-2 text-purple-800 text-sm">
                        <li><strong>Overall Health Status:</strong> Comprehensive health summary display</li>
                        <li><strong>Multi-metric Assessment:</strong> Combined view of all health indicators</li>
                        <li><strong>Status Indicators:</strong> Visual health status representations</li>
                        <li><strong>Quick Health Overview:</strong> At-a-glance database health assessment</li>
                    </ul>
                </div>

                <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                    <h3 class="text-lg font-semibold text-orange-900 mb-4">Database Stats Widget</h3>
                    <ul class="space-y-2 text-orange-800 text-sm">
                        <li><strong>General Database Statistics:</strong> Overall database performance metrics</li>
                        <li><strong>System Resource Usage:</strong> Database resource consumption tracking</li>
                        <li><strong>Performance Counters:</strong> Key performance indicators and statistics</li>
                        <li><strong>Historical Comparisons:</strong> Trend analysis and performance comparisons</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Data Models -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Data Collection Models</h2>
            <p class="text-gray-600 mb-6">The health monitoring system uses dedicated models to store and track metrics:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">DatabaseHealthMetric Model</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Stores connection-specific health metrics</li>
                        <li>• Records response times and connection status</li>
                        <li>• Tracks database size and performance indicators</li>
                        <li>• Timestamp-based metric recording</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">QueryPerformanceLog Model</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Logs individual query performance metrics</li>
                        <li>• Records execution times and query status</li>
                        <li>• Tracks successful and failed query attempts</li>
                        <li>• Connection-specific performance tracking</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Dashboard Access -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Access</h2>
            <p class="text-gray-600 mb-6">Access the Database Health Dashboard through your Filament admin panel:</p>

            <div class="bg-white p-4 rounded-lg border">
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Navigate to your Filament admin panel</li>
                    <li>Look for the <strong>"Database Health"</strong> navigation group</li>
                    <li>Click on <strong>"Database Health Dashboard"</strong></li>
                    <li>View real-time metrics and performance charts</li>
                    <li>Use the manual refresh action to update metrics on demand</li>
                </ol>
            </div>
        </div>

        <!-- Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Configuration</h2>
            <p class="text-gray-600 mb-6">Enable database health monitoring in your plugin configuration:</p>

            <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>// In your AdminPanelProvider.php
    CodeForgeStudioPlugin::make()
        ->enableHealthMonitoring(true)  // Enable health monitoring features
        // ... other configuration</code></pre>

            <p class="text-gray-600 mt-4 mb-6">Configuration file settings for health monitoring:</p>

            <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>'features' => [
        'health_monitoring' => true,
    ],

    'health_monitoring' => [
        'enabled' => true,
        'metrics_retention_days' => 30,
        'performance_log_retention_days' => 7,
        'slow_query_threshold' => 1000, // milliseconds
    ],</code></pre>
        </div>

        <!-- Data Sources -->
        <div class="bg-gray-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Sources</h2>
            <p class="text-gray-600 mb-6">The health monitoring system collects data from:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">DatabaseHealthMetric Table</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• connection (string): Database connection name</li>
                        <li>• metric_name (string): Type of metric (response_time, database_size)</li>
                        <li>• value (decimal): Metric value</li>
                        <li>• recorded_at (timestamp): When metric was recorded</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">QueryPerformanceLog Table</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• connection (string): Database connection name</li>
                        <li>• execution_time (integer): Query execution time in milliseconds</li>
                        <li>• status (string): Query status (success/error)</li>
                        <li>• executed_at (timestamp): When query was executed</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Widgets Overview -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Widgets</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg border shadow-sm">
                    <h4 class="font-semibold text-gray-900 text-lg mb-2">DatabaseHealthMetricsWidget</h4>
                    <p class="text-gray-600 mb-3">Primary metrics display showing connection status, response times, query
                        counts, and error rates.</p>
                    <p class="text-sm text-gray-500">Displays 7 key performance indicators in a stats overview format.</p>
                </div>
                <div class="bg-white p-6 rounded-lg border shadow-sm">
                    <h4 class="font-semibold text-gray-900 text-lg mb-2">QueryPerformanceChart</h4>
                    <p class="text-gray-600 mb-3">Interactive performance visualization showing query execution trends over
                        time.</p>
                    <p class="text-sm text-gray-500">Chart-based widget for visualizing performance patterns and trends.</p>
                </div>
                <div class="bg-white p-6 rounded-lg border shadow-sm">
                    <h4 class="font-semibold text-gray-900 text-lg mb-2">DatabaseHealthWidget</h4>
                    <p class="text-gray-600 mb-3">Overall health status summary with combined health indicators.</p>
                    <p class="text-sm text-gray-500">Provides at-a-glance health assessment and status overview.</p>
                </div>
                <div class="bg-white p-6 rounded-lg border shadow-sm">
                    <h4 class="font-semibold text-gray-900 text-lg mb-2">DatabaseStatsWidget</h4>
                    <p class="text-gray-600 mb-3">General database statistics and performance counters.</p>
                    <p class="text-sm text-gray-500">Additional statistical information and performance metrics.</p>
                </div>
            </div>
        </div>
    </div>
@endsection