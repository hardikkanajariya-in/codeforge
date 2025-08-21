@extends('codeforge-studio::layout.docs')

@section('title', 'Testing Guide - CodeForge Database Studio')
@section('description', 'Learn how to test your CodeForge Database Studio integrations and extensions effectively.')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('codeforge.docs.home') }}" class="text-gray-500 hover:text-primary-600">Documentation</a>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="flex items-center">
        <span class="text-gray-500">Advanced</span>
        <svg class="ml-2 mr-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-primary-600 font-medium">Testing</li>
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
                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Testing Guide</h1>
                    <p class="text-xl text-gray-600">Best practices for testing CodeForge Database Studio integrations and
                        custom extensions</p>
                </div>
            </div>
        </div>

        <!-- Testing Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Testing Framework</h2>
            <p class="text-gray-600 mb-6">
                CodeForge Database Studio includes a comprehensive testing suite with over 500 test cases covering all major
                functionality.
                Learn how to extend these tests and create your own test cases.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-green-900 mb-3">Unit Tests</h3>
                    <p class="text-green-700 text-sm mb-4">Test individual services, commands, and components in isolation.
                    </p>
                    <div class="space-y-2">
                        <div class="text-sm text-green-600">• Service Layer Testing</div>
                        <div class="text-sm text-green-600">• Command Testing</div>
                        <div class="text-sm text-green-600">• Model Testing</div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Feature Tests</h3>
                    <p class="text-blue-700 text-sm mb-4">Test complete workflows and user interactions.</p>
                    <div class="space-y-2">
                        <div class="text-sm text-blue-600">• Database Operations</div>
                        <div class="text-sm text-blue-600">• Code Generation</div>
                        <div class="text-sm text-blue-600">• Schema Operations</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Running Tests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Running Tests</h2>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Basic Test Commands</h3>
                <div class="space-y-3">
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        # Run all tests<br>
                        php artisan test tests/
                    </div>
                    <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                        # Run specific test suite<br>
                        php artisan test tests/Unit/<br>
                        php artisan test tests/Feature/
                    </div>
                </div>
            </div>
        </div>

        <!-- Writing Custom Tests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Writing Custom Tests</h2>
            <p class="text-gray-600 mb-6">
                When extending CodeForge Database Studio, it's important to write tests for your custom functionality.
            </p>

            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Example Test Structure</h3>
                <div class="bg-gray-900 text-green-400 p-4 rounded font-mono text-sm">
                    &lt;?php<br><br>
                    use Tests\TestCase;<br>
                    use HkDevs\CodeForgeStudio\Services\DatabaseService;<br><br>
                    class CustomFeatureTest extends TestCase<br>
                    {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;public function test_custom_feature_works()<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Arrange<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$service = app(DatabaseService::class);<br><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Act<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$result = $service->customMethod();<br><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Assert<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->assertTrue($result);<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                    }
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-gradient-to-r from-primary-50 to-indigo-50 border border-primary-200 rounded-xl p-8">
            <h2 class="text-2xl font-bold text-primary-900 mb-4">Next Steps</h2>
            <p class="text-primary-700 mb-6">Continue learning about CodeForge Database Studio development:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('codeforge.docs.advanced.performance') }}"
                    class="flex items-center p-4 bg-white rounded-lg border border-primary-200 hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-primary-900">Performance Guide</h3>
                        <p class="text-primary-600 text-sm">Optimize your implementation</p>
                    </div>
                </a>
                <a href="{{ route('codeforge.docs.advanced.deployment') }}"
                    class="flex items-center p-4 bg-white rounded-lg border border-primary-200 hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-primary-900">Deployment</h3>
                        <p class="text-primary-600 text-sm">Deploy to production</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection