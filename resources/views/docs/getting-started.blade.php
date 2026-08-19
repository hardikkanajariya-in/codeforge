@extends('codeforge-studio::layout.docs')

@section('title', 'Getting Started - CodeForge Database Studio')
@section('description', 'Learn how to install and configure CodeForge Database Studio for your Laravel project. Complete guide for developers.')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('codeforge.docs.home') }}" class="text-gray-500 hover:text-primary-600">Documentation</a>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-primary-600 font-medium">Getting Started</li>
@endsection

@section('navigation')
    <div class="space-y-6">
        <!-- Getting Started Section -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Getting Started</h3>
            <div class="space-y-1">
                <a href="{{ route('codeforge.docs.getting-started') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 border-r-2 border-primary-600 rounded-l">
                    🚀 Quick Start
                </a>
                <a href="{{ route('codeforge.docs.installation') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    📦 Installation
                </a>
                <a href="{{ route('codeforge.docs.configuration') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    ⚙️ Configuration
                </a>
                <a href="{{ route('codeforge.docs.requirements') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    📋 Requirements
                </a>
            </div>
        </div>

        <!-- Features Section -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Core Features</h3>
            <div class="space-y-1">
                <a href="{{ route('codeforge.docs.features.overview') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    🎯 Features Overview
                </a>
                <a href="{{ route('codeforge.docs.features.database-health') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    💖 Database Health
                </a>
                <a href="{{ route('codeforge.docs.features.migration-management') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    🔄 Migration Management
                </a>
                <a href="{{ route('codeforge.docs.features.schema-designer') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    🎨 Schema Designer
                </a>
                <a href="{{ route('codeforge.docs.features.code-generation') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    ⚡ Code Generation
                </a>
                <a href="{{ route('codeforge.docs.features.data-seeding') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    🌱 Data Seeding
                </a>
            </div>
        </div>

        <!-- Architecture Section -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Architecture</h3>
            <div class="space-y-1">
                <a href="{{ route('codeforge.docs.architecture.overview') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    🏗️ Overview
                </a>
                <a href="{{ route('codeforge.docs.architecture.services') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    🔧 Services
                </a>
                <a href="{{ route('codeforge.docs.architecture.events') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    📡 Events
                </a>
            </div>
        </div>

        <!-- API Reference Section -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">API Reference</h3>
            <div class="space-y-1">
                <a href="{{ route('codeforge.docs.api.overview') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    📚 API Overview
                </a>
                <a href="{{ route('codeforge.docs.api.services') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    🛠️ Services API
                </a>
                <a href="{{ route('codeforge.docs.api.commands') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    ⌨️ Artisan Commands
                </a>
            </div>
        </div>

        <!-- Support Section -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Help & Support</h3>
            <div class="space-y-1">
                <a href="{{ route('codeforge.docs.troubleshooting') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    🐛 Troubleshooting
                </a>
                <a href="{{ route('codeforge.docs.faq') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    ❓ FAQ
                </a>
                <a href="{{ route('codeforge.docs.support') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
                    💬 Get Support
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-primary-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Getting Started</h1>
                    <p class="text-xl text-gray-600">Learn how to install and configure CodeForge Database Studio for your
                        Laravel project</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-primary-50 to-purple-50 rounded-lg p-6 border border-primary-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Setup Time</h3>
                            <p class="text-2xl font-bold text-primary-600">5 mins</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6 border border-green-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Commands</h3>
                            <p class="text-2xl font-bold text-green-600">3 steps</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17v4a2 2 0 002 2h4M13 13h3a2 2 0 012 2v3">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Features</h3>
                            <p class="text-2xl font-bold text-blue-600">12+</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Start Guide -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 id="quick-installation" class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <span
                    class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center text-sm font-bold mr-3">1</span>
                Quick Installation
            </h2>

            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 mb-6">Get CodeForge Database Studio up and running in your Laravel project in just a
                    few minutes.</p>

                <!-- Prerequisites -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Prerequisites
                    </h3>
                    <ul class="text-blue-800 space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            PHP 8.3 or higher
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Laravel 12.x or 13.x
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            FilamentPHP 3.x
                        </li>
                    </ul>
                </div>

                <!-- Installation Steps -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Step 1: Install via Composer</h4>
                        <pre
                            class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code class="language-bash">composer require hkdevs/codeforge-database-studio</code></pre>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Step 2: Run Installation Command</h4>
                        <pre
                            class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code class="language-bash">php artisan codeforge-database-studio:install</code></pre>
                        <p class="text-sm text-gray-600 mt-2">This command will publish configuration files and run
                            necessary migrations.</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Step 3: Register the Plugin</h4>
                        <p class="text-gray-600 mb-3">Add the plugin to your Filament panel provider:</p>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code class="language-php">&lt;?php
        // app/Providers/Filament/AdminPanelProvider.php

        use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

        public function panel(Panel $panel): Panel
        {
            return $panel
                // ... other configurations
                ->plugins([
                    CodeForgeStudioPlugin::make()
                        ->enableSchemaDesigner()
                        ->enableCodeGeneration(),
                ]);
        }</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- What's Next -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 id="whats-next" class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <span
                    class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center text-sm font-bold mr-3">2</span>
                What's Next?
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('codeforge.docs.features.overview') }}"
                    class="group block p-6 bg-gradient-to-br from-primary-50 to-purple-50 border border-primary-100 rounded-lg hover:shadow-md transition-all duration-200">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary-200 transition-colors">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600">Explore Features</h3>
                    </div>
                    <p class="text-gray-600">Discover all the powerful features that CodeForge Database Studio offers for
                        your development workflow.</p>
                </a>

                <a href="{{ route('codeforge.docs.configuration') }}"
                    class="group block p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-lg hover:shadow-md transition-all duration-200">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">Configuration</h3>
                    </div>
                    <p class="text-gray-600">Customize the plugin settings to match your specific needs and workflow
                        preferences.</p>
                </a>

                <a href="{{ route('codeforge.docs.api.overview') }}"
                    class="group block p-6 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100 rounded-lg hover:shadow-md transition-all duration-200">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600">API Reference</h3>
                    </div>
                    <p class="text-gray-600">Dive deep into the API documentation to understand all available methods and
                        services.</p>
                </a>

                <a href="{{ route('codeforge.docs.architecture.overview') }}"
                    class="group block p-6 bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-100 rounded-lg hover:shadow-md transition-all duration-200">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-yellow-200 transition-colors">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-yellow-600">Architecture</h3>
                    </div>
                    <p class="text-gray-600">Learn about the plugin's architecture and how different components work
                        together.</p>
                </a>
            </div>
        </div>

        <!-- Key Features Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 id="key-features" class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <span
                    class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-bold mr-3">3</span>
                Key Features at a Glance
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-6 border border-gray-200 rounded-lg">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        💖
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Database Health Monitoring</h3>
                    <p class="text-sm text-gray-600">Real-time monitoring of database performance, health metrics, and
                        connection status.</p>
                </div>

                <div class="p-6 border border-gray-200 rounded-lg">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        🔄
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Migration Management</h3>
                    <p class="text-sm text-gray-600">Advanced migration tools with history tracking and safe rollback
                        capabilities.</p>
                </div>

                <div class="p-6 border border-gray-200 rounded-lg">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        🎨
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Schema Designer</h3>
                    <p class="text-sm text-gray-600">Visual database schema design with drag-and-drop interface and
                        relationship mapping.</p>
                </div>

                <div class="p-6 border border-gray-200 rounded-lg">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        ⚡
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Code Generation</h3>
                    <p class="text-sm text-gray-600">Automated generation of models, migrations, factories, and Filament
                        resources.</p>
                </div>

                <div class="p-6 border border-gray-200 rounded-lg">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        🌱
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Smart Data Seeding</h3>
                    <p class="text-sm text-gray-600">Context-aware data generation with relationship handling and custom
                        templates.</p>
                </div>

                <div class="p-6 border border-gray-200 rounded-lg">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        📚
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Documentation Generator</h3>
                    <p class="text-sm text-gray-600">Automated schema documentation with multiple export formats including
                        ERD generation.</p>
                </div>
            </div>
        </div>

        <!-- Need Help? -->
        <div class="bg-gradient-to-r from-primary-50 to-purple-50 border border-primary-200 rounded-xl p-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Need Help Getting Started?</h2>
                <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                    Our comprehensive documentation and support resources are here to help you get the most out of CodeForge
                    Database Studio.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('codeforge.docs.troubleshooting') }}"
                        class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        Troubleshooting Guide
                    </a>
                    <a href="mailto:contact@hardikkanajariya.in"
                        class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-medium rounded-lg border border-primary-600 hover:bg-primary-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
