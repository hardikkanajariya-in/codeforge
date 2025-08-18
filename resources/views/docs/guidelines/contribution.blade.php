@extends('codeforge-studio::layout.docs')

@section('title', 'Contribution - CodeForge Database Studio')

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
<div class='bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center'>
    <h2 class='text-2xl font-bold text-gray-900 mb-4'>Contribution Documentation</h2>
    <p class='text-gray-600 mb-6'>Documentation for this section is coming soon.</p>
    <a href='{{ route('codeforge.docs.getting-started') }}' class='text-primary-600 hover:text-primary-700 font-medium'> Back to Getting Started</a>
</div>
@endsection
