@extends('codeforge-studio::layout.docs')

@section('title', 'Migration Management - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Migration Manager</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Migration Manager</h1>
                    <p class="text-xl text-gray-600">Complete migration control with individual execution, batch operations,
                        and comprehensive tracking</p>
                </div>
            </div>
        </div>

        <!-- Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
            <p class="text-gray-600 mb-6">
                The Migration Manager provides complete control over Laravel database migrations with individual migration
                execution,
                comprehensive status tracking, batch operations, and safe rollback capabilities. Monitor all migrations from
                a single interface.
            </p>
        </div>

        <!-- Current Implementation -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Current Implementation</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Migration Discovery & Status</h3>
                    <ul class="space-y-2 text-blue-800 text-sm">
                        <li><strong>File System Scanning:</strong> Automatically discovers all migration files from
                            database/migrations</li>
                        <li><strong>Status Determination:</strong> Checks migrations table to determine executed vs pending
                            status</li>
                        <li><strong>Real-time Counts:</strong> Displays pending, executed, and total migration counts</li>
                        <li><strong>File Existence Validation:</strong> Validates migration files exist before execution
                        </li>
                        <li><strong>Batch Information:</strong> Shows batch numbers and execution order</li>
                    </ul>
                </div>

                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-green-900 mb-4">Individual Migration Control</h3>
                    <ul class="space-y-2 text-green-800 text-sm">
                        <li><strong>Single Migration Execution:</strong> Run individual migrations with confirmation</li>
                        <li><strong>Temporary Migration Isolation:</strong> Uses temporary directory for safe execution</li>
                        <li><strong>File Copy Protection:</strong> Creates safe copies before execution</li>
                        <li><strong>Status Validation:</strong> Prevents re-execution of already completed migrations</li>
                        <li><strong>Error Handling:</strong> Comprehensive error reporting with detailed feedback</li>
                    </ul>
                </div>

                <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                    <h3 class="text-lg font-semibold text-purple-900 mb-4">Batch Operations</h3>
                    <ul class="space-y-2 text-purple-800 text-sm">
                        <li><strong>Run All Pending:</strong> Execute all pending migrations in correct order</li>
                        <li><strong>Rollback Operations:</strong> Rollback last migration batch with confirmation</li>
                        <li><strong>Fresh Migration:</strong> Reset and re-run all migrations with confirmation</li>
                        <li><strong>Artisan Integration:</strong> Uses Laravel's built-in migration commands</li>
                        <li><strong>Force Mode Support:</strong> Force execution in production environments</li>
                    </ul>
                </div>

                <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                    <h3 class="text-lg font-semibold text-orange-900 mb-4">Safety Features</h3>
                    <ul class="space-y-2 text-orange-800 text-sm">
                        <li><strong>Confirmation Dialogs:</strong> Requires confirmation for destructive operations</li>
                        <li><strong>Error Logging:</strong> Detailed logging of all migration operations</li>
                        <li><strong>File Validation:</strong> Checks file existence before attempting execution</li>
                        <li><strong>Status Verification:</strong> Verifies migration status before and after execution</li>
                        <li><strong>Temporary Directory Usage:</strong> Isolated execution environment for safety</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Migration Actions -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Actions</h2>
            <p class="text-gray-600 mb-6">The Migration Manager provides several action buttons for migration control:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Individual Migration Actions</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>Run Migration:</strong> Execute single pending migration</li>
                        <li>• <strong>Status Check:</strong> Verify current migration status</li>
                        <li>• <strong>File Validation:</strong> Confirm migration file exists</li>
                        <li>• <strong>Error Feedback:</strong> Detailed error reporting</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Batch Actions</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>Run All:</strong> Execute all pending migrations</li>
                        <li>• <strong>Rollback:</strong> Rollback last migration batch</li>
                        <li>• <strong>Fresh:</strong> Reset and re-run all migrations</li>
                        <li>• <strong>Refresh:</strong> Reload migration status</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Dashboard Access -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Access</h2>
            <p class="text-gray-600 mb-6">Access the Migration Manager through your Filament admin panel:</p>

            <div class="bg-white p-4 rounded-lg border">
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Navigate to your Filament admin panel</li>
                    <li>Look for the <strong>"Database Tools"</strong> navigation group</li>
                    <li>Click on <strong>"Migration Manager"</strong></li>
                    <li>View all migrations with their current status</li>
                    <li>Use individual or batch action buttons to manage migrations</li>
                </ol>
            </div>
        </div>

        <!-- Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Configuration</h2>
            <p class="text-gray-600 mb-6">Enable migration management in your plugin configuration:</p>

            <pre class="bg-gray-800 text-white p-6 rounded-lg overflow-x-auto"><code>// In your AdminPanelProvider.php
    CodeForgeStudioPlugin::make()
        ->enableMigrationManager(true)  // Enable migration management features
        // ... other configuration</code></pre>
        </div>

        <!-- Safety Considerations -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 15.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-amber-800 mb-2">Safety Considerations</h3>
                    <ul class="text-amber-700 space-y-1 text-sm">
                        <li>• Always backup your database before running batch operations</li>
                        <li>• Test migrations in development environment first</li>
                        <li>• Rollback operations can cause data loss - confirm before proceeding</li>
                        <li>• Fresh migrations will drop all tables and data</li>
                        <li>• Individual migration execution uses temporary isolation for safety</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Technical Implementation -->
        <div class="bg-gray-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Technical Implementation</h2>
            <p class="text-gray-600 mb-6">The Migration Manager uses several key techniques for safe operation:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Temporary Directory Isolation</h4>
                    <p class="text-sm text-gray-600 mb-2">Individual migrations are copied to a temporary directory before
                        execution:</p>
                    <code class="text-xs bg-gray-100 p-1 rounded">storage/app/temp_migrations</code>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-2">Artisan Command Integration</h4>
                    <p class="text-sm text-gray-600 mb-2">Uses Laravel's built-in migration commands:</p>
                    <ul class="text-xs text-gray-500 space-y-1">
                        <li>• <code>migrate --path=...</code> for individual migrations</li>
                        <li>• <code>migrate --force</code> for batch operations</li>
                        <li>• <code>migrate:rollback</code> for rollback operations</li>
                        <li>• <code>migrate:fresh</code> for fresh migrations</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection