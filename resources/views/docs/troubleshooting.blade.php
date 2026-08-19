@extends('codeforge-studio::layout.docs')

@section('title', 'Troubleshooting - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='text-primary-600 font-medium'>Troubleshooting</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto animate-fade-in-up">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Troubleshooting</h1>
            <p class="text-lg text-gray-600">Solutions for common installation and runtime issues.</p>
        </div>

        <div class="space-y-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Plugin pages not appearing in navigation</h2>
                <ul class="list-disc list-inside text-gray-600 space-y-2">
                    <li>Confirm <code class="bg-gray-100 px-1 rounded">CodeForgeStudioPlugin::make()</code> is registered on your Filament panel</li>
                    <li>Enable the feature with the matching <code class="bg-gray-100 px-1 rounded">enable*()</code> method (e.g. <code class="bg-gray-100 px-1 rounded">enableSchemaDesigner()</code>)</li>
                    <li>Run <code class="bg-gray-100 px-1 rounded">php artisan codeforge:install</code> to publish config and migrations</li>
                    <li>Run <code class="bg-gray-100 px-1 rounded">php artisan migrate</code> for package tables</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Missing CSS or JavaScript</h2>
                <ul class="list-disc list-inside text-gray-600 space-y-2">
                    <li>Publish assets: <code class="bg-gray-100 px-1 rounded">php artisan codeforge:assets publish</code></li>
                    <li>Verify files exist under <code class="bg-gray-100 px-1 rounded">public/vendor/codeforge/css</code> and <code class="bg-gray-100 px-1 rounded">public/vendor/codeforge/js</code></li>
                    <li>Debug registration: <code class="bg-gray-100 px-1 rounded">php artisan codeforge:asset-debug</code></li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Seeders not discovered</h2>
                <ul class="list-disc list-inside text-gray-600 space-y-2">
                    <li><code class="bg-gray-100 px-1 rounded">php artisan codeforge:diagnose-seeders</code> — inspect discovery paths and classes</li>
                    <li><code class="bg-gray-100 px-1 rounded">php artisan codeforge:debug-discovery</code> — detailed discovery debug output</li>
                    <li><code class="bg-gray-100 px-1 rounded">php artisan codeforge:fix-seeder-paths</code> — repair common path issues</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Query performance logs not recording</h2>
                <ul class="list-disc list-inside text-gray-600 space-y-2">
                    <li>Check <code class="bg-gray-100 px-1 rounded">enable_query_logging</code> in config (default: true)</li>
                    <li>Toggle at runtime: <code class="bg-gray-100 px-1 rounded">php artisan codeforge:toggle-query-logging</code></li>
                    <li>Adjust <code class="bg-gray-100 px-1 rounded">query_logging.slow_query_threshold</code> if only slow queries are logged</li>
                    <li>Clean old logs: <code class="bg-gray-100 px-1 rounded">php artisan codeforge:cleanup-logs</code></li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Composer / version errors</h2>
                <p class="text-gray-600">This package requires PHP 8.3+, Laravel 12 or 13, and Filament 4 or 5. Upgrade your stack or use a branch compatible with your environment. See <a href="{{ route('codeforge.docs.requirements') }}" class="text-primary-600 hover:text-primary-700">Requirements</a>.</p>
            </div>
        </div>
    </div>
@endsection
