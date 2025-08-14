@extends('codeforge-database-studio::layout.docs')

@section('title', 'Migration Management - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Migration Management</li>
@endsection

@section('navigation')
    @include('codeforge-database-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class='prose max-w-none'>
        <h1>Migration Management</h1>

        <p>CodeForge Database Studio provides comprehensive migration tracking and monitoring capabilities for Laravel
            database migrations.</p>

        <h2>Overview</h2>
        <p>The Migration Management system provides detailed tracking, performance monitoring, and history management for
            all database migration operations.</p>

        <h2>Key Features</h2>

        <h3>Detailed Migration Logging</h3>
        <div class='bg-gray-50 p-4 rounded-md'>
            <ul>
                <li><strong>Execution Logging:</strong> Detailed logging of all migration operations with timestamps</li>
                <li><strong>Performance Metrics:</strong> Execution time tracking and performance benchmarking</li>
                <li><strong>User Attribution:</strong> Track which users execute migrations in team environments</li>
                <li><strong>Error Reporting:</strong> Comprehensive error logging with stack traces and context</li>
            </ul>
        </div>

        <h3>Migration History Management</h3>
        <div class='bg-blue-50 p-4 rounded-md'>
            <ul>
                <li><strong>Complete History:</strong> Persistent storage of all migration operations</li>
                <li><strong>Rollback Points:</strong> Identification and management of rollback points</li>
                <li><strong>Synchronization:</strong> Sync migration history across different systems</li>
                <li><strong>Data Integrity:</strong> Continuous validation of migration history consistency</li>
            </ul>
        </div>

        <h3>Performance Analysis</h3>
        <div class='bg-green-50 p-4 rounded-md'>
            <ul>
                <li><strong>Execution Time Tracking:</strong> Detailed analysis of migration execution performance</li>
                <li><strong>Bottleneck Identification:</strong> Detection of slow migrations and performance issues</li>
                <li><strong>Resource Monitoring:</strong> Track CPU, memory, and I/O usage during migrations</li>
                <li><strong>Optimization Recommendations:</strong> Suggestions for migration performance improvements</li>
            </ul>
        </div>

        <h2>Migration Tracking Capabilities</h2>
        <p>Comprehensive tracking of all migration activities:</p>

        <h3>Execution Monitoring</h3>
        <pre class='bg-gray-800 text-green-400 p-4 rounded-md'><code>✓ Real-time migration execution tracking
    ✓ Performance metrics collection
    ✓ Error detection and reporting
    ✓ User activity attribution
    ✓ Environment-specific logging</code></pre>

        <h3>Batch Management</h3>
        <pre class='bg-gray-800 text-green-400 p-4 rounded-md'><code>✓ Migration batch identification
    ✓ Rollback point management
    ✓ Cross-system synchronization
    ✓ Orphaned entry cleanup
    ✓ Consistency validation</code></pre>

        <h2>Configuration</h2>
        <p>Configure migration tracking settings:</p>

        <pre class='bg-gray-800 text-white p-4 rounded-md'><code>'features' => [
        'migration_management' => true,
    ],

    'migration_tracking' => [
        'enabled' => true,
        'track_performance' => true,
        'track_user_attribution' => true,
        'error_reporting' => [
            'detailed_logging' => true,
            'stack_traces' => true,
            'system_context' => true,
        ],
        'performance_monitoring' => [
            'execution_time' => true,
            'resource_usage' => true,
            'bottleneck_detection' => true,
        ],
        'cleanup' => [
            'auto_cleanup' => true,
            'retention_days' => 90,
        ],
    ],</code></pre>

        <h2>Benefits</h2>
        <div class='grid grid-cols-1 md:grid-cols-2 gap-4 mt-6'>
            <div class='bg-green-50 p-4 rounded-md'>
                <h4 class='font-semibold text-green-800'>Complete Visibility</h4>
                <p>Full visibility into all migration operations and performance</p>
            </div>
            <div class='bg-blue-50 p-4 rounded-md'>
                <h4 class='font-semibold text-blue-800'>Performance Optimization</h4>
                <p>Identify and resolve migration performance bottlenecks</p>
            </div>
            <div class='bg-purple-50 p-4 rounded-md'>
                <h4 class='font-semibold text-purple-800'>Error Prevention</h4>
                <p>Proactive error detection and prevention strategies</p>
            </div>
            <div class='bg-orange-50 p-4 rounded-md'>
                <h4 class='font-semibold text-orange-800'>Team Collaboration</h4>
                <p>Enhanced team collaboration with user attribution and history</p>
            </div>
        </div>
    </div>
@endsection