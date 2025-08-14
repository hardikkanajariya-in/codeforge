@extends('codeforge-database-studio::layout.docs')

@section('title', 'Features Overview - CodeForge Database Studio')
@section('description', 'Complete overview of all CodeForge Database Studio features including database health monitoring, migration management, and code generation.')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('codeforge.docs.home') }}" class="text-gray-500 hover:text-primary-600">Documentation</a>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="flex items-center">
        <span class="text-gray-500">Features</span>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-primary-600 font-medium">Overview</li>
@endsection

@section('navigation')
    @include('codeforge-database-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Core Features Overview</h1>
                    <p class="text-xl text-gray-600">Comprehensive suite of database management and development tools for
                        Laravel applications</p>
                </div>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                        1</div>
                    <h3 class="ml-3 text-lg font-semibold text-blue-900">Health Monitoring</h3>
                </div>
                <p class="text-blue-800 text-sm mb-4">Real-time database health monitoring with performance metrics,
                    connection tracking, and intelligent alerting.</p>
                <a href="{{ route('codeforge.docs.features.database-health') }}"
                    class="inline-block text-blue-600 hover:text-blue-700 font-medium text-sm">Learn More →</a>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg border border-green-200 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                        2</div>
                    <h3 class="ml-3 text-lg font-semibold text-green-900">Schema Designer</h3>
                </div>
                <p class="text-green-800 text-sm mb-4">Advanced schema analysis with automatic relationship discovery,
                    performance insights, and optimization recommendations.</p>
                <a href="{{ route('codeforge.docs.features.schema-designer') }}"
                    class="inline-block text-green-600 hover:text-green-700 font-medium text-sm">Learn More →</a>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg border border-purple-200 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                        3</div>
                    <h3 class="ml-3 text-lg font-semibold text-purple-900">Smart Data Seeding</h3>
                </div>
                <p class="text-purple-800 text-sm mb-4">Intelligent data generation with relationship awareness, realistic
                    patterns, and customizable templates.</p>
                <a href="{{ route('codeforge.docs.features.data-seeding') }}"
                    class="inline-block text-purple-600 hover:text-purple-700 font-medium text-sm">Learn More →</a>
            </div>

            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-lg border border-orange-200 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                        4</div>
                    <h3 class="ml-3 text-lg font-semibold text-orange-900">Code Generation</h3>
                </div>
                <p class="text-orange-800 text-sm mb-4">Comprehensive Laravel component generation with dependency
                    management and transactional operations.</p>
                <a href="{{ route('codeforge.docs.features.code-generation') }}"
                    class="inline-block text-orange-600 hover:text-orange-700 font-medium text-sm">Learn More →</a>
            </div>

            <div
                class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-lg border border-red-200 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                        5</div>
                    <h3 class="ml-3 text-lg font-semibold text-red-900">Migration Management</h3>
                </div>
                <p class="text-red-800 text-sm mb-4">Advanced migration tracking with performance monitoring, error
                    handling, and team collaboration features.</p>
                <a href="{{ route('codeforge.docs.features.migration-management') }}"
                    class="inline-block text-red-600 hover:text-red-700 font-medium text-sm">Learn More →</a>
            </div>

            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-lg border border-indigo-200 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                        6</div>
                    <h3 class="ml-3 text-lg font-semibold text-indigo-900">Documentation Generator</h3>
                </div>
                <p class="text-indigo-800 text-sm mb-4">Automated schema documentation with versioning, multi-format export,
                    and comprehensive reporting.</p>
                <a href="{{ route('codeforge.docs.features.documentation-generator') }}"
                    class="inline-block text-indigo-600 hover:text-indigo-700 font-medium text-sm">Learn More →</a>
            </div>
        </div>

        <!-- Key Capabilities -->
        <div class="bg-gray-50 p-8 rounded-xl mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Core Capabilities Across All Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">🔧 Configuration-Based</h4>
                    <p class="text-sm text-gray-700">All features can be enabled/disabled via configuration</p>
                </div>
                <div class="bg-white p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">⚡ Performance Optimized</h4>
                    <p class="text-sm text-gray-700">Built for efficiency with intelligent caching and optimization</p>
                </div>
                <div class="bg-white p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">🛡️ Security-First</h4>
                    <p class="text-sm text-gray-700">Enterprise-grade security with confirmation for destructive operations
                    </p>
                </div>
                <div class="bg-white p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">📊 Comprehensive Logging</h4>
                    <p class="text-sm text-gray-700">Detailed logging and monitoring for all operations</p>
                </div>
                <div class="bg-white p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">🎯 Laravel Integration</h4>
                    <p class="text-sm text-gray-700">Seamless integration with Laravel's ecosystem</p>
                </div>
                <div class="bg-white p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">🔄 Real-time Updates</h4>
                    <p class="text-sm text-gray-700">Live updates and real-time monitoring capabilities</p>
                </div>
            </div>
        </div>

        <!-- Quick Start -->
        <div class="bg-green-50 p-8 rounded-xl mb-12">
            <h2 class="text-2xl font-bold text-green-900 mb-6">Quick Start Steps</h2>
            <div class="space-y-4">
                <div class="flex items-start">
                    <span
                        class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">1</span>
                    <div>
                        <h4 class="font-semibold text-green-900">Configure Features</h4>
                        <p class="text-sm text-green-700">Enable the features you need in your configuration file</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span
                        class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">2</span>
                    <div>
                        <h4 class="font-semibold text-green-900">Run Initial Setup</h4>
                        <p class="text-sm text-green-700">Execute the setup commands to initialize the plugin</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span
                        class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">3</span>
                    <div>
                        <h4 class="font-semibold text-green-900">Access the Dashboard</h4>
                        <p class="text-sm text-green-700">Navigate to the CodeForge panel in your Filament admin</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Example -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Feature Configuration</h2>
            <p class="text-gray-600 mb-6">All features can be individually configured in your <code
                    class="bg-gray-100 px-2 py-1 rounded">config/codeforge-database-studio.php</code> file:</p>
            <pre class="bg-gray-800 text-white p-4 rounded-md overflow-x-auto"><code>'features' => [
                'health_monitoring' => true,
                'schema_designer' => true,
                'data_seeding' => true,
                'code_generation' => true,
                'migration_management' => true,
                'documentation_generator' => true,
            ],</code></pre>
        </div>

        <!-- Support -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6">
            <h3 class="text-lg font-semibold text-yellow-900 mb-2">Professional Support</h3>
            <p class="text-yellow-800">
                CodeForge Database Studio includes professional support with your commercial license.
                Contact us at <a href="mailto:contact@hardikkanajariya.in"
                    class="text-yellow-600 hover:text-yellow-700 font-medium">contact@hardikkanajariya.in</a>
                for assistance with implementation, customization, or troubleshooting.
            </p>
        </div>
    </div>
@endsection