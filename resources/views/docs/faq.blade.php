@extends('codeforge-studio::layout.docs')

@section('title', 'FAQ - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='text-primary-600 font-medium'>FAQ</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto animate-fade-in-up">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h1>
            <p class="text-lg text-gray-600">Common questions about installation, compatibility, and features.</p>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">What versions are supported?</h2>
                <p class="text-gray-600">PHP 8.3+, Laravel 12.x or 13.x, and Filament 4.x or 5.x. Filament v3 is not supported.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">How do I enable or disable features?</h2>
                <p class="text-gray-600 mb-2">Register the plugin in your Filament panel provider and call <code class="bg-gray-100 px-1 rounded">enable*()</code> methods on <code class="bg-gray-100 px-1 rounded">CodeForgeStudioPlugin::make()</code>. Config <code class="bg-gray-100 px-1 rounded">features.*</code> only toggles quick-action cards on the Database Overview page—it does not register Filament pages.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Can I generate models from the command line?</h2>
                <p class="text-gray-600">Model, migration, factory, seeder, and Filament resource generation runs through Filament generator pages in the panel. There are no <code class="bg-gray-100 px-1 rounded">codeforge:generate:*</code> Artisan commands. Use <code class="bg-gray-100 px-1 rounded">codeforge:generate-data</code> and <code class="bg-gray-100 px-1 rounded">codeforge:run-seeders</code> for data workflows.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">What documentation formats are exported?</h2>
                <p class="text-gray-600">Markdown, HTML, and PDF via the Documentation Generator UI or <code class="bg-gray-100 px-1 rounded">php artisan codeforge:generate-docs</code>. JSON export is not implemented.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Where are published assets located?</h2>
                <p class="text-gray-600">After <code class="bg-gray-100 px-1 rounded">php artisan codeforge:install</code> or <code class="bg-gray-100 px-1 rounded">codeforge:assets publish</code>, CSS and JS are published to <code class="bg-gray-100 px-1 rounded">public/vendor/codeforge/</code>.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">How do I access in-app documentation?</h2>
                <p class="text-gray-600">Call <code class="bg-gray-100 px-1 rounded">->enableDevDocs()</code> on the plugin. Docs are served at <code class="bg-gray-100 px-1 rounded">/codeforge/docs</code> when enabled.</p>
            </div>
        </div>
    </div>
@endsection
