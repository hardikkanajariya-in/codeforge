@extends('codeforge-studio::layout.docs')

@section('title', 'Artisan Commands - CodeForge Database Studio')
@section('description', 'Complete reference for all CodeForge Database Studio Artisan commands and their usage.')

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
    <li class="text-primary-600 font-medium">Artisan Commands</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 002 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Artisan Commands</h1>
                    <p class="text-xl text-gray-600">Complete reference for all CodeForge Database Studio Artisan commands
                    </p>
                </div>
            </div>
            <p class="text-lg text-gray-600">CodeForge Database Studio provides 15+ Artisan commands for installation,
                database management, code generation, and maintenance tasks.</p>
        </div>

        <!-- Commands Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Command Categories</h2>
            <p class="text-gray-600 mb-6">All commands are prefixed with <code
                    class="bg-gray-100 px-2 py-1 rounded">codeforge:</code> and provide comprehensive help via <code
                    class="bg-gray-100 px-2 py-1 rounded">--help</code> option.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Installation</h3>
                    <div class="space-y-1 text-sm text-blue-600">
                        <div>codeforge:install</div>
                        <div>codeforge:assets</div>
                        <div>codeforge:asset-debug</div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-green-900 mb-3">Database</h3>
                    <div class="space-y-1 text-sm text-green-600">
                        <div>codeforge:migrate</div>
                        <div>codeforge:batch-migrate</div>
                        <div>codeforge:sync-migration-history</div>
                        <div>codeforge:create-snapshot</div>
                    </div>
                </div>

                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-purple-900 mb-3">Data Generation</h3>
                    <div class="space-y-1 text-sm text-purple-600">
                        <div>codeforge:generate-data</div>
                        <div>codeforge:run-seeders</div>
                        <div>codeforge:test-generation</div>
                        <div>codeforge:diagnose-seeders</div>
                    </div>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-orange-900 mb-3">Maintenance</h3>
                    <div class="space-y-1 text-sm text-orange-600">
                        <div>codeforge:cleanup-logs</div>
                        <div>codeforge:cleanup-docs</div>
                        <div>codeforge:collect-metrics</div>
                        <div>codeforge:toggle-query-logging</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installation Commands -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Installation Commands</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:install</h3>
                    <p class="text-gray-600 mb-4">Complete plugin installation with configuration publishing and migration
                        execution.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Basic installation
    php artisan codeforge:install

    # Force overwrite existing files
    php artisan codeforge:install --force</code></pre>
                    </div>
                    <div class="mt-3 text-sm text-gray-600">
                        <strong>Features:</strong> Publishes configuration files, runs migrations, validates system
                        requirements
                    </div>
                </div>

                <div class="border-l-4 border-indigo-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:assets</h3>
                    <p class="text-gray-600 mb-4">Manage plugin assets including CSS, JavaScript, and view files.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Publish all assets
    php artisan codeforge:assets {action}

    # Available actions: publish, republish, clear, list</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Commands -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Database Management Commands</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:migrate</h3>
                    <p class="text-gray-600 mb-4">Enhanced migration management with rollback, refresh, and reset
                        capabilities.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Run migrations
    php artisan codeforge:migrate

    # Rollback last batch
    php artisan codeforge:migrate --rollback

    # Rollback specific steps
    php artisan codeforge:migrate --rollback --step=3

    # Refresh migrations
    php artisan codeforge:migrate --refresh

    # Reset all migrations
    php artisan codeforge:migrate --reset</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-teal-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:batch-migrate</h3>
                    <p class="text-gray-600 mb-4">Execute migrations in controlled batches with dependency resolution.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Run batch migration
    php artisan codeforge:batch-migrate {migrations*}

    # Example with specific migrations
    php artisan codeforge:batch-migrate create_users_table create_posts_table</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-cyan-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:sync-migration-history</h3>
                    <p class="text-gray-600 mb-4">Synchronize migration history and cleanup orphaned entries.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Sync migration history
    php artisan codeforge:sync-migration-history

    # Sync with cleanup
    php artisan codeforge:sync-migration-history --cleanup</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:create-snapshot</h3>
                    <p class="text-gray-600 mb-4">Create database schema snapshots for version control and comparison.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Create schema snapshot
    php artisan codeforge:create-snapshot {table?}

    # Snapshot specific table
    php artisan codeforge:create-snapshot users</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Generation Commands -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Data Generation Commands</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:generate-data</h3>
                    <p class="text-gray-600 mb-4">Generate intelligent test data with relationship handling and custom
                        templates.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Generate data for table
    php artisan codeforge:generate-data {table} {count=10}

    # Generate 100 users
    php artisan codeforge:generate-data users 100

    # Use custom template
    php artisan codeforge:generate-data users 50 --template=custom</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-violet-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:run-seeders</h3>
                    <p class="text-gray-600 mb-4">Execute database seeders with dependency resolution and progress tracking.
                    </p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Run all seeders
    php artisan codeforge:run-seeders

    # Run specific seeder
    php artisan codeforge:run-seeders {seeder}

    # Run with force option
    php artisan codeforge:run-seeders --force</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-pink-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:diagnose-seeders</h3>
                    <p class="text-gray-600 mb-4">Diagnose seeder issues and validate seeder configuration.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Diagnose all seeders
    php artisan codeforge:diagnose-seeders

    # Diagnose specific seeder
    php artisan codeforge:diagnose-seeders {seeder?}</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-rose-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:test-generation</h3>
                    <p class="text-gray-600 mb-4">Test data generation capabilities and validate generation templates.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Test data generation
    php artisan codeforge:test-generation</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitoring & Maintenance Commands -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Monitoring & Maintenance Commands</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-orange-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:collect-metrics</h3>
                    <p class="text-gray-600 mb-4">Collect database health metrics and performance data.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Collect metrics for default connection
    php artisan codeforge:collect-metrics

    # Collect for specific connection
    php artisan codeforge:collect-metrics --connection=mysql</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-red-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:cleanup-logs</h3>
                    <p class="text-gray-600 mb-4">Clean up old log entries and manage log file sizes.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Cleanup logs older than 30 days (default)
    php artisan codeforge:cleanup-logs

    # Cleanup logs older than 7 days
    php artisan codeforge:cleanup-logs --days=7

    # Dry run to preview cleanup
    php artisan codeforge:cleanup-logs --dry-run</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-yellow-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:toggle-query-logging</h3>
                    <p class="text-gray-600 mb-4">Enable or disable database query logging for performance monitoring.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Toggle query logging (auto-detect current state)
    php artisan codeforge:toggle-query-logging

    # Explicitly enable logging
    php artisan codeforge:toggle-query-logging --enable

    # Explicitly disable logging
    php artisan codeforge:toggle-query-logging --disable</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-amber-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:cleanup-docs</h3>
                    <p class="text-gray-600 mb-4">Clean up generated documentation files and temporary assets.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Cleanup documentation files
    php artisan codeforge:cleanup-docs {type?}

    # Available types: generated, temp, cache, all</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentation Commands -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Documentation Commands</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-indigo-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:generate-docs</h3>
                    <p class="text-gray-600 mb-4">Generate comprehensive documentation for database schema and API.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Generate all documentation
    php artisan codeforge:generate-docs {type?}

    # Available types: schema, api, full, markdown, html</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Development & Debug Commands -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Development & Debug Commands</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-gray-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:debug-discovery</h3>
                    <p class="text-gray-600 mb-4">Debug seeder discovery process and validate seeder registration.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Debug seeder discovery
    php artisan codeforge:debug-discovery</code></pre>
                    </div>
                </div>

                <div class="border-l-4 border-slate-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:fix-seeder-paths</h3>
                    <p class="text-gray-600 mb-4">Fix seeder path issues and validate seeder autoloading.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto"><code># Fix seeder paths
    php artisan codeforge:fix-seeder-paths {path?}</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Command Best Practices -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Command Best Practices</h2>
            <p class="text-gray-600 mb-6">Guidelines for using CodeForge Database Studio commands effectively:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Safety & Validation</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Use --dry-run:</strong> Preview changes before execution</li>
                        <li>• <strong>Backup First:</strong> Always backup before destructive operations</li>
                        <li>• <strong>Test Environment:</strong> Test commands in development first</li>
                        <li>• <strong>Check Help:</strong> Use --help for detailed command options</li>
                        <li>• <strong>Validate Inputs:</strong> Commands validate parameters automatically</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Performance & Efficiency</h4>
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li>• <strong>Batch Operations:</strong> Use batch commands for multiple operations</li>
                        <li>• <strong>Connection Specific:</strong> Specify connections when working with multiple databases
                        </li>
                        <li>• <strong>Cleanup Regularly:</strong> Use cleanup commands to maintain performance</li>
                        <li>• <strong>Monitor Progress:</strong> Commands provide progress feedback for long operations</li>
                        <li>• <strong>Schedule Tasks:</strong> Use Laravel scheduler for regular maintenance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    generate code, and perform maintenance tasks.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Database Commands</h3>
            <div class="space-y-2">
                <div class="text-sm text-blue-600">codeforge:db:analyze</div>
                <div class="text-sm text-blue-600">codeforge:db:health</div>
                <div class="text-sm text-blue-600">codeforge:db:optimize</div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-900 mb-3">Generation Commands</h3>
            <div class="space-y-2">
                <div class="text-sm text-green-600">codeforge:generate:model</div>
                <div class="text-sm text-green-600">codeforge:generate:migration</div>
                <div class="text-sm text-green-600">codeforge:generate:resource</div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-purple-900 mb-3">Documentation Commands</h3>
            <div class="space-y-2">
                <div class="text-sm text-purple-600">codeforge:docs:generate</div>
                <div class="text-sm text-purple-600">codeforge:docs:export</div>
                <div class="text-sm text-purple-600">codeforge:docs:publish</div>
            </div>
        </div>
    </div>
    </div>

    <!-- Database Commands -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Database Commands</h2>

        <div class="space-y-6">
            <div class="border-l-4 border-blue-500 pl-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:db:analyze</h3>
                <p class="text-gray-600 mb-4">Analyze database structure and performance metrics.</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        # Basic analysis<br>
                        php artisan codeforge:db:analyze<br><br>
                        # With specific table<br>
                        php artisan codeforge:db:analyze --table=users<br><br>
                        # Export results<br>
                        php artisan codeforge:db:analyze --export=json
                    </div>
                </div>
            </div>

            <div class="border-l-4 border-green-500 pl-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:db:health</h3>
                <p class="text-gray-600 mb-4">Check database health and connection status.</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        # Quick health check<br>
                        php artisan codeforge:db:health<br><br>
                        # Detailed report<br>
                        php artisan codeforge:db:health --detailed<br><br>
                        # Monitor mode<br>
                        php artisan codeforge:db:health --monitor
                    </div>
                </div>
            </div>

            <div class="border-l-4 border-purple-500 pl-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:db:optimize</h3>
                <p class="text-gray-600 mb-4">Optimize database tables and indexes.</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        # Optimize all tables<br>
                        php artisan codeforge:db:optimize<br><br>
                        # Optimize specific table<br>
                        php artisan codeforge:db:optimize --table=users<br><br>
                        # Dry run mode<br>
                        php artisan codeforge:db:optimize --dry-run
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generation Commands -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Code Generation Commands</h2>

        <div class="space-y-6">
            <div class="border-l-4 border-orange-500 pl-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:generate:model</h3>
                <p class="text-gray-600 mb-4">Generate Eloquent models from database tables.</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        # Generate model for table<br>
                        php artisan codeforge:generate:model User<br><br>
                        # Generate with relationships<br>
                        php artisan codeforge:generate:model User --with-relationships<br><br>
                        # Generate with factory and seeder<br>
                        php artisan codeforge:generate:model User --all
                    </div>
                </div>
            </div>

            <div class="border-l-4 border-red-500 pl-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:generate:migration</h3>
                <p class="text-gray-600 mb-4">Generate migrations from existing database structure.</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        # Generate migration for table<br>
                        php artisan codeforge:generate:migration users<br><br>
                        # Generate from existing table<br>
                        php artisan codeforge:generate:migration --from-table=users<br><br>
                        # Generate with foreign keys<br>
                        php artisan codeforge:generate:migration users --with-foreign-keys
                    </div>
                </div>
            </div>

            <div class="border-l-4 border-teal-500 pl-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">codeforge:generate:resource</h3>
                <p class="text-gray-600 mb-4">Generate Filament resources from models.</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        # Generate basic resource<br>
                        php artisan codeforge:generate:resource UserResource<br><br>
                        # Generate with all features<br>
                        php artisan codeforge:generate:resource UserResource --full<br><br>
                        # Generate with custom namespace<br>
                        php artisan codeforge:generate:resource UserResource --namespace=Admin
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Common Options -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Common Options</h2>
        <p class="text-gray-600 mb-6">
            Most CodeForge commands support these common options:
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Global Options</h3>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <code class="bg-gray-200 px-2 py-1 rounded mr-2">--verbose</code>
                        <span class="text-gray-600">Show detailed output</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <code class="bg-gray-200 px-2 py-1 rounded mr-2">--quiet</code>
                        <span class="text-gray-600">Suppress output</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <code class="bg-gray-200 px-2 py-1 rounded mr-2">--force</code>
                        <span class="text-gray-600">Force execution</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Output Options</h3>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <code class="bg-gray-200 px-2 py-1 rounded mr-2">--format=json</code>
                        <span class="text-gray-600">JSON output</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <code class="bg-gray-200 px-2 py-1 rounded mr-2">--export=file</code>
                        <span class="text-gray-600">Export to file</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <code class="bg-gray-200 px-2 py-1 rounded mr-2">--dry-run</code>
                        <span class="text-gray-600">Preview without executing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="bg-gradient-to-r from-primary-50 to-indigo-50 border border-primary-200 rounded-xl p-8">
        <h2 class="text-2xl font-bold text-primary-900 mb-4">Next Steps</h2>
        <p class="text-primary-700 mb-6">Explore related API documentation:</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('codeforge.docs.api.services') }}"
                class="flex items-center p-4 bg-white rounded-lg border border-primary-200 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-900">Services API</h3>
                    <p class="text-primary-600 text-sm">Service layer documentation</p>
                </div>
            </a>
            <a href="{{ route('codeforge.docs.architecture.events') }}"
                class="flex items-center p-4 bg-white rounded-lg border border-primary-200 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-5 5v-5zM4 19h5m0-5h11M4 14h5M4 9h5m0-5h11M4 4h5"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-900">Events</h3>
                    <p class="text-primary-600 text-sm">Event system reference</p>
                </div>
            </a>
        </div>
    </div>
    </div>
@endsection