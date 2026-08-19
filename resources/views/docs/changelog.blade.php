@extends('codeforge-studio::layout.docs')

@section('title', 'Changelog - CodeForge Database Studio')

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto animate-fade-in-up">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Changelog</h1>
            <p class="text-lg text-gray-600">
                Release history for CodeForge Database Studio. The canonical changelog lives in the repository
                <a href="https://github.com/hardikkanajariya-in/codeforge/blob/master/CHANGELOG.md" class="text-primary-600 hover:text-primary-700">CHANGELOG.md</a>.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Unreleased</h2>
            <h3 class="font-semibold text-gray-800 mb-2">Changed</h3>
            <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                <li>Rebranded as a free, open source project under the MIT License</li>
                <li>Dropped Filament v3 support; targets Filament v4 and v5 Schema APIs only</li>
                <li>Minimum PHP raised to 8.3; supports Laravel 12 and 13</li>
                <li>Documentation aligned with actual Artisan commands, config keys, and export formats</li>
                <li>CI matrix: PHP 8.3 × Laravel 12/13 × Filament 4/5</li>
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">1.1.0 <span class="text-gray-500 text-lg font-normal">— 2025-08-27</span></h2>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li>Complete authorization logic for policy methods</li>
                <li>Full CRUD implementations for generated controllers</li>
                <li>Removed license key requirements; MIT License</li>
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">1.0.0 <span class="text-gray-500 text-lg font-normal">— 2025-08-22</span></h2>
            <p class="text-gray-600">
                Initial release: schema designer, migration management, health monitoring, smart seeding,
                documentation generator (Markdown, HTML, PDF), and Filament-based code generation suite.
            </p>
        </div>
    </div>
@endsection
