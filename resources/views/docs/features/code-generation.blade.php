@extends('codeforge-studio::layout.docs')

@section('title', 'Code Generation - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Code Generation</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Code Generation Suite</h1>
                    <p class="text-xl text-gray-600">Comprehensive Laravel component generation with intelligent analysis
                        and template systems</p>
                </div>
            </div>
        </div>

        <!-- Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
            <p class="text-gray-600 mb-6">
                The Code Generation Suite provides a comprehensive set of generators for Laravel components including
                models,
                migrations, factories, seeders, and Filament resources. Each generator includes intelligent analysis and
                suggestion systems to create optimized, production-ready code.
            </p>
        </div>

        <!-- Available Generators -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Generators</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Model Generator</h3>
                    <ul class="space-y-2 text-blue-800 text-sm">
                        <li><strong>Intelligent Analysis:</strong> Database-driven fillable field suggestions from table
                            columns</li>
                        <li><strong>Relationship Discovery:</strong> Automatic foreign key relationship detection</li>
                        <li><strong>Smart Casting:</strong> Data type-aware casting configuration</li>
                        <li><strong>Security Fields:</strong> Automated hidden field suggestions for sensitive data</li>
                        <li><strong>Feature Generation:</strong> Query scopes, mutators, accessors, and custom methods</li>
                    </ul>
                </div>

                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-green-900 mb-4">Migration Generator</h3>
                    <ul class="space-y-2 text-green-800 text-sm">
                        <li><strong>Schema Analysis:</strong> Intelligent table structure generation</li>
                        <li><strong>Column Types:</strong> Appropriate Laravel column type selection</li>
                        <li><strong>Index Creation:</strong> Performance-optimized index suggestions</li>
                        <li><strong>Foreign Keys:</strong> Relationship-aware constraint generation</li>
                        <li><strong>Best Practices:</strong> Laravel migration conventions and standards</li>
                    </ul>
                </div>

                <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                    <h3 class="text-lg font-semibold text-purple-900 mb-4">Factory Generator</h3>
                    <ul class="space-y-2 text-purple-800 text-sm">
                        <li><strong>Realistic Data:</strong> Faker integration for appropriate data types</li>
                        <li><strong>Field Mapping:</strong> Intelligent data generation based on field names</li>
                        <li><strong>Relationship Support:</strong> Factory states and relationships</li>
                        <li><strong>Custom Providers:</strong> Specialized data generation for business domains</li>
                        <li><strong>Testing Ready:</strong> Production-quality test data generation</li>
                    </ul>
                </div>

                <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                    <h3 class="text-lg font-semibold text-orange-900 mb-4">Seeder Generator</h3>
                    <ul class="space-y-2 text-orange-800 text-sm">
                        <li><strong>Data Population:</strong> Database seeder creation with factory integration</li>
                        <li><strong>Dependency Management:</strong> Relationship-aware seeding order</li>
                        <li><strong>Volume Control:</strong> Configurable record counts and batching</li>
                        <li><strong>Environment Support:</strong> Different seeding strategies per environment</li>
                        <li><strong>Performance Optimization:</strong> Efficient bulk data insertion</li>
                    </ul>
                </div>

                <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-200">
                    <h3 class="text-lg font-semibold text-indigo-900 mb-4">Filament Resource Generator</h3>
                    <ul class="space-y-2 text-indigo-800 text-sm">
                        <li><strong>Complete Admin Interface:</strong> Full CRUD operations with Filament components</li>
                        <li><strong>Form Generation:</strong> Intelligent form field mapping from model attributes</li>
                        <li><strong>Table Columns:</strong> Optimized table display with sortable columns</li>
                        <li><strong>Actions & Filters:</strong> Common actions and filter generation</li>
                        <li><strong>Relationship Handling:</strong> Related model management and display</li>
                    </ul>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Generator Overview Dashboard</h3>
                    <ul class="space-y-2 text-gray-800 text-sm">
                        <li><strong>Central Hub:</strong> Access all generators from a single interface</li>
                        <li><strong>Quick Actions:</strong> Rapid navigation to specific generators</li>
                        <li><strong>Status Tracking:</strong> Monitor generation history and activity</li>
                        <li><strong>Template Management:</strong> Reusable generation templates</li>
                        <li><strong>Configuration Access:</strong> Generator settings and customization</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Dashboard Access -->
        <div class="bg-gradient-to-r from-orange-50 to-red-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Access</h2>
            <p class="text-gray-600 mb-6">Access the code generators through your Filament admin panel:</p>

            <div class="bg-white p-4 rounded-lg border">
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Navigate to your Filament admin panel</li>
                    <li>Look for the <strong>"Code Generation"</strong> navigation group</li>
                    <li>Access individual generators:</li>
                    <ul class="list-disc list-inside ml-6 mt-2 space-y-1 text-sm">
                        <li><strong>Generator Overview:</strong> Central dashboard for all generators</li>
                        <li><strong>Model Generator:</strong> Laravel Eloquent model creation</li>
                        <li><strong>Migration Generator:</strong> Database migration generation</li>
                        <li><strong>Factory Generator:</strong> Model factory creation</li>
                        <li><strong>Seeder Generator:</strong> Database seeder generation</li>
                        <li><strong>Filament Resource Generator:</strong> Admin interface generation</li>
                    </ul>
                </ol>
            </div>
        </div>

        <!-- Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Configuration</h2>
            <p class="text-gray-600 mb-6">Enable code generation features in your plugin configuration:</p>

            <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>// In your AdminPanelProvider.php
    CodeForgeStudioPlugin::make()
        ->enableCodeGeneration(true)  // Enable all code generation features
        // ... other configuration</code></pre>
        </div>

        <!-- Intelligent Features -->
        <div class="bg-gray-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Intelligent Analysis Features</h2>
            <p class="text-gray-600 mb-6">Each generator includes intelligent analysis powered by database introspection:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Database-Driven Suggestions</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Analyzes actual table structure and columns</li>
                        <li>• Suggests fillable fields based on column properties</li>
                        <li>• Detects foreign key relationships automatically</li>
                        <li>• Recommends appropriate data type casting</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Smart Code Generation</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Follows Laravel best practices and conventions</li>
                        <li>• Generates production-ready code with proper formatting</li>
                        <li>• Includes comprehensive docblocks and comments</li>
                        <li>• Handles edge cases and validation requirements</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Generator Services -->
        <div class="bg-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Generator Services</h2>
            <p class="text-gray-600 mb-6">Each generator is powered by dedicated service classes:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-3 rounded border">
                    <code class="text-sm text-blue-600">ModelGeneratorService</code>
                    <p class="text-xs text-gray-500 mt-1">Handles Eloquent model generation with relationships</p>
                </div>
                <div class="bg-white p-3 rounded border">
                    <code class="text-sm text-blue-600">IntelligentSuggestionService</code>
                    <p class="text-xs text-gray-500 mt-1">Provides intelligent analysis and suggestions</p>
                </div>
                <div class="bg-white p-3 rounded border">
                    <code class="text-sm text-blue-600">DataGenerationService</code>
                    <p class="text-xs text-gray-500 mt-1">Powers factory and seeder generation</p>
                </div>
                <div class="bg-white p-3 rounded border">
                    <code class="text-sm text-blue-600">DocumentationGenerationService</code>
                    <p class="text-xs text-gray-500 mt-1">Handles documentation generation tasks</p>
                </div>
            </div>
        </div>
    </div>
@endsection