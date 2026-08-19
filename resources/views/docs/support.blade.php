@extends('codeforge-studio::layout.docs')

@section('title', 'Support - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='text-primary-600 font-medium'>Support</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto animate-fade-in-up">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Support</h1>
            <p class="text-lg text-gray-600">CodeForge Database Studio is community-maintained open source software (MIT License).</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">GitHub Issues</h2>
                <p class="text-gray-600 mb-4">Report bugs and request features on the repository issue tracker.</p>
                <a href="https://github.com/hardikkanajariya-in/codeforge/issues" class="text-primary-600 hover:text-primary-700 font-medium">Open an issue →</a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Security</h2>
                <p class="text-gray-600 mb-4">Report security vulnerabilities privately per SECURITY.md.</p>
                <a href="https://github.com/hardikkanajariya-in/codeforge/blob/master/SECURITY.md" class="text-primary-600 hover:text-primary-700 font-medium">Security policy →</a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Contributing</h2>
                <p class="text-gray-600 mb-4">Pull requests welcome. See CONTRIBUTING.md for workflow and standards.</p>
                <a href="https://github.com/hardikkanajariya-in/codeforge/blob/master/CONTRIBUTING.md" class="text-primary-600 hover:text-primary-700 font-medium">Contributing guide →</a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Packagist</h2>
                <p class="text-gray-600 mb-4">Install via Composer: <code class="bg-gray-100 px-1 rounded">hkdevs/codeforge-database-studio</code></p>
                <a href="https://packagist.org/packages/hkdevs/codeforge-database-studio" class="text-primary-600 hover:text-primary-700 font-medium">View on Packagist →</a>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Maintainer</h2>
            <p class="text-gray-600">Hardik Kanajariya — <a href="https://github.com/hardikkanajariya-in" class="text-primary-600 hover:text-primary-700">GitHub</a></p>
        </div>
    </div>
@endsection
