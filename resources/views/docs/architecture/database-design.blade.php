@extends('codeforge-studio::layout.docs')

@section('title', 'Database Design - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Database Design</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Database Design Architecture</h1>
            <p class="text-lg text-gray-600">CodeForge Database Studio implements a comprehensive database infrastructure
                designed for monitoring, tracking, and managing database operations with performance and scalability in
                mind.</p>
        </div>

        <!-- Database Schema Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Schema Overview</h2>
            <p class="text-gray-600 mb-6">The plugin uses 12+ specialized tables to track database health, migrations,
                queries, and operations. Each table is designed with specific indexing and relationship strategies for
                optimal performance.</p>

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
                        <h3 class="font-semibold text-gray-900">Core Tables</h3>
                    </div>
                    <p class="text-sm text-gray-600">Primary infrastructure tables for logging, health metrics, and
                        migration tracking with optimized indexes.</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Performance</h3>
                    </div>
                    <p class="text-sm text-gray-600">Query performance tracking, slow query detection, and execution time
                        monitoring with historical data.</p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Audit Trail</h3>
                    </div>
                    <p class="text-sm text-gray-600">Complete audit trails for schema changes, data operations, and
                        administrative actions with user tracking.</p>
                </div>
            </div>
        </div>

        <!-- Core Database Tables -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Core Database Tables</h2>

            <div class="space-y-6">
                <!-- Database Manager Logs -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">database_manager_logs</h3>
                    <p class="text-gray-600 mb-3">Central logging table for all database operations and system events:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>Schema::create('database_manager_logs', function (Blueprint $table) {
        $table->id();
        $table->string('level');                    // info, warning, error, debug
        $table->string('message');                  // Log message
        $table->json('context')->nullable();        // Additional context data
        $table->string('connection')->nullable();   // Database connection name
        $table->timestamps();

        // Optimized indexes for frequent queries
        $table->index(['level', 'created_at']);
        $table->index(['connection', 'created_at']);
        $table->index('created_at');
    });</code></pre>
                    </div>
                </div>

                <!-- Health Metrics -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">health_metrics</h3>
                    <p class="text-gray-600 mb-3">Real-time database health monitoring and performance metrics:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>Schema::create('health_metrics', function (Blueprint $table) {
        $table->id();
        $table->string('connection');               // Database connection
        $table->json('metrics');                    // Health metrics data
        $table->decimal('health_score', 5, 2);     // Overall health score (0-100)
        $table->timestamp('checked_at');           // When health was checked
        $table->timestamps();

        // Indexes for health monitoring queries
        $table->index(['connection', 'checked_at']);
        $table->index(['health_score', 'checked_at']);
    });</code></pre>
                    </div>
                </div>

                <!-- Query Performance -->
                <div class="border-l-4 border-orange-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">query_performance_logs</h3>
                    <p class="text-gray-600 mb-3">Query execution tracking and performance analysis:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>Schema::create('query_performance_logs', function (Blueprint $table) {
        $table->id();
        $table->text('sql');                        // Executed SQL query
        $table->json('bindings')->nullable();       // Query bindings
        $table->decimal('execution_time', 8, 2);    // Execution time in ms
        $table->string('connection');               // Database connection
        $table->string('hash', 64);                // Query hash for grouping
        $table->timestamps();

        // Performance analysis indexes
        $table->index(['hash', 'execution_time']);
        $table->index(['execution_time', 'created_at']);
        $table->index(['connection', 'created_at']);
    });</code></pre>
                    </div>
                </div>

                <!-- Migration Histories -->
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">migration_histories</h3>
                    <p class="text-gray-600 mb-3">Detailed migration execution tracking and rollback capabilities:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>Schema::create('migration_histories', function (Blueprint $table) {
        $table->id();
        $table->string('migration');               // Migration file name
        $table->string('batch');                   // Migration batch number
        $table->enum('action', ['up', 'down']);    // Migration direction
        $table->json('changes')->nullable();       // Schema changes made
        $table->string('user_id')->nullable();     // User who ran migration
        $table->timestamp('executed_at');          // Execution timestamp
        $table->timestamps();

        // Migration tracking indexes
        $table->index(['migration', 'action']);
        $table->index(['batch', 'executed_at']);
        $table->index('executed_at');
    });</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Database Features -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Advanced Database Features</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Schema Versions -->
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Schema Versioning</h3>
                    <div class="bg-white p-4 rounded border">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>Schema::create('schema_versions', function (Blueprint $table) {
        $table->id();
        $table->string('table_name');
        $table->json('schema_definition');
        $table->string('version_hash', 64);
        $table->timestamps();

        $table->index(['table_name', 'created_at']);
        $table->unique(['table_name', 'version_hash']);
    });</code></pre>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">Track schema changes over time with version control capabilities.
                    </p>
                </div>

                <!-- Data Seeds Tracking -->
                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Data Seeds Tracking</h3>
                    <div class="bg-white p-4 rounded border">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>Schema::create('data_seeds', function (Blueprint $table) {
        $table->id();
        $table->string('seeder_class');
        $table->string('table_name');
        $table->integer('records_inserted');
        $table->json('seed_config')->nullable();
        $table->timestamps();

        $table->index(['table_name', 'created_at']);
        $table->index('seeder_class');
    });</code></pre>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">Monitor data seeding operations and track insertion counts.</p>
                </div>

                <!-- Query Cache -->
                <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Query Cache</h3>
                    <div class="bg-white p-4 rounded border">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>Schema::create('query_cache', function (Blueprint $table) {
        $table->id();
        $table->string('cache_key', 64)->unique();
        $table->json('result_data');
        $table->timestamp('expires_at');
        $table->timestamps();

        $table->index('expires_at');
        $table->index(['cache_key', 'expires_at']);
    });</code></pre>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">Intelligent query result caching for improved performance.</p>
                </div>

                <!-- Connection Monitoring -->
                <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Connection Monitoring</h3>
                    <div class="bg-white p-4 rounded border">
                        <pre class="text-sm text-gray-700 overflow-x-auto"><code>Schema::create('connection_stats', function (Blueprint $table) {
        $table->id();
        $table->string('connection_name');
        $table->integer('active_connections');
        $table->integer('max_connections');
        $table->decimal('cpu_usage', 5, 2);
        $table->bigInteger('memory_usage');
        $table->timestamps();

        $table->index(['connection_name', 'created_at']);
    });</code></pre>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">Real-time database connection and resource monitoring.</p>
                </div>
            </div>
        </div>

        <!-- Database Design Patterns -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Database Design Patterns</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio follows established database design patterns for
                reliability, performance, and maintainability:</p>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-lg border border-blue-200">
                        <h4 class="font-semibold text-gray-900 mb-3">Indexing Strategy</h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• <strong>Composite Indexes:</strong> Multi-column indexes for common query patterns</li>
                            <li>• <strong>Time-based Indexes:</strong> Optimized for time-series data queries</li>
                            <li>• <strong>Partial Indexes:</strong> Conditional indexes for specific use cases</li>
                            <li>• <strong>Unique Constraints:</strong> Data integrity enforcement at database level</li>
                        </ul>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-lg border border-green-200">
                        <h4 class="font-semibold text-gray-900 mb-3">Data Normalization</h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• <strong>3NF Compliance:</strong> Normalized tables reduce data redundancy</li>
                            <li>• <strong>Foreign Key Constraints:</strong> Referential integrity enforcement</li>
                            <li>• <strong>Lookup Tables:</strong> Centralized reference data management</li>
                            <li>• <strong>Audit Trails:</strong> Complete change tracking with timestamps</li>
                        </ul>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-6 rounded-lg border border-purple-200">
                        <h4 class="font-semibold text-gray-900 mb-3">Performance Optimization</h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• <strong>Query Caching:</strong> Intelligent result caching with TTL</li>
                            <li>• <strong>Connection Pooling:</strong> Efficient database connection management</li>
                            <li>• <strong>Batch Operations:</strong> Bulk inserts and updates for efficiency</li>
                            <li>• <strong>Read Replicas:</strong> Separated read/write operations where possible</li>
                        </ul>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-red-50 p-6 rounded-lg border border-orange-200">
                        <h4 class="font-semibold text-gray-900 mb-3">Scalability Patterns</h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• <strong>Horizontal Partitioning:</strong> Table partitioning by date/key</li>
                            <li>• <strong>Archive Strategy:</strong> Automated old data archival</li>
                            <li>• <strong>Sharding Ready:</strong> Design supports future sharding</li>
                            <li>• <strong>Connection Scaling:</strong> Multi-database support architecture</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Migration Best Practices -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Migration Best Practices</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio implements Laravel migration best practices with
                additional safeguards:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Migration Safety</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>// Atomic migrations with rollback support
    public function up()
    {
        Schema::create('table_name', function (Blueprint $table) {
            // Table definition with proper constraints
            $table->id();
            $table->timestamps();

            // Always add indexes in same migration
            $table->index(['column', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('table_name');
    }</code></pre>
                </div>

                <div class="bg-white p-6 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3">Index Management</h4>
                    <pre class="bg-gray-800 text-white p-4 rounded text-sm overflow-x-auto"><code>// Proper index creation and naming
    $table->index('column_name', 'idx_table_column');
    $table->unique(['col1', 'col2'], 'unq_table_cols');
    $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');</code></pre>
                </div>
            </div>
        </div>

        <!-- Performance Monitoring -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Performance Monitoring</h2>
            <p class="text-gray-600 mb-6">Built-in database performance monitoring and optimization recommendations:</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Query Analysis</h3>
                    <p class="text-sm text-gray-600">Automatic slow query detection and optimization suggestions with
                        execution plan analysis.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Real-time Metrics</h3>
                    <p class="text-sm text-gray-600">Live database health monitoring with alerts for performance degradation
                        and resource usage.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Optimization</h3>
                    <p class="text-sm text-gray-600">Automated index recommendations and query optimization suggestions
                        based on usage patterns.</p>
                </div>
            </div>
        </div>
    </div>
@endsection