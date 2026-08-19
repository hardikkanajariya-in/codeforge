@extends('codeforge-studio::layout.docs')

@section('title', 'Overview - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Overview</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Architecture Overview</h1>
                    <p class="text-xl text-gray-600">Comprehensive overview of CodeForge Database Studio's modular
                        architecture and design patterns</p>
                </div>
            </div>
        </div>

        <!-- Overview -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Plugin Overview</h2>
            <p class="text-gray-600 mb-6">
                CodeForge Database Studio is a comprehensive Laravel/Filament plugin that provides advanced database
                management capabilities. Built with a modular architecture, it offers feature toggles, service-based design,
                and seamless integration with existing Laravel applications.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded-lg border border-indigo-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Modular Design</h3>
                    </div>
                    <p class="text-sm text-gray-600">Feature-based architecture with individual enable/disable toggles</p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Service Layer</h3>
                    </div>
                    <p class="text-sm text-gray-600">Comprehensive service layer with dependency injection and singletons
                    </p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Filament Integration</h3>
                    </div>
                    <p class="text-sm text-gray-600">Native Filament v4/v5 integration with pages, resources, and widgets</p>
                </div>
            </div>
        </div>

        <!-- Core Components -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Core Components</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-indigo-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Plugin Class</h3>
                    <p class="text-gray-600 mb-3">Central plugin configuration implementing Filament's Plugin contract.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <code class="text-blue-600">CodeForgeStudioPlugin</code> - Main plugin class with feature
                            toggles</li>
                        <li>• Feature enable/disable methods for granular control</li>
                        <li>• Dynamic page and resource registration based on configuration</li>
                        <li>• Navigation group and sorting customization</li>
                    </ul>
                </div>

                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Service Provider</h3>
                    <p class="text-gray-600 mb-3">Laravel service provider handling dependency injection and service
                        registration.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <code class="text-blue-600">CodeForgeStudioServiceProvider</code> - Service registration and
                            binding</li>
                        <li>• Singleton service bindings for performance optimization</li>
                        <li>• Configuration publishing and merging</li>
                        <li>• Package auto-discovery configuration</li>
                    </ul>
                </div>

                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Feature Architecture</h3>
                    <p class="text-gray-600 mb-3">Modular feature system with individual toggle capabilities.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Database Overview Dashboard - Central monitoring hub</li>
                        <li>• Schema Designer - Visual database design tools</li>
                        <li>• Migration Manager - Advanced migration control</li>
                        <li>• Health Monitoring - Performance tracking and alerts</li>
                        <li>• Code Generation Suite - Automated code generation</li>
                        <li>• Smart Data Seeding - Intelligent test data creation</li>
                        <li>• Documentation Generator - Multi-format documentation</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Plugin Structure -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Plugin Structure</h2>
            <p class="text-gray-600 mb-6">Understanding the plugin's internal structure and organization:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Pages & Resources</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Filament pages for feature interfaces</li>
                        <li>• CRUD resources for data management</li>
                        <li>• Widget components for dashboards</li>
                        <li>• Navigation organization and grouping</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Models & Migrations</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Eloquent models for data persistence</li>
                        <li>• Database migrations for schema management</li>
                        <li>• Relationship definitions and constraints</li>
                        <li>• Model observers and event listeners</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Services & Utilities</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Business logic encapsulation</li>
                        <li>• Code generation engines</li>
                        <li>• Database analysis tools</li>
                        <li>• Performance monitoring utilities</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Configuration & Assets</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Configuration file management</li>
                        <li>• View templates and layouts</li>
                        <li>• Language files for internationalization</li>
                        <li>• Route definitions and controllers</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Feature Toggle System -->
        <div class="bg-gray-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Feature Toggle System</h2>
            <p class="text-gray-600 mb-6">Granular control over plugin features with enable/disable functionality:</p>

            <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>// Plugin configuration example
    CodeForgeStudioPlugin::make()
        ->enableSchemaDesigner(true)           // Visual schema design
        ->enableMigrationManager(true)         // Advanced migration control
        ->enableHealthMonitoring(true)         // Performance monitoring
        ->enableSmartSeeding(true)             // Intelligent data generation
        ->enableDocumentationGenerator(true)   // Multi-format documentation
        ->enableCodeGeneration(true)           // Code generation suite
        ->enableDevDocs(false)                 // Development documentation</code></pre>
        </div>

        <!-- Architecture Principles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Architecture Principles</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio is built on solid architectural principles to ensure
                scalability, maintainability, and performance.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Modular Design</h3>
                    <p class="text-sm text-gray-600">Service-oriented architecture with clear separation of concerns</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">High Performance</h3>
                    <p class="text-sm text-gray-600">Optimized for speed with intelligent caching and lazy loading</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Security First</h3>
                    <p class="text-sm text-gray-600">Enterprise-grade security with comprehensive validation</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1V1a1 1 0 011-1h2a1 1 0 011 1v3">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Laravel Native</h3>
                    <p class="text-sm text-gray-600">Built specifically for Laravel with native integration</p>
                </div>
            </div>
        </div>

        <!-- Core Components -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Core Components</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Service Layer</h3>
                    <ul class="space-y-2 text-blue-800 text-sm">
                        <li>� <strong>DatabaseHealthService:</strong> Real-time health monitoring and performance tracking
                        </li>
                        <li>� <strong>SchemaAnalyzerService:</strong> Relationship discovery and performance analysis</li>
                        <li>� <strong>DataGenerationService:</strong> Intelligent data seeding with relationship awareness
                        </li>
                        <li>� <strong>CodeGenerationService:</strong> Laravel component generation with dependency
                            management</li>
                        <li>� <strong>MigrationTrackingService:</strong> Migration execution monitoring and history</li>
                        <li>� <strong>SchemaDocumentationService:</strong> Automated documentation generation</li>
                    </ul>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-green-900 mb-4">Data Layer</h3>
                    <ul class="space-y-2 text-green-800 text-sm">
                        <li>� <strong>DatabaseManagerLogs:</strong> Comprehensive database operation logging</li>
                        <li>� <strong>MigrationHistories:</strong> Detailed migration execution tracking</li>
                        <li>� <strong>QueryPerformanceLogs:</strong> Query performance metrics and analysis</li>
                        <li>� <strong>DatabaseHealthMetrics:</strong> Real-time health scoring and alerts</li>
                        <li>� <strong>DataSeeders:</strong> Smart seeding configuration and templates</li>
                        <li>� <strong>SchemaSnapshots:</strong> Version-controlled schema documentation</li>
                    </ul>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg border border-purple-200">
                    <h3 class="text-lg font-semibold text-purple-900 mb-4">User Interface</h3>
                    <ul class="space-y-2 text-purple-800 text-sm">
                        <li>� <strong>Filament Integration:</strong> Native Filament admin panel components</li>
                        <li>� <strong>Interactive Dashboards:</strong> Real-time monitoring and analytics</li>
                        <li>� <strong>Resource Management:</strong> CRUD operations with advanced filtering</li>
                        <li>� <strong>Visual Analytics:</strong> Charts, graphs, and performance visualizations</li>
                        <li>� <strong>Configuration Management:</strong> Dynamic feature configuration interface</li>
                        <li>� <strong>Documentation Viewer:</strong> Integrated documentation browsing</li>
                    </ul>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-lg border border-orange-200">
                    <h3 class="text-lg font-semibold text-orange-900 mb-4">Integration Layer</h3>
                    <ul class="space-y-2 text-orange-800 text-sm">
                        <li>� <strong>Laravel Commands:</strong> Artisan command integration for CLI operations</li>
                        <li>� <strong>Event System:</strong> Laravel event broadcasting and listening</li>
                        <li>� <strong>Queue Integration:</strong> Background job processing for heavy operations</li>
                        <li>� <strong>Cache Management:</strong> Intelligent caching with Redis/Memcached support</li>
                        <li>� <strong>File System:</strong> Multi-disk file storage and management</li>
                        <li>� <strong>API Endpoints:</strong> RESTful API for external integrations</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Design Patterns -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Design Patterns</h2>
            <p class="text-gray-600 mb-6">CodeForge Database Studio implements proven design patterns for maintainable and
                scalable code:</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Repository Pattern</h4>
                    <p class="text-sm text-gray-700">Abstraction layer for data access with consistent interfaces</p>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Service Pattern</h4>
                    <p class="text-sm text-gray-700">Business logic encapsulation with dependency injection</p>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Observer Pattern</h4>
                    <p class="text-sm text-gray-700">Event-driven architecture with Laravel's event system</p>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Factory Pattern</h4>
                    <p class="text-sm text-gray-700">Dynamic object creation with configuration-based instantiation</p>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Strategy Pattern</h4>
                    <p class="text-sm text-gray-700">Pluggable algorithms for different database engines</p>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Command Pattern</h4>
                    <p class="text-sm text-gray-700">Encapsulated operations with undo/redo capabilities</p>
                </div>
            </div>
        </div>

        <!-- Technology Stack -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Technology Stack</h2>
            <p class="text-gray-600 mb-6">Built on modern technologies for reliability and performance:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Framework</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>• Laravel ^12.0|^13.0</li>
                        <li>• Filament ^4.0|^5.0</li>
                        <li>• PHP ^8.3</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Database</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>� MySQL 5.7+</li>
                        <li>� PostgreSQL 12+</li>
                        <li>� SQLite 3.8+</li>
                        <li>� SQL Server 2017+</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Frontend</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>� Livewire ^3.0</li>
                        <li>� Alpine.js</li>
                        <li>� Tailwind CSS</li>
                        <li>� Chart.js</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Tools</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>� Redis/Memcached</li>
                        <li>� Queue Workers</li>
                        <li>� PHPUnit Testing</li>
                        <li>� Composer</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Architecture Benefits -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Architecture Benefits</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <h4 class="font-semibold text-green-800 text-lg mb-2">Scalability</h4>
                    <p class="text-green-700">Modular design allows for horizontal scaling and independent component
                        development.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <h4 class="font-semibold text-blue-800 text-lg mb-2">Maintainability</h4>
                    <p class="text-blue-700">Clear separation of concerns and consistent patterns make the codebase easy to
                        maintain.</p>
                </div>
                <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                    <h4 class="font-semibold text-purple-800 text-lg mb-2">Extensibility</h4>
                    <p class="text-purple-700">Plugin architecture allows for easy extension and customization of
                        functionality.</p>
                </div>
                <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                    <h4 class="font-semibold text-orange-800 text-lg mb-2">Testability</h4>
                    <p class="text-orange-700">Dependency injection and service patterns enable comprehensive unit and
                        integration testing.</p>
                </div>
            </div>
        </div>
    </div>
@endsection