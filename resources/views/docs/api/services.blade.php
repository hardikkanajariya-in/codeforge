@extends('codeforge-studio::layout.docs')

@section('title', 'Services - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='flex items-center'>
        <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='flex items-center'>
        <span class='text-gray-500'>Api</span>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Services</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
<div class='bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center'>
    <h2 class='text-2xl font-bold text-gray-900 mb-4'>Services Documentation</h2>
    <p class='text-gray-600 mb-6'>Documentation for this section is coming soon.</p>
    <a href='{{ route('codeforge.docs.getting-started') }}' class='text-primary-600 hover:text-primary-700 font-medium'> Back to Getting Started</a>
</div>
@endsection
