@extends('codeforge-studio::layout.docs')

@section('title', 'Contribution Guidelines - CodeForge Database Studio')

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Contribution Guidelines</h1>
                <p class="text-lg text-gray-600">
                    CodeForge Database Studio is free and open source under the MIT License.
                    We welcome contributions from the community on
                    <a href="https://github.com/hardikkanajariya-in/codeforge" class="text-primary-600 hover:text-primary-700">GitHub</a>.
                </p>
            </div>

            <!-- Open Source Notice -->
            <div class="mb-12">
                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Open Source Contributions</h3>
                            <p class="mt-1 text-sm text-green-700">
                                Read <a href="https://github.com/hardikkanajariya-in/codeforge/blob/master/CONTRIBUTING.md" class="underline font-medium">CONTRIBUTING.md</a>
                                before opening a pull request. Maintainer:
                                <a href="https://hardikkanajariya.in" class="underline font-medium">Hardik Kanajariya</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Getting Started -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Getting Started</h2>

                <div class="space-y-6">
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Prerequisites</h3>
                        <ul class="space-y-2 text-blue-800">
                            <li><strong>Valid License:</strong> Must have an active CodeForge Database Studio license</li>
                            <li><strong>Development Environment:</strong> PHP 8.1+, Laravel 10+, Filament 3+</li>
                            <li><strong>Testing Setup:</strong> PHPUnit 10+ with comprehensive test coverage</li>
                            <li><strong>Code Standards:</strong> Follow PSR-12 coding standards</li>
                            <li><strong>Git Knowledge:</strong> Familiarity with Git and GitHub workflows</li>
                        </ul>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h3 class="text-lg font-semibold text-green-900 mb-4">Development Setup</h3>
                        <div class="space-y-3 text-green-800">
                            <p><strong>1. Environment Setup:</strong></p>
                            <div class="bg-green-100 p-4 rounded">
                                <pre class="text-sm"><code># Clone the repository (requires access)
    git clone https://github.com/hardik-kanajariya/codeforge-database-studio.git

    # Install dependencies
    composer install

    # Set up testing environment
    cp .env.testing.example .env.testing
    vendor/bin/phpunit</code></pre>
                            </div>
                            <p><strong>2. Testing Requirements:</strong> All contributions must include comprehensive tests
                            </p>
                            <p><strong>3. Documentation:</strong> Update relevant documentation for new features</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contribution Types -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Types of Contributions</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Bug Fixes</h3>
                        <ul class="space-y-2 text-purple-800 text-sm">
                            <li><strong>Issue Verification:</strong> Reproduce and document the bug</li>
                            <li><strong>Root Cause Analysis:</strong> Identify the underlying cause</li>
                            <li><strong>Minimal Changes:</strong> Fix with minimal code changes</li>
                            <li><strong>Test Coverage:</strong> Add tests to prevent regression</li>
                            <li><strong>Impact Assessment:</strong> Document potential side effects</li>
                        </ul>
                    </div>

                    <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                        <h3 class="text-lg font-semibold text-orange-900 mb-4">Feature Enhancements</h3>
                        <ul class="space-y-2 text-orange-800 text-sm">
                            <li><strong>Proposal Discussion:</strong> Discuss feature with maintainers first</li>
                            <li><strong>Design Document:</strong> Create detailed design specification</li>
                            <li><strong>Backward Compatibility:</strong> Ensure existing functionality remains</li>
                            <li><strong>Configuration Options:</strong> Make features configurable</li>
                            <li><strong>Performance Impact:</strong> Assess and optimize performance</li>
                        </ul>
                    </div>

                    <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                        <h3 class="text-lg font-semibold text-red-900 mb-4">Security Improvements</h3>
                        <ul class="space-y-2 text-red-800 text-sm">
                            <li><strong>Vulnerability Assessment:</strong> Identify security vulnerabilities</li>
                            <li><strong>Impact Analysis:</strong> Assess severity and scope</li>
                            <li><strong>Secure Implementation:</strong> Follow security best practices</li>
                            <li><strong>Security Testing:</strong> Comprehensive security test coverage</li>
                            <li><strong>Documentation:</strong> Document security improvements</li>
                        </ul>
                    </div>

                    <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-200">
                        <h3 class="text-lg font-semibold text-indigo-900 mb-4">Performance Optimizations</h3>
                        <ul class="space-y-2 text-indigo-800 text-sm">
                            <li><strong>Benchmarking:</strong> Measure current performance baseline</li>
                            <li><strong>Profiling:</strong> Identify performance bottlenecks</li>
                            <li><strong>Optimization Strategy:</strong> Plan optimization approach</li>
                            <li><strong>Testing:</strong> Verify improvements with benchmarks</li>
                            <li><strong>Monitoring:</strong> Add performance monitoring if needed</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Development Process -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Development Process</h2>

                <div class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Development Workflow</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <span
                                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white text-sm font-bold rounded-full flex items-center justify-center">1</span>
                                <div class="ml-4">
                                    <h4 class="font-medium text-gray-900">Fork & Branch</h4>
                                    <p class="text-gray-600 text-sm">Create a feature branch from the latest main branch</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span
                                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white text-sm font-bold rounded-full flex items-center justify-center">2</span>
                                <div class="ml-4">
                                    <h4 class="font-medium text-gray-900">Develop & Test</h4>
                                    <p class="text-gray-600 text-sm">Implement changes with comprehensive test coverage</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span
                                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white text-sm font-bold rounded-full flex items-center justify-center">3</span>
                                <div class="ml-4">
                                    <h4 class="font-medium text-gray-900">Quality Assurance</h4>
                                    <p class="text-gray-600 text-sm">Run full test suite and code quality checks</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span
                                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white text-sm font-bold rounded-full flex items-center justify-center">4</span>
                                <div class="ml-4">
                                    <h4 class="font-medium text-gray-900">Pull Request</h4>
                                    <p class="text-gray-600 text-sm">Submit pull request with detailed description</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span
                                    class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white text-white text-sm font-bold rounded-full flex items-center justify-center">5</span>
                                <div class="ml-4">
                                    <h4 class="font-medium text-gray-900">Review & Merge</h4>
                                    <p class="text-gray-600 text-sm">Address feedback and merge after approval</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testing Requirements -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Testing Requirements</h2>

                <div class="space-y-6">
                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h3 class="text-lg font-semibold text-green-900 mb-4">Test Coverage Standards</h3>
                        <ul class="space-y-2 text-green-800">
                            <li><strong>Minimum Coverage:</strong> 80% code coverage for all new code</li>
                            <li><strong>Unit Tests:</strong> Test individual components in isolation</li>
                            <li><strong>Feature Tests:</strong> Test complete user workflows</li>
                            <li><strong>Integration Tests:</strong> Test component interactions</li>
                            <li><strong>Edge Cases:</strong> Include tests for edge cases and error conditions</li>
                        </ul>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Test Organization</h3>
                        <div class="bg-blue-100 p-4 rounded mt-4">
                            <pre class="text-sm"><code>tests/
    ├── Unit/                  # Unit tests
    │   ├── Services/         # Service class tests
    │   ├── Models/           # Model tests
    │   └── Commands/         # Command tests
    ├── Feature/              # Feature tests
    │   ├── DatabaseHealth/   # Health monitoring tests
    │   ├── SchemaDesigner/   # Schema designer tests
    │   └── MigrationManager/ # Migration management tests
    └── Integration/          # Integration tests
        ├── FilamentTests/    # Filament integration tests
        └── DatabaseTests/    # Database integration tests</code></pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Code Quality -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Code Quality Requirements</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                        <h3 class="text-lg font-semibold text-yellow-900 mb-4">Code Standards</h3>
                        <ul class="space-y-2 text-yellow-800 text-sm">
                            <li><strong>PSR-12:</strong> Follow PSR-12 coding standards</li>
                            <li><strong>Type Hints:</strong> Use strict type declarations</li>
                            <li><strong>Documentation:</strong> PHPDoc for all public methods</li>
                            <li><strong>Naming:</strong> Descriptive variable and method names</li>
                            <li><strong>SOLID Principles:</strong> Follow SOLID design principles</li>
                        </ul>
                    </div>

                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Security Standards</h3>
                        <ul class="space-y-2 text-purple-800 text-sm">
                            <li><strong>Input Validation:</strong> Validate all user inputs</li>
                            <li><strong>Authorization:</strong> Proper permission checks</li>
                            <li><strong>SQL Injection:</strong> Use parameterized queries</li>
                            <li><strong>XSS Prevention:</strong> Escape output appropriately</li>
                            <li><strong>Error Handling:</strong> Don't expose sensitive information</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Support & Contact -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Support & Contact</h2>

                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Getting Help</h3>
                    <div class="space-y-3 text-gray-700">
                        <p><strong>Development Support:</strong> <a href="mailto:support@hardikkanajariya.in"
                                class="text-blue-600 hover:text-blue-800">support@hardikkanajariya.in</a></p>
                        <p><strong>Technical Documentation:</strong> Refer to our comprehensive documentation</p>
                        <p><strong>Community Forum:</strong> Join discussions with other licensed developers</p>
                        <p><strong>Issue Reporting:</strong> Use GitHub issues for bug reports and feature requests</p>
                    </div>
                </div>
            </div>

            <!-- License -->
            <div class="mb-12">
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Contributor License</h3>
                    <p class="text-blue-800 mb-4">
                        By contributing to CodeForge Database Studio, you agree that your contributions will be licensed
                        under the MIT License.
                    </p>
                    <ul class="space-y-2 text-blue-800 text-sm">
                        <li><strong>Copyright:</strong> You retain copyright on your contributions</li>
                        <li><strong>License:</strong> Contributions are licensed under the MIT License</li>
                        <li><strong>Attribution:</strong> Contributors are credited in release notes when applicable</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection