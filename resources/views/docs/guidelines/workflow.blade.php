@extends('codeforge-studio::layout.docs')

@section('title', 'Development Workflow - CodeForge Database Studio')

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Development Workflow</h1>
                <p class="text-lg text-gray-600">
                    CodeForge Database Studio follows a structured development workflow to ensure high code quality,
                    comprehensive testing, and reliable releases.
                </p>
            </div>

            <!-- Git Workflow -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Git Workflow</h2>

                <div class="space-y-6">
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Branch Strategy</h3>
                        <div class="space-y-4">
                            <div class="bg-blue-100 p-4 rounded">
                                <h4 class="font-medium text-blue-900 mb-2">Main Branches</h4>
                                <ul class="space-y-2 text-blue-800 text-sm">
                                    <li><strong>main:</strong> Production-ready code, always stable</li>
                                    <li><strong>develop:</strong> Integration branch for features</li>
                                    <li><strong>release/*:</strong> Release preparation branches</li>
                                    <li><strong>hotfix/*:</strong> Critical bug fixes for production</li>
                                </ul>
                            </div>
                            <div class="bg-blue-100 p-4 rounded">
                                <h4 class="font-medium text-blue-900 mb-2">Feature Branches</h4>
                                <ul class="space-y-2 text-blue-800 text-sm">
                                    <li><strong>feature/database-health-monitoring:</strong> Health monitoring features</li>
                                    <li><strong>feature/schema-designer-improvements:</strong> Schema designer enhancements
                                    </li>
                                    <li><strong>feature/migration-management:</strong> Migration management features</li>
                                    <li><strong>bugfix/query-performance-logging:</strong> Bug fix branches</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h3 class="text-lg font-semibold text-green-900 mb-4">Commit Standards</h3>
                        <div class="space-y-3 text-green-800">
                            <p><strong>Conventional Commits:</strong> Use conventional commit format</p>
                            <div class="bg-green-100 p-4 rounded">
                                <pre class="text-sm"><code>feat(database-health): add real-time query performance tracking
    fix(schema-designer): resolve D3.js visualization rendering issue
    docs(api): update service class documentation
    test(migration): add comprehensive migration rollback tests
    refactor(services): optimize database health metrics collection</code></pre>
                            </div>
                            <div class="space-y-2">
                                <p><strong>Commit Types:</strong></p>
                                <ul class="text-sm space-y-1 ml-4">
                                    <li><code>feat:</code> New features</li>
                                    <li><code>fix:</code> Bug fixes</li>
                                    <li><code>docs:</code> Documentation updates</li>
                                    <li><code>test:</code> Test additions/modifications</li>
                                    <li><code>refactor:</code> Code refactoring</li>
                                    <li><code>perf:</code> Performance improvements</li>
                                    <li><code>chore:</code> Maintenance tasks</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Development Phases -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Development Phases</h2>

                <div class="space-y-6">
                    <div class="relative">
                        <div class="absolute left-4 top-8 bottom-0 w-0.5 bg-gray-300"></div>

                        <div class="relative flex items-start mb-8">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white text-sm font-bold rounded-full flex items-center justify-center">
                                1</div>
                            <div class="ml-6 bg-blue-50 p-6 rounded-lg border border-blue-200 flex-1">
                                <h3 class="text-lg font-semibold text-blue-900 mb-3">Planning & Analysis</h3>
                                <ul class="space-y-2 text-blue-800 text-sm">
                                    <li><strong>Requirement Analysis:</strong> Define feature requirements and
                                        specifications</li>
                                    <li><strong>Architecture Design:</strong> Plan system architecture and integration
                                        points</li>
                                    <li><strong>Database Design:</strong> Design database schema and relationships</li>
                                    <li><strong>API Design:</strong> Define service interfaces and contracts</li>
                                    <li><strong>Testing Strategy:</strong> Plan comprehensive testing approach</li>
                                </ul>
                            </div>
                        </div>

                        <div class="relative flex items-start mb-8">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-green-500 text-white text-sm font-bold rounded-full flex items-center justify-center">
                                2</div>
                            <div class="ml-6 bg-green-50 p-6 rounded-lg border border-green-200 flex-1">
                                <h3 class="text-lg font-semibold text-green-900 mb-3">Implementation</h3>
                                <ul class="space-y-2 text-green-800 text-sm">
                                    <li><strong>Core Services:</strong> Implement business logic services</li>
                                    <li><strong>Database Layer:</strong> Create models, migrations, and seeders</li>
                                    <li><strong>Filament Integration:</strong> Build pages, resources, and widgets</li>
                                    <li><strong>Command Line Tools:</strong> Develop Artisan commands</li>
                                    <li><strong>Background Processing:</strong> Implement queue jobs and listeners</li>
                                </ul>
                            </div>
                        </div>

                        <div class="relative flex items-start mb-8">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white text-sm font-bold rounded-full flex items-center justify-center">
                                3</div>
                            <div class="ml-6 bg-purple-50 p-6 rounded-lg border border-purple-200 flex-1">
                                <h3 class="text-lg font-semibold text-purple-900 mb-3">Testing & Quality Assurance</h3>
                                <ul class="space-y-2 text-purple-800 text-sm">
                                    <li><strong>Unit Testing:</strong> Test individual components (500+ test cases)</li>
                                    <li><strong>Feature Testing:</strong> Test complete user workflows</li>
                                    <li><strong>Integration Testing:</strong> Test component interactions</li>
                                    <li><strong>Performance Testing:</strong> Benchmark and optimize performance</li>
                                    <li><strong>Security Testing:</strong> Validate security implementations</li>
                                </ul>
                            </div>
                        </div>

                        <div class="relative flex items-start mb-8">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white text-sm font-bold rounded-full flex items-center justify-center">
                                4</div>
                            <div class="ml-6 bg-orange-50 p-6 rounded-lg border border-orange-200 flex-1">
                                <h3 class="text-lg font-semibold text-orange-900 mb-3">Documentation & Review</h3>
                                <ul class="space-y-2 text-orange-800 text-sm">
                                    <li><strong>Code Documentation:</strong> PHPDoc comments and inline documentation</li>
                                    <li><strong>User Documentation:</strong> Feature guides and API references</li>
                                    <li><strong>Code Review:</strong> Peer review and quality checks</li>
                                    <li><strong>Security Review:</strong> Security audit and validation</li>
                                    <li><strong>Performance Review:</strong> Performance optimization review</li>
                                </ul>
                            </div>
                        </div>

                        <div class="relative flex items-start">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-red-500 text-white text-sm font-bold rounded-full flex items-center justify-center">
                                5</div>
                            <div class="ml-6 bg-red-50 p-6 rounded-lg border border-red-200 flex-1">
                                <h3 class="text-lg font-semibold text-red-900 mb-3">Deployment & Release</h3>
                                <ul class="space-y-2 text-red-800 text-sm">
                                    <li><strong>Release Preparation:</strong> Version bumping and changelog updates</li>
                                    <li><strong>Package Building:</strong> Build distribution packages</li>
                                    <li><strong>Quality Gates:</strong> Final quality checks and validation</li>
                                    <li><strong>Release Distribution:</strong> Distribute through Anystack.sh</li>
                                    <li><strong>Post-Release Monitoring:</strong> Monitor for issues and feedback</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testing Workflow -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Testing Workflow</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                        <h3 class="text-lg font-semibold text-yellow-900 mb-4">Test Development</h3>
                        <ul class="space-y-2 text-yellow-800 text-sm">
                            <li><strong>TDD Approach:</strong> Write tests before implementation</li>
                            <li><strong>Test Categories:</strong> Unit, Feature, Integration tests</li>
                            <li><strong>Coverage Goals:</strong> Minimum 80% code coverage</li>
                            <li><strong>Mock Strategy:</strong> Mock external dependencies</li>
                            <li><strong>Data Factories:</strong> Use factories for test data</li>
                            <li><strong>Test Isolation:</strong> Ensure tests are independent</li>
                        </ul>
                    </div>

                    <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-200">
                        <h3 class="text-lg font-semibold text-indigo-900 mb-4">Test Execution</h3>
                        <div class="space-y-3 text-indigo-800">
                            <p><strong>Automated Testing:</strong></p>
                            <div class="bg-indigo-100 p-4 rounded text-sm">
                                <pre><code># Run all tests
    vendor/bin/phpunit

    # Run with coverage
    vendor/bin/phpunit --coverage-html coverage

    # Run specific test suite
    vendor/bin/phpunit --testsuite Unit

    # Run comprehensive test runner
    php tests/ComprehensiveTestRunner.php</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Code Review Process -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Code Review Process</h2>

                <div class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Checklist</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Code Quality</h4>
                                <ul class="space-y-1 text-gray-700 text-sm">
                                    <li>✓ PSR-12 coding standards compliance</li>
                                    <li>✓ Proper type hints and return types</li>
                                    <li>✓ PHPDoc documentation</li>
                                    <li>✓ Meaningful variable and method names</li>
                                    <li>✓ SOLID principles adherence</li>
                                    <li>✓ No code duplication</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Functionality</h4>
                                <ul class="space-y-1 text-gray-700 text-sm">
                                    <li>✓ Requirements implementation</li>
                                    <li>✓ Error handling and validation</li>
                                    <li>✓ Security considerations</li>
                                    <li>✓ Performance optimization</li>
                                    <li>✓ Backward compatibility</li>
                                    <li>✓ Integration with existing code</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Review Stages</h3>
                        <div class="space-y-3 text-blue-800">
                            <div class="flex items-start">
                                <span
                                    class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white text-xs font-bold rounded-full flex items-center justify-center mt-0.5">1</span>
                                <div class="ml-3">
                                    <p><strong>Automated Checks:</strong> CI/CD pipeline runs automated tests and quality
                                        checks</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span
                                    class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white text-xs font-bold rounded-full flex items-center justify-center mt-0.5">2</span>
                                <div class="ml-3">
                                    <p><strong>Peer Review:</strong> Another developer reviews code for quality and
                                        correctness</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span
                                    class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white text-xs font-bold rounded-full flex items-center justify-center mt-0.5">3</span>
                                <div class="ml-3">
                                    <p><strong>Lead Review:</strong> Technical lead reviews architecture and design
                                        decisions</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span
                                    class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white text-xs font-bold rounded-full flex items-center justify-center mt-0.5">4</span>
                                <div class="ml-3">
                                    <p><strong>Final Approval:</strong> Final approval and merge to main branch</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Release Management -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Release Management</h2>

                <div class="space-y-6">
                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h3 class="text-lg font-semibold text-green-900 mb-4">Semantic Versioning</h3>
                        <div class="space-y-3 text-green-800">
                            <p><strong>Version Format:</strong> MAJOR.MINOR.PATCH (e.g., 2.1.0)</p>
                            <ul class="space-y-2 text-sm">
                                <li><strong>MAJOR:</strong> Breaking changes or major feature releases</li>
                                <li><strong>MINOR:</strong> New features with backward compatibility</li>
                                <li><strong>PATCH:</strong> Bug fixes and minor improvements</li>
                            </ul>
                            <div class="bg-green-100 p-4 rounded mt-4">
                                <p class="text-sm"><strong>Current Version:</strong> 2.1.0 - Latest stable release with
                                    comprehensive database management features</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Release Cycle</h3>
                        <ul class="space-y-2 text-purple-800 text-sm">
                            <li><strong>Major Releases:</strong> Every 6-12 months with significant new features</li>
                            <li><strong>Minor Releases:</strong> Every 2-3 months with feature additions</li>
                            <li><strong>Patch Releases:</strong> As needed for bug fixes and security updates</li>
                            <li><strong>Hotfixes:</strong> Emergency releases for critical issues</li>
                            <li><strong>Beta Releases:</strong> Preview releases for testing new features</li>
                        </ul>
                    </div>

                    <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                        <h3 class="text-lg font-semibold text-orange-900 mb-4">Release Checklist</h3>
                        <div class="space-y-2 text-orange-800 text-sm">
                            <p><strong>Pre-Release:</strong></p>
                            <ul class="ml-4 space-y-1">
                                <li>✓ All tests passing (500+ test cases)</li>
                                <li>✓ Documentation updated</li>
                                <li>✓ Changelog updated</li>
                                <li>✓ Version numbers updated</li>
                                <li>✓ Security audit completed</li>
                            </ul>
                            <p><strong>Release:</strong></p>
                            <ul class="ml-4 space-y-1">
                                <li>✓ Tag and build release package</li>
                                <li>✓ Upload to Anystack.sh</li>
                                <li>✓ Update documentation site</li>
                                <li>✓ Notify customers</li>
                                <li>✓ Monitor for issues</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quality Assurance -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Quality Assurance</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                        <h3 class="text-lg font-semibold text-red-900 mb-4">Automated Quality Checks</h3>
                        <ul class="space-y-2 text-red-800 text-sm">
                            <li><strong>Static Analysis:</strong> Code quality and potential issue detection</li>
                            <li><strong>Security Scanning:</strong> Vulnerability assessment</li>
                            <li><strong>Performance Testing:</strong> Load and performance benchmarks</li>
                            <li><strong>Compatibility Testing:</strong> PHP and Laravel version compatibility</li>
                            <li><strong>Integration Testing:</strong> Full system integration verification</li>
                        </ul>
                    </div>

                    <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-200">
                        <h3 class="text-lg font-semibold text-indigo-900 mb-4">Manual Quality Assurance</h3>
                        <ul class="space-y-2 text-indigo-800 text-sm">
                            <li><strong>User Experience Testing:</strong> UI/UX validation</li>
                            <li><strong>Browser Compatibility:</strong> Cross-browser testing</li>
                            <li><strong>Documentation Review:</strong> Documentation accuracy and completeness</li>
                            <li><strong>Feature Validation:</strong> End-to-end feature testing</li>
                            <li><strong>Regression Testing:</strong> Existing functionality validation</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Continuous Integration -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Continuous Integration</h2>

                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">CI/CD Pipeline</h3>
                    <div class="space-y-4">
                        <div class="bg-gray-100 p-4 rounded">
                            <h4 class="font-medium text-gray-900 mb-2">Pipeline Stages</h4>
                            <ul class="space-y-2 text-gray-700 text-sm">
                                <li><strong>1. Code Quality:</strong> PSR-12 compliance, static analysis</li>
                                <li><strong>2. Dependencies:</strong> Composer install and dependency checks</li>
                                <li><strong>3. Testing:</strong> Unit, feature, and integration tests</li>
                                <li><strong>4. Security:</strong> Security vulnerability scanning</li>
                                <li><strong>5. Performance:</strong> Performance benchmarking</li>
                                <li><strong>6. Documentation:</strong> Documentation generation and validation</li>
                                <li><strong>7. Package:</strong> Build distribution packages</li>
                                <li><strong>8. Deploy:</strong> Deploy to staging/production environments</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection