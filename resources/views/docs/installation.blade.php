@extends('codeforge-studio::layout.docs')

@section('title', 'Installation Guide - CodeForge Database Studio')
@section('description', 'Complete installation guide for CodeForge Database Studio. Step-by-step instructions for Laravel developers.')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('codeforge.docs.home') }}" class="text-gray-500 hover:text-primary-600">Documentation</a>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-primary-600 font-medium">Installation</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Installation Guide</h1>
                    <p class="text-xl text-gray-600">Complete installation instructions for CodeForge Database Studio</p>
                </div>
            </div>
        </div>

        <!-- Prerequisites -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 15.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-amber-800 mb-2">Before You Begin</h3>
                    <p class="text-amber-700">
                        Ensure your system meets the <a href="{{ route('codeforge.docs.requirements') }}"
                            class="underline font-medium">system requirements</a>
                        and you have a working Laravel application with Filament installed.
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 1: Manual Package Installation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold text-sm">1</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Manual Package Installation</h2>
                </div>
                <p class="text-gray-600 mt-2 ml-12">Install the package manually in your Laravel project</p>
            </div>
            <div class="p-6">
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-amber-800 text-sm">
                                <strong>Current Installation Method:</strong> The package is not yet available via Composer.
                                We are working hard to publish it to Packagist for convenient installation. For now, please
                                follow the manual installation steps below.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">Manual Installation Steps:</h4>

                    <!-- Step 1a: Extract Package -->
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h5 class="font-medium text-gray-900 mb-2">1. Extract Package Files</h5>
                        <p class="text-gray-700 mb-2">Extract the purchased package ZIP file to your Laravel project:</p>
                        <div class="bg-gray-900 rounded-lg p-3 mb-2">
                            <code class="text-green-400 text-sm">packages/codeforge/</code>
                        </div>
                        <p class="text-sm text-gray-600">Place the entire <code
                                class="bg-gray-100 px-1 rounded">codeforge</code> directory inside a <code
                                class="bg-gray-100 px-1 rounded">packages</code> folder in your project root.</p>
                    </div>

                    <!-- Step 1b: Update Composer -->
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h5 class="font-medium text-gray-900 mb-2">2. Update Composer Configuration</h5>
                        <p class="text-gray-700 mb-2">Add the package to your project's <code
                                class="bg-gray-100 px-1 rounded">composer.json</code>:</p>
                        <div class="bg-gray-900 rounded-lg p-3 mb-2 overflow-x-auto">
                            <pre class="text-sm"><code class="text-gray-300">{
        "repositories": [
            {
                "type": "path",
                "url": "./packages/codeforge"
            }
        ],
        "require": {
            <span class="text-green-400">"hkdevs/codeforge-database-studio": "@dev"</span>
        }
    }</code></pre>
                        </div>
                    </div>

                    <!-- Step 1c: Install Dependencies -->
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h5 class="font-medium text-gray-900 mb-2">3. Install Package Dependencies</h5>
                        <div class="bg-gray-900 rounded-lg p-3 mb-2">
                            <code class="text-green-400 text-sm">composer update</code>
                        </div>
                        <p class="text-sm text-gray-600">This will install the package and its dependencies from your local
                            path.</p>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-4 mt-4">
                    <p class="text-blue-800 text-sm">
                        <strong>Note:</strong> This package is commercially licensed. The manual installation method is
                        temporary while we prepare the Composer package for convenient installation.
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 2: Run Installation Command -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold text-sm">2</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Run Installation Command</h2>
                </div>
                <p class="text-gray-600 mt-2 ml-12">Execute the automated installation process</p>
            </div>
            <div class="p-6">
                <div class="bg-gray-900 rounded-lg p-4 mb-4">
                    <code class="text-green-400 text-sm">
                                php artisan codeforge:install
                            </code>
                </div>
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">What this command does:</h4>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Publishes configuration file to <code
                                class="bg-gray-100 px-2 py-1 rounded text-sm">config/codeforge-database-studio.php</code>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Publishes and runs database migrations for plugin functionality
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Creates necessary database tables for plugin operation
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            Syncs migration history with existing Laravel migrations
                        </li>
                    </ul>

                    <div class="bg-gray-50 rounded-lg p-4 mt-4">
                        <h5 class="font-medium text-gray-900 mb-2">Installation Options:</h5>
                        <div class="space-y-2">
                            <div class="bg-gray-900 rounded p-2">
                                <code class="text-green-400 text-sm">php artisan codeforge:install --force</code>
                            </div>
                            <p class="text-sm text-gray-600">Force overwrite existing files (use with caution)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Plugin Registration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold text-sm">3</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Register Plugin</h2>
                </div>
                <p class="text-gray-600 mt-2 ml-12">Add the plugin to your Filament panel provider</p>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">
                    Open your Filament panel provider (typically <code
                        class="bg-gray-100 px-2 py-1 rounded text-sm">app/Providers/Filament/AdminPanelProvider.php</code>)
                    and add the plugin:
                </p>

                <div class="bg-gray-900 rounded-lg p-4 mb-4 overflow-x-auto">
                    <pre class="text-sm"><code class="text-gray-300">&lt;?php

        use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

        // In your panel method:
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // ... other configuration
            <span class="text-green-400">->plugins([
                CodeForgeStudioPlugin::make()
                    ->enableSchemaDesigner(true)
                    ->enableMigrationManager(true)
                    ->enableHealthMonitoring(true)
                    ->enableSmartSeeding(true)
                    ->enableDocumentationGenerator(true)
                    ->enableCodeGeneration(true)
                    ->enableDevDocs(false), // Enable only if needed
            ])</span>;</code></pre>
                </div>

                <div class="bg-blue-50 rounded-lg p-4">
                    <h5 class="font-medium text-blue-800 mb-2">Feature Configuration:</h5>
                    <p class="text-blue-700 text-sm">
                        Each feature can be individually enabled or disabled. See the
                        <a href="{{ route('codeforge.docs.configuration') }}" class="underline font-medium">Configuration
                            Guide</a>
                        for detailed options.
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 4: Database Tables -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold text-sm">4</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Database Tables Created</h2>
                </div>
                <p class="text-gray-600 mt-2 ml-12">The following tables will be automatically created</p>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">database_manager_logs</h5>
                                <p class="text-sm text-gray-600">General plugin logging</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">migration_histories</h5>
                                <p class="text-sm text-gray-600">Migration tracking and history</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">query_performance_logs</h5>
                                <p class="text-sm text-gray-600">Query performance monitoring</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">database_health_metrics</h5>
                                <p class="text-sm text-gray-600">Health monitoring data</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">data_seeders</h5>
                                <p class="text-sm text-gray-600">Smart seeding configurations</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">seeder_execution_logs</h5>
                                <p class="text-sm text-gray-600">Seeder execution tracking</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">data_generation_templates</h5>
                                <p class="text-sm text-gray-600">Data generation templates</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">documentation_generations</h5>
                                <p class="text-sm text-gray-600">Documentation generation logs</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">schema_snapshots</h5>
                                <p class="text-sm text-gray-600">Database schema snapshots</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">code_generation_histories</h5>
                                <p class="text-sm text-gray-600">Code generation tracking</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 5: Verification -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-green-600 font-bold text-sm">5</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Verify Installation</h2>
                </div>
                <p class="text-gray-600 mt-2 ml-12">Confirm everything is working correctly</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900">Quick verification steps:</h4>

                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 mr-3">
                                <span class="text-green-600 text-xs font-bold">1</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Access your Filament admin panel</p>
                                <p class="text-gray-600 text-sm">Navigate to your admin dashboard (usually <code
                                        class="bg-gray-100 px-1 rounded">/admin</code>)</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 mr-3">
                                <span class="text-green-600 text-xs font-bold">2</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Look for "Database Overview" in navigation</p>
                                <p class="text-gray-600 text-sm">You should see the CodeForge Database Studio navigation
                                    group</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 mr-3">
                                <span class="text-green-600 text-xs font-bold">3</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Check database tables</p>
                                <p class="text-gray-600 text-sm">Verify the plugin tables were created in your database</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 mr-3">
                                <span class="text-green-600 text-xs font-bold">4</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Test basic functionality</p>
                                <p class="text-gray-600 text-sm">Try accessing the Database Overview page to confirm all
                                    features load</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-900 rounded-lg p-4 mt-6">
                        <p class="text-white text-sm mb-2"><strong>Check migration status:</strong></p>
                        <code class="text-green-400 text-sm">php artisan migrate:status</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Troubleshooting -->
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-red-800 mb-2">Common Installation Issues</h3>
                    <div class="space-y-3 text-red-700">
                        <div>
                            <p class="font-medium">Plugin not appearing in navigation:</p>
                            <p class="text-sm">Ensure you've added the plugin to your panel provider and cleared any caches.
                            </p>
                        </div>
                        <div>
                            <p class="font-medium">Migration errors:</p>
                            <p class="text-sm">Check database permissions and ensure your database user has CREATE TABLE
                                privileges.</p>
                        </div>
                        <div>
                            <p class="font-medium">Memory limit issues:</p>
                            <p class="text-sm">Increase PHP memory limit to at least 256MB for large schema operations.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">🎉 Installation Complete!</h3>
                    <p class="text-gray-700 mb-4">
                        CodeForge Database Studio is now installed and ready to use. Here's what you can do next:
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('codeforge.docs.configuration') }}"
                            class="inline-flex items-center text-green-700 hover:text-green-800 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Configure the plugin settings
                        </a>
                        <br>
                        <a href="{{ route('codeforge.docs.features.overview') }}"
                            class="inline-flex items-center text-green-700 hover:text-green-800 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            Explore available features
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection