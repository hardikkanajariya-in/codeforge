@extends('codeforge-studio::layout.docs')

@section('title', 'CodeForge Database Studio Documentation')
@section('description', 'Comprehensive documentation for CodeForge Database Studio - Advanced Laravel database management and code generation suite.')

@section('breadcrumbs')
    <li class="text-primary-600 font-medium">Documentation</li>
@endsection

@section('navigation')
    <div class="space-y-6">
        <!-- Getting Started Section -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Getting Started</h3>
            <div class="space-y-1">
                <a href="{{ route('codeforge.docs.getting-started') }}"
                    class="flex items-center px-3 py-2 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded">
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
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <div
                class="mx-auto w-24 h-24 bg-gradient-to-br from-primary-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-2xl mb-8">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 7v10c0 2.21 1.79 4 4 4h8c2.21 0 4-1.79 4-4V7c0-2.21-1.79-4-4-4H8c-2.21 0-4 1.79-4 4z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6v6H9z"></path>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-gray-900 mb-6">
                CodeForge Database Studio
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                A comprehensive database management and code generation suite for Laravel applications using FilamentPHP.
                Build, manage, and optimize your database with advanced tools and intelligent automation.
            </p>

            <!-- Version Badge -->
            <div class="flex items-center justify-center space-x-4 mb-8">
                <span
                    class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Version 1.0
                </span>
                <span
                    class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Open Source · MIT License
                </span>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('codeforge.docs.getting-started') }}"
                    class="inline-flex items-center px-8 py-4 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Get Started
                </a>
                <a href="{{ route('codeforge.docs.features.overview') }}"
                    class="inline-flex items-center px-8 py-4 bg-white text-primary-600 font-semibold rounded-lg border-2 border-primary-600 hover:bg-primary-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                        </path>
                    </svg>
                    Explore Features
                </a>
            </div>
        </div>

        <!-- Core Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-red-100 to-pink-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-3xl">💖</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Database Overview</h3>
                <p class="text-gray-600 mb-4">Centralized dashboard with database connection status, table overview, and comprehensive system monitoring.</p>
                <a href="{{ route('codeforge.docs.features.database-health') }}"
                    class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    Learn more
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-3xl">🔄</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Migration Manager</h3>
                <p class="text-gray-600 mb-4">Complete migration control with individual execution, batch operations, status tracking, and safe rollback capabilities.</p>
                <a href="{{ route('codeforge.docs.features.migration-management') }}"
                    class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    Learn more
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-3xl">🎨</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Visual Schema Designer</h3>
                <p class="text-gray-600 mb-4">Interactive database schema visualization with table views, relationship mapping, dependency tracking, and performance analysis.</p>
                <a href="{{ route('codeforge.docs.features.schema-designer') }}"
                    class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    Learn more
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-3xl">⚡</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Code Generation Suite</h3>
                <p class="text-gray-600 mb-4">Comprehensive Laravel component generation including models, migrations, factories, seeders, and Filament resources with intelligent templates.</p>
                <a href="{{ route('codeforge.docs.features.code-generation') }}"
                    class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    Learn more
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-3xl">🌱</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Data Seeding</h3>
                <p class="text-gray-600 mb-4">Intelligent data generation with relationship awareness, customizable templates, and execution tracking for realistic test data.</p>
                <a href="{{ route('codeforge.docs.features.data-seeding') }}"
                    class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    Learn more
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-shadow">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-3xl">📚</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Database Health</h3>
                <p class="text-gray-600 mb-4">Real-time performance monitoring with health metrics, query analysis, and comprehensive connection tracking dashboard.</p>
                <a href="{{ route('codeforge.docs.features.database-health') }}"
                    class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    Learn more
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Future Features Note -->
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-8 mb-16">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-amber-800 mb-2">Development in Progress</h3>
                    <p class="text-amber-700 mb-4">
                        CodeForge Database Studio is actively being developed. Additional features like the Documentation Generator are currently in development and will be available in future updates.
                    </p>
                    <p class="text-amber-700 text-sm">
                        <strong>Note:</strong> All features shown above are currently implemented and available in your installation.
                    </p>
                </div>
            </div>
        </div>

        <!-- Getting Started Section -->
        <div class="bg-gradient-to-r from-primary-50 to-purple-50 rounded-2xl border border-primary-200 p-12 mb-16">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Follow our comprehensive installation guide and start enhancing your Laravel development workflow in
                    minutes.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('codeforge.docs.installation') }}"
                        class="inline-flex items-center px-8 py-4 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Installation Guide
                    </a>
                    <a href="{{ route('codeforge.docs.api.overview') }}"
                        class="inline-flex items-center px-8 py-4 bg-white text-primary-600 font-semibold rounded-lg border border-primary-600 hover:bg-primary-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        API Reference
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-16">
            <div class="text-center">
                <div class="text-4xl font-bold text-primary-600 mb-2">12+</div>
                <div class="text-gray-600 font-medium">Core Features</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-green-600 mb-2">200+</div>
                <div class="text-gray-600 font-medium">Test Cases</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-blue-600 mb-2">24/7</div>
                <div class="text-gray-600 font-medium">Monitoring</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-purple-600 mb-2">∞</div>
                <div class="text-gray-600 font-medium">Possibilities</div>
            </div>
        </div>

        <!-- Support Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Need Help or Have Questions?</h2>
                <p class="text-gray-600 mb-6">
                    Our comprehensive documentation and professional support are here to help you succeed.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('codeforge.docs.troubleshooting') }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        Troubleshooting
                    </a>
                    <a href="mailto:contact@hardikkanajariya.in"
                        class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        Contact Support
                    </a>
                    <a href="https://codeforge.hardikkanajariya.in" target="_blank"
                        class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Visit HkDevs
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
