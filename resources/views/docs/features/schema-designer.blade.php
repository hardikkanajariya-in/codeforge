@extends('codeforge-studio::layout.docs')

@section('title', 'Schema Designer - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Schema Designer</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection


@section('title', 'Schema Designer & Analysis')

@section('content')
    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Visual Schema Designer</h1>
                    <p class="text-xl text-gray-600">Design database schemas visually with drag-and-drop interface,
                        automatic relationship detection, and instant migration generation</p>
                </div>
            </div>

            <!-- Feature Highlights -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.121 2.122">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-blue-900">Drag & Drop</h3>
                    <p class="text-sm text-blue-700">Intuitive visual interface</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-green-900">Auto-Detection</h3>
                    <p class="text-sm text-green-700">Smart relationship discovery</p>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-purple-900">Code Generation</h3>
                    <p class="text-sm text-purple-700">Instant Laravel migrations</p>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center">
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-orange-900">Real-time Sync</h3>
                    <p class="text-sm text-orange-700">Live database reflection</p>
                </div>
            </div>
        </div>

        <!-- Core Features -->
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Revolutionary Design Experience</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                        </path>
                    </svg>
                    Interactive Canvas
                </h3>
                <ul class="space-y-2 text-gray-700">
                    <li>• Drag-and-drop table design</li>
                    <li>• Visual relationship connections</li>
                    <li>• Zoom, pan, and grid alignment</li>
                    <li>• Multi-mode views (Designer, Tables, Dependencies)</li>
                    <li>• Real-time canvas updates</li>
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    Smart Database Analysis
                </h3>
                <ul class="space-y-2 text-gray-700">
                    <li>• Automatic existing schema import</li>
                    <li>• Intelligent table filtering (excludes system tables)</li>
                    <li>• Foreign key relationship detection</li>
                    <li>• Column type analysis and validation</li>
                    <li>• Database connection management</li>
                </ul>
            </div>
        </div>

        <!-- Live Features Demo -->
        <h2 class="text-3xl font-bold text-gray-900 mb-6">What You Get</h2>

        <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-xl p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Visualize Complex Schemas</h3>
                    <p class="text-blue-700">Transform complex database relationships into clear, understandable visual
                        diagrams</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-900 mb-2">Generate Code Instantly</h3>
                    <p class="text-green-700">Export production-ready Laravel migrations with proper foreign keys and
                        indexes</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-purple-900 mb-2">Version Control</h3>
                    <p class="text-purple-700">Save, load, and manage multiple schema versions with full history tracking
                    </p>
                </div>
            </div>
        </div>
        <!-- Workflow & Features -->
        <h2 class="text-3xl font-bold text-gray-900 mb-6">How It Works</h2>

        <div class="space-y-8">
            <!-- Step 1 -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">1
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Connect & Import</h3>
                        <p class="text-gray-700 mb-4">Automatically connects to your configured database and imports
                            existing schema structure. System tables, framework tables, and plugin tables are intelligently
                            filtered out.</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Smart Filtering:</h4>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>• Excludes system tables (mysql, information_schema, etc.)</li>
                                <li>• Filters Laravel framework tables (migrations, sessions, etc.)</li>
                                <li>• Hides plugin-specific tables automatically</li>
                                <li>• Shows only your application tables</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">2
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Design & Modify</h3>
                        <p class="text-gray-700 mb-4">Use the intuitive visual interface to design new tables, modify
                            existing ones, and create relationships with drag-and-drop simplicity.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-medium text-blue-900 mb-2">Table Designer:</h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Add/edit columns with full type support</li>
                                    <li>• Set nullable, default values, auto-increment</li>
                                    <li>• Configure indexes and unique constraints</li>
                                    <li>• Real-time validation and error checking</li>
                                </ul>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-medium text-green-900 mb-2">Relationship Tools:</h4>
                                <ul class="text-sm text-green-700 space-y-1">
                                    <li>• Drag connections between tables</li>
                                    <li>• Automatic foreign key detection</li>
                                    <li>• Support for one-to-many, many-to-many</li>
                                    <li>• Cascading delete/update options</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                        3</div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Export & Deploy</h3>
                        <p class="text-gray-700 mb-4">Generate production-ready Laravel migrations with a single click. All
                            relationships, constraints, and indexes are properly handled.</p>
                        <div class="bg-gray-900 rounded-lg p-4 mb-4">
                            <div class="text-green-400 text-sm font-mono">
                                <div class="mb-2">📁 Generated Migration File:</div>
                                <div class="text-gray-300">2025_08_21_143052_create_schema_from_designer.php</div>
                                <div class="mt-2 text-yellow-400">✓ Tables created with proper structure</div>
                                <div class="text-yellow-400">✓ Foreign keys and relationships configured</div>
                                <div class="text-yellow-400">✓ Indexes and constraints applied</div>
                                <div class="text-yellow-400">✓ Drop statements included for rollback</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Features -->
        <h2 class="text-3xl font-bold text-gray-900 mb-6 mt-12">Advanced Capabilities</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m9-9h2a2 2 0 012 2v6a2 2 0 01-2 2h-2m-9-9a2 2 0 012-2h2a2 2 0 012 2m-9 9a2 2 0 002 2h2a2 2 0 002-2">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Multiple View Modes</h3>
                <ul class="text-gray-700 space-y-2 text-sm">
                    <li>• <strong>Designer:</strong> Visual schema editing canvas</li>
                    <li>• <strong>Tables:</strong> Detailed table structure view</li>
                    <li>• <strong>Dependencies:</strong> Relationship mapping</li>
                    <li>• <strong>Performance:</strong> Query analysis insights</li>
                    <li>• <strong>Matrix:</strong> Relationship overview grid</li>
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="w-12 h-12 bg-rose-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Version Management</h3>
                <ul class="text-gray-700 space-y-2 text-sm">
                    <li>• Save schema designs with custom names</li>
                    <li>• Load previous versions instantly</li>
                    <li>• Track changes and modifications</li>
                    <li>• User-specific version history</li>
                    <li>• Metadata tracking for team collaboration</li>
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="w-12 h-12 bg-emerald-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Real-time Debugging</h3>
                <ul class="text-gray-700 space-y-2 text-sm">
                    <li>• Live database connection status</li>
                    <li>• Table count and structure validation</li>
                    <li>• Schema refresh and sync tools</li>
                    <li>• Comprehensive error logging</li>
                    <li>• Debug mode for troubleshooting</li>
                </ul>
            </div>
        </div>

        <!-- Business Benefits -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Why Teams Choose Schema Designer</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Save Hours</h3>
                    <p class="text-gray-600 text-sm">Reduce schema design time from hours to minutes with visual tools</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Reduce Errors</h3>
                    <p class="text-gray-600 text-sm">Visual validation prevents relationship and constraint errors</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Team Clarity</h3>
                    <p class="text-gray-600 text-sm">Clear visual documentation improves team understanding</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Faster Deployment</h3>
                    <p class="text-gray-600 text-sm">Instant migration generation accelerates development cycles</p>
                </div>
            </div>
        </div>

        <!-- Technical Specifications -->
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Technical Features</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2.21 3.79 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.79 4 8 4s8-1.79 8-4M4 7c0-2.21 3.79-4 8-4s8 1.79 8 4">
                        </path>
                    </svg>
                    Database Support
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-700">MySQL</span>
                        <span class="text-green-600 font-medium">✓ Full Support</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-700">PostgreSQL</span>
                        <span class="text-green-600 font-medium">✓ Full Support</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-700">SQLite</span>
                        <span class="text-green-600 font-medium">✓ Full Support</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-700">SQL Server</span>
                        <span class="text-green-600 font-medium">✓ Full Support</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                    Framework Integration
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-700">Laravel 10+</span>
                        <span class="text-green-600 font-medium">✓ Optimized</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-700">Filament v4 / v5</span>
                        <span class="text-green-600 font-medium">✓ Native</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-700">Livewire 3</span>
                        <span class="text-green-600 font-medium">✓ Real-time</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-700">Alpine.js</span>
                        <span class="text-green-600 font-medium">✓ Interactive</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection