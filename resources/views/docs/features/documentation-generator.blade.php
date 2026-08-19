@extends('codeforge-studio::layout.docs')

@section('title', 'Documentation Generator - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Documentation Generator</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection


@section('title', 'Documentation Generator')

@section('content')
    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Documentation Generator</h1>
                    <p class="text-xl text-gray-600">Automated documentation generation with multi-format export
                        capabilities</p>
                </div>
            </div>
        </div>

        <!-- Overview -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
            <p class="text-gray-600 mb-6">
                The Documentation Generator provides automated documentation creation for your database schema, API
                endpoints, and application structure. Export Markdown, HTML, or PDF from the same schema analysis pipeline.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Multi-Format</h3>
                    </div>
                    <p class="text-sm text-gray-600">Generate documentation in Markdown, HTML, and PDF formats</p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-emerald-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Schema Analysis</h3>
                    </div>
                    <p class="text-sm text-gray-600">Comprehensive database schema analysis with relationship mapping</p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Automated Analysis</h3>
                    </div>
                    <p class="text-sm text-gray-600">Intelligent analysis of code structure and application architecture</p>
                </div>
            </div>
        </div>

        <!-- Key Features -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Key Features</h2>

            <div class="space-y-6">
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Multi-Format Export</h3>
                    <p class="text-gray-600 mb-3">Generate documentation in multiple formats for different use cases and
                        audiences.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• HTML documentation with interactive navigation</li>
                        <li>• PDF export for offline reading and sharing</li>
                        <li>• Markdown format for version control and GitHub</li>
                        <li>• Markdown, HTML, and PDF export from the Filament UI and <code>codeforge:generate-docs</code></li>
                    </ul>
                </div>

                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Schema Documentation</h3>
                    <p class="text-gray-600 mb-3">Comprehensive database schema analysis with detailed table and
                        relationship documentation.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Complete table structure documentation</li>
                        <li>• Column details with data types and constraints</li>
                        <li>• Index and foreign key relationship mapping</li>
                        <li>• Model relationship analysis and documentation</li>
                    </ul>
                </div>

                <div class="border-l-4 border-purple-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Generation Tracking</h3>
                    <p class="text-gray-600 mb-3">Track documentation generation history with versioning and change
                        detection.</p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Generation history with timestamps and metadata</li>
                        <li>• Format-specific generation tracking</li>
                        <li>• Change detection between documentation versions</li>
                        <li>• Generation statistics and performance metrics</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Dashboard Access -->
        <div class="bg-gradient-to-r from-green-50 to-teal-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Access</h2>
            <p class="text-gray-600 mb-6">Access the Documentation Generator through your Filament admin panel:</p>

            <div class="bg-white p-4 rounded-lg border">
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Navigate to your Filament admin panel</li>
                    <li>Look for the <strong>"Database Tools"</strong> navigation group</li>
                    <li>Click on <strong>"Documentation Generator"</strong></li>
                    <li>Select your documentation type and format preferences</li>
                    <li>Configure generation settings and options</li>
                    <li>Generate and download your documentation</li>
                </ol>
            </div>
        </div>

        <!-- Supported Formats -->
        <div class="bg-gray-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Supported Formats</h2>
            <p class="text-gray-600 mb-6">Generate documentation in multiple formats tailored for different use cases:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">HTML Documentation</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Interactive navigation and search</li>
                        <li>• Responsive design for all devices</li>
                        <li>• Syntax highlighting for code examples</li>
                        <li>• Professional styling and formatting</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">PDF Export</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Professional layout with table of contents</li>
                        <li>• Print-ready formatting and pagination</li>
                        <li>• Embedded diagrams and charts</li>
                        <li>• Bookmarks for easy navigation</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Markdown Format</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Version control friendly format</li>
                        <li>• GitHub and GitLab compatibility</li>
                        <li>• Easy editing and collaboration</li>
                        <li>• Structured content with headers</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">PDF Format</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Printable documentation for stakeholders</li>
                        <li>• Professional layout for sharing</li>
                        <li>• Generated from the same schema analysis pipeline</li>
                        <li>• Suitable for offline reference</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Configuration</h2>
            <p class="text-gray-600 mb-6">Enable documentation generation in your plugin configuration:</p>

            <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>// In your AdminPanelProvider.php
    CodeForgeStudioPlugin::make()
        ->enableDocumentationGenerator(true)  // Enable documentation generation
        // ... other configuration</code></pre>
        </div>

        <h2>Key Features</h2>

        <h3>Comprehensive Schema Documentation</h3>
        <div class="bg-gray-50 p-4 rounded-md">
            <ul>
                <li><strong>Complete Table Documentation:</strong> Detailed table structure with columns, indexes, and
                    constraints</li>
                <li><strong>Multi-Format Export:</strong> Generate documentation in Markdown, HTML, and PDF formats
                </li>
                <li><strong>Generation Tracking:</strong> Track documentation generation history with metadata and
                    statistics</li>
                <li><strong>Formatted output:</strong> Styled HTML and PDF layouts from the same source content</li>
            </ul>
        </div>

        <!-- Generation Services -->
        <div class="bg-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Generation Services</h2>
            <p class="text-gray-600 mb-6">Documentation is generated by:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-3 rounded border">
                    <code class="text-sm text-blue-600">DocumentationGenerationService</code>
                    <p class="text-xs text-gray-500 mt-1">Core documentation generation engine with multi-format support</p>
                </div>
                <div class="bg-white p-3 rounded border">
                    <code class="text-sm text-blue-600">DocumentationGeneration</code>
                    <p class="text-xs text-gray-500 mt-1">Model for tracking generation history and metadata</p>
                </div>
            </div>
        </div>
    </div>
@endsection