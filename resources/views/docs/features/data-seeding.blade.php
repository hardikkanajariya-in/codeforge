@extends('codeforge-studio::layout.docs')

@section('title', 'Data Seeding - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Data Seeding</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection


@section('title', 'Smart Data Seeding')

@section('content')
    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Smart Data Seeding</h1>
                    <p class="text-xl text-gray-600">Intelligent data generation with relationship awareness and realistic
                        patterns</p>
                </div>
            </div>
        </div>

        <!-- Overview -->
        <div class="bg-gradient-to-r from-purple-50 to-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
            <p class="text-gray-600 mb-6">
                The Smart Data Seeding feature provides intelligent data generation for your database tables. Using
                template-based generation and relationship analysis, it creates realistic test data that maintains
                referential integrity and follows natural patterns.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Template-Based</h3>
                    </div>
                    <p class="text-sm text-gray-600">Customizable data generation templates with field-specific patterns and
                        rules</p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Relationship-Aware</h3>
                    </div>
                    <p class="text-sm text-gray-600">Automatically analyzes table relationships and maintains referential
                        integrity</p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Intelligent Patterns</h3>
                    </div>
                    <p class="text-sm text-gray-600">Generates realistic data patterns based on field names and data types
                    </p>
                </div>
            </div>
        </div>

        <!-- Key Features -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Key Features</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Template Management</h3>
                    <p class="text-gray-600 mb-3">Create and manage data generation templates with customizable field
                        patterns and generation rules.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Template-based data generation with reusable patterns</li>
                        <li>• Field-specific generation rules and constraints</li>
                        <li>• Custom data patterns for realistic test scenarios</li>
                        <li>• Template sharing and import/export capabilities</li>
                    </ul>
                </div>

                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Relationship Analysis</h3>
                    <p class="text-gray-600 mb-3">Automatically analyzes database relationships to maintain data integrity
                        during generation.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Foreign key relationship detection and handling</li>
                        <li>• Parent-child data generation ordering</li>
                        <li>• Referential integrity maintenance</li>
                        <li>• Cross-table dependency management</li>
                    </ul>
                </div>

                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Smart Data Patterns</h3>
                    <p class="text-gray-600 mb-3">Intelligent data generation based on field names, types, and contextual
                        analysis.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Field name-based pattern recognition (email, phone, name, etc.)</li>
                        <li>• Data type-aware generation (strings, numbers, dates, booleans)</li>
                        <li>• Locale-specific data generation</li>
                        <li>• Realistic data distribution and variance</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Dashboard Access -->
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Access</h2>
            <p class="text-gray-600 mb-6">Access the Smart Data Seeder through your Filament admin panel:</p>

            <div class="bg-white p-4 rounded-lg border">
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Navigate to your Filament admin panel</li>
                    <li>Look for the <strong>"Database Tools"</strong> navigation group</li>
                    <li>Click on <strong>"Smart Data Seeder"</strong></li>
                    <li>Select your target table and configure generation parameters</li>
                    <li>Create or select a data generation template</li>
                    <li>Execute the seeding operation</li>
                </ol>
            </div>
        </div>

        <!-- Template System -->
        <div class="bg-gray-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Generation Templates</h2>
            <p class="text-gray-600 mb-6">Templates define how data should be generated for specific tables and use cases:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Template Structure</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Table-specific field definitions</li>
                        <li>• Generation rules and constraints</li>
                        <li>• Relationship handling instructions</li>
                        <li>• Custom pattern definitions</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Template Features</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Reusable across multiple projects</li>
                        <li>• Version control and history tracking</li>
                        <li>• Import/export capabilities</li>
                        <li>• Community template sharing</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Configuration</h2>
            <p class="text-gray-600 mb-6">Enable smart data seeding in your plugin configuration:</p>

            <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>// In your AdminPanelProvider.php
    CodeForgeStudioPlugin::make()
        ->enableSmartDataSeeding(true)  // Enable smart data seeding
        // ... other configuration</code></pre>
        </div>

        <h3>Intelligent Data Generation</h3>
        <div class="bg-gray-50 p-4 rounded-md">
            <ul>
                <li><strong>Template-Based:</strong> Uses customizable templates for data generation</li>
                <li><strong>Realistic Patterns:</strong> Generates realistic data based on field names and types</li>
                <li><strong>Performance Optimized:</strong> Efficient generation for large datasets</li>
            </ul>
        </div>

        <!-- Data Generation Services -->
        <div class="bg-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Generation Services</h2>
            <p class="text-gray-600 mb-6">The seeding system is powered by specialized services:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-3 rounded border">
                    <code class="text-sm text-blue-600">DataGenerationService</code>
                    <p class="text-xs text-gray-500 mt-1">Core data generation engine with pattern recognition</p>
                </div>
                <div class="bg-white p-3 rounded border">
                    <code class="text-sm text-blue-600">DataGenerationTemplate</code>
                    <p class="text-xs text-gray-500 mt-1">Template model for storing generation patterns</p>
                </div>
            </div>
        </div>
    </div>
@endsection