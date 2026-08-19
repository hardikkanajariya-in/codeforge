@extends('codeforge-studio::layout.docs')

@section('title', 'System Requirements - CodeForge Database Studio')
@section('description', 'System requirements and dependencies for CodeForge Database Studio. Check compatibility with your Laravel application.')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('codeforge.docs.home') }}" class="text-gray-500 hover:text-primary-600">Documentation</a>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Requirements</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">System Requirements</h1>
                    <p class="text-xl text-gray-600">Ensure your system meets these requirements before installation</p>
                </div>
            </div>
        </div>

        <!-- Core Requirements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Core Requirements</h2>
                <p class="text-gray-600">Essential components required for CodeForge Database Studio</p>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- PHP Requirements -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">PHP</h3>
                        </div>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                                PHP ^8.3 or higher
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                                PDO extension
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                                JSON extension
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                                Fileinfo extension
                            </li>
                        </ul>
                    </div>

                    <!-- Laravel Requirements -->
                    <div class="bg-red-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Laravel Framework</h3>
                        </div>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-red-600 rounded-full mr-3"></span>
                                Laravel ^12.0 or ^13.0
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-red-600 rounded-full mr-3"></span>
                                Composer for dependency management
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-red-600 rounded-full mr-3"></span>
                                Artisan CLI access
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Dependencies -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Package Dependencies</h2>
                <p class="text-gray-600">Required packages that will be automatically installed</p>
            </div>
            <div class="p-6">
                <div class="grid gap-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Filament</h4>
                                <p class="text-sm text-gray-600">Admin panel framework</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono text-gray-500">^4.0|^5.0</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Doctrine DBAL</h4>
                                <p class="text-sm text-gray-600">Database abstraction layer</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono text-gray-500">^3.8|^4.0</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">DomPDF</h4>
                                <p class="text-sm text-gray-600">PDF generation for documentation</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono text-gray-500">^3.1</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Spatie Laravel Package Tools</h4>
                                <p class="text-sm text-gray-600">Package development utilities</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono text-gray-500">^1.15.0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Support -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Database Support</h2>
                <p class="text-gray-600">Supported database systems and their requirements</p>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">MySQL</h4>
                        <p class="text-sm text-gray-600">5.7+ or 8.0+</p>
                    </div>

                    <div class="text-center p-4 bg-indigo-50 rounded-lg">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">PostgreSQL</h4>
                        <p class="text-sm text-gray-600">11.0+</p>
                    </div>

                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">SQLite</h4>
                        <p class="text-sm text-gray-600">3.8+</p>
                    </div>

                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">SQL Server</h4>
                        <p class="text-sm text-gray-600">2012+</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional Recommendations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Recommended Setup</h2>
                <p class="text-gray-600">Optional but recommended for optimal performance</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 mr-4">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Redis or Memcached</h4>
                            <p class="text-gray-600">For caching database queries and schema information</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 mr-4">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Queue Driver</h4>
                            <p class="text-gray-600">Database, Redis, or SQS for background processing</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 mr-4">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Sufficient Memory</h4>
                            <p class="text-gray-600">Minimum 256MB PHP memory limit for large schema operations</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 mr-4">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">File Permissions</h4>
                            <p class="text-gray-600">Write access to storage, config, and database directories</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Check -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick Compatibility Check</h3>
                    <p class="text-gray-700 mb-4">
                        Run these commands to verify your system meets the requirements:
                    </p>
                    <div class="bg-white rounded-lg p-4 font-mono text-sm">
                        <div class="text-gray-800">
                            <span class="text-blue-600">php</span> --version<br>
                            <span class="text-blue-600">composer</span> --version<br>
                            <span class="text-blue-600">php artisan</span> --version
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
