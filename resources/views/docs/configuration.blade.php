@extends('codeforge-studio::layout.docs')

@section('title', 'Configuration Guide - CodeForge Database Studio')
@section('description', 'Complete configuration guide for CodeForge Database Studio. Learn how to customize features and optimize performance.')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('codeforge.docs.home') }}" class="text-gray-500 hover:text-primary-600">Documentation</a>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-primary-600 font-medium">Configuration</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Configuration Guide</h1>
                    <p class="text-xl text-gray-600">Customize CodeForge Database Studio to fit your development workflow
                    </p>
                </div>
            </div>
        </div>

        <!-- Plugin Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Plugin Configuration</h2>
                <p class="text-gray-600">Configure features during plugin registration</p>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">
                    The plugin can be configured when registering it in your Filament panel provider. This approach provides
                    fine-grained control over which features are enabled for each panel.
                </p>

                <div class="bg-gray-900 rounded-lg p-4 mb-6 overflow-x-auto">
                    <pre class="text-sm"><code class="text-gray-300">// In your AdminPanelProvider.php

    use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

    return $panel
        // ... other panel configuration
        ->plugins([
            <span class="text-yellow-400">CodeForgeStudioPlugin::make()</span>
                <span class="text-green-400">->enableSchemaDesigner(true)</span>        <span class="text-gray-500">// Visual database schema design</span>
                <span class="text-green-400">->enableMigrationManager(true)</span>      <span class="text-gray-500">// Migration tracking and management</span>
                <span class="text-green-400">->enableHealthMonitoring(true)</span>      <span class="text-gray-500">// Database health monitoring</span>
                <span class="text-green-400">->enableSmartSeeding(true)</span>          <span class="text-gray-500">// Intelligent data generation</span>
                <span class="text-green-400">->enableDocumentationGenerator(true)</span> <span class="text-gray-500">// Automated documentation</span>
                <span class="text-green-400">->enableCodeGeneration(true)</span>        <span class="text-gray-500">// Laravel code generation</span>
                <span class="text-green-400">->enableDevDocs(false)</span>              <span class="text-gray-500">// Developer documentation access</span>
        ]);</code></pre>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Available Methods</h4>
                        <div class="space-y-2">
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <code class="text-sm text-purple-600 mr-2">enableSchemaDesigner()</code>
                                <span class="text-sm text-gray-600">Visual schema design tools</span>
                            </div>
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <code class="text-sm text-purple-600 mr-2">enableMigrationManager()</code>
                                <span class="text-sm text-gray-600">Migration tracking and history</span>
                            </div>
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <code class="text-sm text-purple-600 mr-2">enableHealthMonitoring()</code>
                                <span class="text-sm text-gray-600">Performance monitoring</span>
                            </div>
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <code class="text-sm text-purple-600 mr-2">enableSmartSeeding()</code>
                                <span class="text-sm text-gray-600">Intelligent data generation</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Advanced Features</h4>
                        <div class="space-y-2">
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <code class="text-sm text-purple-600 mr-2">enableDocumentationGenerator()</code>
                                <span class="text-sm text-gray-600">Auto documentation</span>
                            </div>
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <code class="text-sm text-purple-600 mr-2">enableCodeGeneration()</code>
                                <span class="text-sm text-gray-600">Laravel code generators</span>
                            </div>
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <code class="text-sm text-purple-600 mr-2">enableDevDocs()</code>
                                <span class="text-sm text-gray-600">Developer documentation</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Configuration File</h2>
                <p class="text-gray-600">Detailed settings in <code
                        class="bg-gray-100 px-2 py-1 rounded text-sm">config/codeforge-database-studio.php</code></p>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Plugin registration (primary) -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                        <p class="text-amber-900 text-sm">
                            <strong>Important:</strong> Filament pages and resources are enabled via
                            <code class="bg-amber-100 px-1 rounded">CodeForgeStudioPlugin::make()->enable*()</code>
                            in your panel provider. Config <code class="bg-amber-100 px-1 rounded">features.*</code>
                            only controls quick-action cards on the Database Overview page—not plugin registration.
                        </p>
                    </div>

                    <!-- Features (overview UI) -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Features (Database Overview)</h4>
                        <div class="bg-gray-900 rounded-lg p-4 mb-2">
                            <pre class="text-sm"><code class="text-gray-300"><span class="text-blue-400">'features'</span> => [
        <span class="text-blue-400">'schema_designer'</span> => <span class="text-green-400">true</span>,
        <span class="text-blue-400">'migration_manager'</span> => <span class="text-green-400">true</span>,
        <span class="text-blue-400">'health_monitoring'</span> => <span class="text-green-400">true</span>,
        <span class="text-blue-400">'smart_seeding'</span> => <span class="text-green-400">true</span>,
        <span class="text-blue-400">'documentation_generator'</span> => <span class="text-green-400">true</span>,
        <span class="text-blue-400">'code_generation'</span> => <span class="text-green-400">true</span>,
        <span class="text-blue-400">'dev_docs'</span> => <span class="text-red-400">false</span>,
    ],</code></pre>
                        </div>
                        <p class="text-gray-600 text-sm">Show or hide quick links on the Database Overview dashboard. Use
                            <code class="bg-gray-100 px-1 rounded">enableDevDocs()</code> on the plugin to expose the in-app docs link.</p>
                    </div>

                    <!-- Navigation sort -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Navigation Sort</h4>
                        <div class="bg-gray-900 rounded-lg p-4 mb-2">
                            <pre class="text-sm"><code class="text-gray-300"><span class="text-blue-400">'navigation'</span> => [
        <span class="text-blue-400">'sort'</span> => <span class="text-yellow-400">1</span>,
    ],</code></pre>
                        </div>
                        <p class="text-gray-600 text-sm">Used as a base sort offset for some Filament resources. Navigation groups are defined per page/resource in code.</p>
                    </div>

                    <!-- Planned / not wired yet -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Reserved settings (not active yet)</h4>
                        <p class="text-gray-600 text-sm mb-2">These keys exist in the config file for future use but are <strong>not read</strong> by the plugin at runtime today:</p>
                        <ul class="text-sm text-gray-600 list-disc pl-5 space-y-1">
                            <li><code>auto_register</code>, <code>register_on_panels</code></li>
                            <li><code>migrations.*</code>, <code>health_monitoring.*</code> (except query logging below)</li>
                            <li><code>schema_designer.*</code>, <code>code_generation.*</code>, <code>security.*</code></li>
                        </ul>
                    </div>

                    <!-- Query Logging -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Query Performance Logging</h4>
                        <div class="bg-gray-900 rounded-lg p-4 mb-2">
                            <pre class="text-sm"><code class="text-gray-300"><span class="text-blue-400">'enable_query_logging'</span> => <span class="text-green-400">true</span>,
    <span class="text-blue-400">'query_logging'</span> => [
        <span class="text-blue-400">'slow_query_threshold'</span> => <span class="text-yellow-400">1000</span>, <span class="text-gray-500">// Log queries slower than this (ms)</span>
        <span class="text-blue-400">'log_all_queries'</span> => <span class="text-red-400">false</span>, <span class="text-gray-500">// Set to true to log all queries</span>
        <span class="text-blue-400">'max_log_entries'</span> => <span class="text-yellow-400">10000</span>, <span class="text-gray-500">// Maximum number of log entries</span>
        <span class="text-blue-400">'cleanup_older_than_days'</span> => <span class="text-yellow-400">30</span>, <span class="text-gray-500">// Clean up logs older than X days</span>
        <span class="text-blue-400">'skip_patterns'</span> => [
            <span class="text-yellow-400">'show tables'</span>,
            <span class="text-yellow-400">'show columns'</span>,
            <span class="text-yellow-400">'information_schema'</span>,
            <span class="text-yellow-400">'query_performance_logs'</span>,
            <span class="text-yellow-400">'database_health_metrics'</span>,
        ],
    ],</code></pre>
                        </div>
                        <p class="text-gray-600 text-sm">Read by <code>QueryPerformanceListener</code>. Toggle at runtime with <code>php artisan codeforge:toggle-query-logging</code>.</p>
                    </div>

                    <!-- Code Generation -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Code Generation Paths</h4>
                        <div class="bg-gray-900 rounded-lg p-4 mb-2">
                            <pre class="text-sm"><code class="text-gray-300"><span class="text-blue-400">'code_generation'</span> => [
        <span class="text-blue-400">'output_path'</span> => [
            <span class="text-blue-400">'models'</span> => <span class="text-yellow-400">'app/Models'</span>,
            <span class="text-blue-400">'migrations'</span> => <span class="text-yellow-400">'database/migrations'</span>,
            <span class="text-blue-400">'factories'</span> => <span class="text-yellow-400">'database/factories'</span>,
            <span class="text-blue-400">'seeders'</span> => <span class="text-yellow-400">'database/seeders'</span>,
            <span class="text-blue-400">'resources'</span> => <span class="text-yellow-400">'app/Filament/Resources'</span>,
        ],
        <span class="text-blue-400">'namespace'</span> => [
            <span class="text-blue-400">'models'</span> => <span class="text-yellow-400">'App\\Models'</span>,
            <span class="text-blue-400">'factories'</span> => <span class="text-yellow-400">'Database\\Factories'</span>,
            <span class="text-blue-400">'seeders'</span> => <span class="text-yellow-400">'Database\\Seeders'</span>,
            <span class="text-blue-400">'resources'</span> => <span class="text-yellow-400">'App\\Filament\\Resources'</span>,
        ],
        <span class="text-blue-400">'auto_format'</span> => <span class="text-green-400">true</span>,
        <span class="text-blue-400">'backup_existing'</span> => <span class="text-green-400">true</span>,
    ],</code></pre>
                        </div>
                        <p class="text-gray-600 text-sm">Reserved for future generator path customization. Generator pages use built-in defaults today.</p>
                    </div>

                    <!-- Security Settings -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Security Configuration</h4>
                        <div class="bg-gray-900 rounded-lg p-4 mb-2">
                            <pre class="text-sm"><code class="text-gray-300"><span class="text-blue-400">'security'</span> => [
        <span class="text-blue-400">'require_confirmation'</span> => [
            <span class="text-blue-400">'drop_table'</span> => <span class="text-green-400">true</span>,
            <span class="text-blue-400">'drop_column'</span> => <span class="text-green-400">true</span>,
            <span class="text-blue-400">'rollback_migration'</span> => <span class="text-green-400">true</span>,
        ],
        <span class="text-blue-400">'allowed_operations'</span> => [
            <span class="text-blue-400">'create_table'</span> => <span class="text-green-400">true</span>,
            <span class="text-blue-400">'alter_table'</span> => <span class="text-green-400">true</span>,
            <span class="text-blue-400">'drop_table'</span> => <span class="text-red-400">false</span>, <span class="text-gray-500">// Disabled by default for safety</span>
            <span class="text-blue-400">'create_migration'</span> => <span class="text-green-400">true</span>,
            <span class="text-blue-400">'rollback_migration'</span> => <span class="text-green-400">true</span>,
        ],
    ],</code></pre>
                        </div>
                        <p class="text-gray-600 text-sm">Reserved for future UI safety prompts. Not enforced by the plugin yet.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Environment Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Environment Configuration</h2>
                <p class="text-gray-600">Environment-specific settings for different deployment stages</p>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Development -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Development Environment</h4>
                        <div class="bg-green-50 rounded-lg p-4 mb-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium text-green-800">Recommended Settings</span>
                            </div>
                            <ul class="text-sm text-green-700 space-y-1">
                                <li>• Enable all features for testing</li>
                                <li>• Enable developer documentation</li>
                                <li>• Enable query logging</li>
                                <li>• Disable destructive operations safety</li>
                            </ul>
                        </div>
                        <div class="bg-gray-900 rounded-lg p-3 text-xs">
                            <pre><code class="text-gray-300">->enableDevDocs(<span class="text-green-400">true</span>)
    ->enableSchemaDesigner(<span class="text-green-400">true</span>)
    ->enableCodeGeneration(<span class="text-green-400">true</span>)</code></pre>
                        </div>
                    </div>

                    <!-- Production -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Production Environment</h4>
                        <div class="bg-red-50 rounded-lg p-4 mb-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 15.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                                <span class="font-medium text-red-800">Security First</span>
                            </div>
                            <ul class="text-sm text-red-700 space-y-1">
                                <li>• Disable developer documentation</li>
                                <li>• Disable code generation</li>
                                <li>• Enable health monitoring only</li>
                                <li>• Require confirmations for all destructive operations</li>
                            </ul>
                        </div>
                        <div class="bg-gray-900 rounded-lg p-3 text-xs">
                            <pre><code class="text-gray-300">->enableDevDocs(<span class="text-red-400">false</span>)
    ->enableCodeGeneration(<span class="text-red-400">false</span>)
    ->enableHealthMonitoring(<span class="text-green-400">true</span>)</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commands Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Available Artisan Commands</h2>
                <p class="text-gray-600">Command-line tools for managing the plugin</p>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Installation & Setup -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Installation & Setup</h4>
                        <div class="space-y-3">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:install</code>
                                <p class="text-xs text-gray-600 mt-1">Complete plugin installation</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:sync-migration-history</code>
                                <p class="text-xs text-gray-600 mt-1">Sync migration history with existing migrations</p>
                            </div>
                        </div>
                    </div>

                    <!-- Health & Monitoring -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Health & Monitoring</h4>
                        <div class="space-y-3">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:collect-metrics</code>
                                <p class="text-xs text-gray-600 mt-1">Collect database health metrics</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:toggle-query-logging</code>
                                <p class="text-xs text-gray-600 mt-1">Enable/disable query performance logging</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Generation -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Data Generation</h4>
                        <div class="space-y-3">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:generate-data</code>
                                <p class="text-xs text-gray-600 mt-1">Generate test data using smart seeding</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:run-seeders</code>
                                <p class="text-xs text-gray-600 mt-1">Execute configured data seeders</p>
                            </div>
                        </div>
                    </div>

                    <!-- Documentation -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Documentation</h4>
                        <div class="space-y-3">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:generate-docs</code>
                                <p class="text-xs text-gray-600 mt-1">Generate database documentation</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:create-snapshot</code>
                                <p class="text-xs text-gray-600 mt-1">Create database schema snapshot</p>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Maintenance</h4>
                        <div class="space-y-3">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:cleanup-logs</code>
                                <p class="text-xs text-gray-600 mt-1">Clean up old log entries</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:cleanup-docs</code>
                                <p class="text-xs text-gray-600 mt-1">Clean up old documentation files</p>
                            </div>
                        </div>
                    </div>

                    <!-- Asset Management -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Asset Management</h4>
                        <div class="space-y-3">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <code class="text-sm text-gray-800">php artisan codeforge:assets publish</code>
                                <p class="text-xs text-gray-600 mt-1">Publish or refresh plugin CSS/JS (also: remove, refresh)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Optimization -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Performance Optimization Tips</h3>
                    <div class="space-y-2 text-gray-700">
                        <div class="flex items-start">
                            <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <p>Use Redis or Memcached for caching query results and schema information</p>
                        </div>
                        <div class="flex items-start">
                            <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <p>Configure queue processing for background health metric collection</p>
                        </div>
                        <div class="flex items-start">
                            <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <p>Adjust query logging thresholds based on your application's performance needs</p>
                        </div>
                        <div class="flex items-start">
                            <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <p>Regularly clean up old log entries using the provided artisan commands</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Priority -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-amber-800 mb-2">Configuration Priority</h3>
                    <p class="text-amber-700 mb-3">
                        Configuration settings are applied in the following order of priority:
                    </p>
                    <ol class="text-amber-700 space-y-1">
                        <li><strong>1. Plugin method configuration</strong> (highest priority) - Settings defined when
                            registering the plugin</li>
                        <li><strong>2. Configuration file settings</strong> - Settings in <code
                                class="bg-amber-100 px-1 rounded">config/codeforge-database-studio.php</code></li>
                        <li><strong>3. Default values</strong> (lowest priority) - Built-in plugin defaults</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
