@extends('codeforge-studio::layout.docs')

@section('title', 'Performance Optimization - CodeForge Database Studio')
@section('description', 'Learn how to optimize CodeForge Database Studio performance for large databases and high-traffic applications.')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('codeforge.docs.home') }}" class="text-gray-500 hover:text-primary-600">Documentation</a>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="flex items-center">
        <span class="text-gray-500">Advanced</span>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-primary-600 font-medium">Performance</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Performance Optimization</h1>
                    <p class="text-xl text-gray-600">Optimize CodeForge Database Studio for maximum performance and
                        scalability</p>
                </div>
            </div>
        </div>

        <!-- Performance Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Considerations</h2>
            <p class="text-gray-600 mb-6">
                CodeForge Database Studio is designed for high performance, but proper configuration and best practices
                can significantly improve response times and resource usage.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Caching</h3>
                    <p class="text-blue-700 text-sm mb-4">Intelligent caching reduces database queries and improves response
                        times.</p>
                    <div class="space-y-2">
                        <div class="text-sm text-blue-600">• Schema Caching</div>
                        <div class="text-sm text-blue-600">• Query Result Caching</div>
                        <div class="text-sm text-blue-600">• Metadata Caching</div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-green-900 mb-3">Background Processing</h3>
                    <p class="text-green-700 text-sm mb-4">Heavy operations run in the background for better user
                        experience.</p>
                    <div class="space-y-2">
                        <div class="text-sm text-green-600">• Queue Processing</div>
                        <div class="text-sm text-green-600">• Batch Operations</div>
                        <div class="text-sm text-green-600">• Async Tasks</div>
                    </div>
                </div>

                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-purple-900 mb-3">Resource Management</h3>
                    <p class="text-purple-700 text-sm mb-4">Efficient memory and CPU usage for large datasets.</p>
                    <div class="space-y-2">
                        <div class="text-sm text-purple-600">• Memory Optimization</div>
                        <div class="text-sm text-purple-600">• Lazy Loading</div>
                        <div class="text-sm text-purple-600">• Connection Pooling</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Tips -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Configuration Optimizations</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cache Configuration</h3>
                    <p class="text-gray-600 mb-4">Configure caching for optimal performance:</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                            // config/codeforge-database-studio.php<br>
                            'cache' => [<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;'enabled' => true,<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;'ttl' => 3600, // 1 hour<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;'driver' => 'redis', // or 'file'<br>
                            ],
                        </div>
                    </div>
                </div>

                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Database Connection</h3>
                    <p class="text-gray-600 mb-4">Optimize database connections:</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                            'database' => [<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;'timeout' => 30,<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;'max_connections' => 10,<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;'chunk_size' => 1000,<br>
                            ],
                        </div>
                    </div>
                </div>

                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Background Processing</h3>
                    <p class="text-gray-600 mb-4">Enable queue processing for heavy operations:</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                            'queue' => [<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;'enabled' => true,<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;'connection' => 'redis',<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;'queue' => 'codeforge',<br>
                            ],
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Monitoring -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Monitoring</h2>
            <p class="text-gray-600 mb-6">
                Monitor your CodeForge Database Studio performance with built-in metrics and external tools.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Built-in Metrics</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Query execution times
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Memory usage tracking
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Cache hit rates
                        </li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">External Tools</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Laravel Telescope
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            New Relic
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Application Performance Monitoring
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-gradient-to-r from-primary-50 to-indigo-50 border border-primary-200 rounded-xl p-8">
            <h2 class="text-2xl font-bold text-primary-900 mb-4">Next Steps</h2>
            <p class="text-primary-700 mb-6">Continue optimizing your CodeForge Database Studio implementation:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('codeforge.docs.advanced.deployment') }}"
                    class="flex items-center p-4 bg-white rounded-lg border border-primary-200 hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-primary-900">Deployment Guide</h3>
                        <p class="text-primary-600 text-sm">Deploy to production</p>
                    </div>
                </a>
                <a href="{{ route('codeforge.docs.troubleshooting') }}"
                    class="flex items-center p-4 bg-white rounded-lg border border-primary-200 hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-primary-900">Troubleshooting</h3>
                        <p class="text-primary-600 text-sm">Common issues and solutions</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection