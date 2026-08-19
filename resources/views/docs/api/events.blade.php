@extends('codeforge-studio::layout.docs')

@section('title', 'Events - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='flex items-center'>
        <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='flex items-center'>
        <span class='text-gray-500'>API</span>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Events</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="max-w-4xl mx-auto animate-fade-in-up">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Events & Listeners</h1>
            <p class="text-lg text-gray-600">
                The package does not publish custom domain events for third-party subscription. Integration uses
                Laravel's database query layer and internal services directly.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Query performance listener</h2>
            <p class="text-gray-600 mb-4">
                <code class="bg-gray-100 px-2 py-1 rounded">QueryPerformanceListener</code> hooks into Laravel's
                <code class="bg-gray-100 px-1 rounded">DB::listen()</code> when query logging is enabled in config
                (<code class="bg-gray-100 px-1 rounded">enable_query_logging</code>).
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li>Records slow queries above <code class="bg-gray-100 px-1 rounded">query_logging.slow_query_threshold</code> (ms)</li>
                <li>Skips patterns listed in <code class="bg-gray-100 px-1 rounded">query_logging.skip_patterns</code></li>
                <li>Stores rows in the <code class="bg-gray-100 px-1 rounded">query_performance_logs</code> table</li>
            </ul>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-amber-900 mb-2">Extending behavior</h2>
            <p class="text-amber-800 text-sm">
                To react to schema or migration changes, extend package services or wrap Filament pages in your application.
                See <a href="{{ route('codeforge.docs.advanced.extending') }}" class="text-primary-600 hover:text-primary-700">Extending CodeForge</a>
                and <a href="{{ route('codeforge.docs.architecture.events') }}" class="text-primary-600 hover:text-primary-700">Architecture: Events</a>.
            </p>
        </div>
    </div>
@endsection
