@extends('codeforge-studio::layout.docs')

@section('title', 'Coding Standards - CodeForge Database Studio')

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Coding Standards</h1>
                <p class="text-lg text-gray-600">
                    CodeForge Database Studio follows strict coding standards to ensure maintainability, readability, and
                    consistency across the entire codebase.
                </p>
            </div>

            <!-- PSR Standards -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">PSR Compliance</h2>
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 mb-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">PSR-4 Autoloading</h3>
                    <div class="space-y-3 text-blue-800">
                        <p><strong>Namespace:</strong> All classes use the <code
                                class="bg-blue-100 px-2 py-1 rounded">HkDevs\CodeForgeStudio</code> namespace</p>
                        <p><strong>Directory Structure:</strong> PSR-4 compliant file organization</p>
                        <div class="bg-blue-100 p-4 rounded mt-4">
                            <pre class="text-sm"><code>src/
    ├── Commands/           # Artisan Commands
    ├── Filament/          # Filament Pages & Resources
    ├── Http/              # Controllers & Middleware
    ├── Listeners/         # Event Listeners
    ├── Models/            # Eloquent Models
    ├── Resources/         # Filament Resources
    ├── Services/          # Business Logic Services
    └── Widgets/           # Dashboard Widgets</code></pre>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-green-900 mb-4">PSR-12 Coding Style</h3>
                    <ul class="space-y-2 text-green-800">
                        <li><strong>Indentation:</strong> 4 spaces, no tabs</li>
                        <li><strong>Line Length:</strong> Maximum 120 characters per line</li>
                        <li><strong>Class Names:</strong> StudlyCase (e.g., <code>DatabaseHealthService</code>)</li>
                        <li><strong>Method Names:</strong> camelCase (e.g., <code>collectHealthMetrics</code>)</li>
                        <li><strong>Constants:</strong> UPPER_CASE with underscores</li>
                        <li><strong>File Encoding:</strong> UTF-8 without BOM</li>
                    </ul>
                </div>
            </div>

            <!-- Laravel Standards -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Laravel Conventions</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Eloquent Models</h3>
                        <ul class="space-y-2 text-purple-800 text-sm">
                            <li><strong>Naming:</strong> Singular, StudlyCase (e.g., <code>QueryLog</code>)</li>
                            <li><strong>Table Names:</strong> Plural, snake_case (e.g., <code>query_logs</code>)</li>
                            <li><strong>Primary Keys:</strong> <code>id</code> column with auto-increment</li>
                            <li><strong>Timestamps:</strong> <code>created_at</code> and <code>updated_at</code></li>
                            <li><strong>Fillable:</strong> Explicitly define fillable attributes</li>
                            <li><strong>Casts:</strong> Use proper type casting for attributes</li>
                        </ul>
                    </div>

                    <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                        <h3 class="text-lg font-semibold text-orange-900 mb-4">Service Classes</h3>
                        <ul class="space-y-2 text-orange-800 text-sm">
                            <li><strong>Naming:</strong> Descriptive with "Service" suffix</li>
                            <li><strong>Single Responsibility:</strong> Each service handles one domain</li>
                            <li><strong>Dependency Injection:</strong> Constructor injection for dependencies</li>
                            <li><strong>Return Types:</strong> Explicit return type declarations</li>
                            <li><strong>Error Handling:</strong> Proper exception handling</li>
                            <li><strong>Documentation:</strong> PHPDoc blocks for all public methods</li>
                        </ul>
                    </div>

                    <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                        <h3 class="text-lg font-semibold text-red-900 mb-4">Artisan Commands</h3>
                        <ul class="space-y-2 text-red-800 text-sm">
                            <li><strong>Signature:</strong> Kebab-case with namespace prefix</li>
                            <li><strong>Description:</strong> Clear, concise command descriptions</li>
                            <li><strong>Arguments:</strong> Properly defined with validation</li>
                            <li><strong>Options:</strong> Use options for optional parameters</li>
                            <li><strong>Output:</strong> Informative progress and result messages</li>
                            <li><strong>Error Handling:</strong> Graceful error handling with exit codes</li>
                        </ul>
                    </div>

                    <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-200">
                        <h3 class="text-lg font-semibold text-indigo-900 mb-4">Filament Components</h3>
                        <ul class="space-y-2 text-indigo-800 text-sm">
                            <li><strong>Resources:</strong> Singular model name with "Resource" suffix</li>
                            <li><strong>Pages:</strong> Descriptive page names in StudlyCase</li>
                            <li><strong>Forms:</strong> Clear field definitions with validation</li>
                            <li><strong>Tables:</strong> Optimized queries with proper relationships</li>
                            <li><strong>Actions:</strong> Descriptive action names with confirmations</li>
                            <li><strong>Permissions:</strong> Proper authorization checks</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Code Quality -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Code Quality Standards</h2>

                <div class="space-y-6">
                    <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                        <h3 class="text-lg font-semibold text-yellow-900 mb-4">Documentation Requirements</h3>
                        <ul class="space-y-2 text-yellow-800">
                            <li><strong>PHPDoc:</strong> All public methods must have PHPDoc comments</li>
                            <li><strong>Parameter Types:</strong> Document all parameters with types</li>
                            <li><strong>Return Types:</strong> Document return types and possible exceptions</li>
                            <li><strong>Class Documentation:</strong> Brief description of class purpose</li>
                            <li><strong>Complex Logic:</strong> Inline comments for complex algorithms</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Testing Standards</h3>
                        <ul class="space-y-2 text-gray-800">
                            <li><strong>Test Coverage:</strong> Minimum 80% code coverage required</li>
                            <li><strong>Test Organization:</strong> Unit, Feature, and Integration test suites</li>
                            <li><strong>Test Naming:</strong> Descriptive test method names</li>
                            <li><strong>Assertions:</strong> Use appropriate assertion methods</li>
                            <li><strong>Test Data:</strong> Use factories and seeders for test data</li>
                            <li><strong>Mocking:</strong> Mock external dependencies appropriately</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Security Standards -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Security Standards</h2>

                <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                    <h3 class="text-lg font-semibold text-red-900 mb-4">Security Best Practices</h3>
                    <ul class="space-y-2 text-red-800">
                        <li><strong>Input Validation:</strong> Validate all user inputs using Laravel validation</li>
                        <li><strong>SQL Injection:</strong> Use Eloquent ORM and prepared statements</li>
                        <li><strong>CSRF Protection:</strong> Enable CSRF protection for all forms</li>
                        <li><strong>Authorization:</strong> Implement proper authorization checks</li>
                        <li><strong>Sensitive Data:</strong> Never log or expose sensitive information</li>
                        <li><strong>Error Handling:</strong> Don't expose internal errors to users</li>
                    </ul>
                </div>
            </div>

            <!-- Performance Standards -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Standards</h2>

                <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-green-900 mb-4">Optimization Guidelines</h3>
                    <ul class="space-y-2 text-green-800">
                        <li><strong>Database Queries:</strong> Use eager loading to prevent N+1 queries</li>
                        <li><strong>Caching:</strong> Implement caching for expensive operations</li>
                        <li><strong>Background Jobs:</strong> Use queues for time-consuming tasks</li>
                        <li><strong>Memory Usage:</strong> Avoid loading large datasets into memory</li>
                        <li><strong>Asset Optimization:</strong> Minimize and compress CSS/JS assets</li>
                        <li><strong>Database Indexes:</strong> Proper indexing for frequently queried columns</li>
                    </ul>
                </div>
            </div>

            <!-- File Organization -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">File Organization</h2>

                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Directory Structure Standards</h3>
                    <div class="bg-blue-100 p-4 rounded mt-4">
                        <pre class="text-sm"><code>HkDevs\CodeForgeStudio\
    ├── Commands\              # Artisan commands
    │   ├── CleanupLogsCommand.php
    │   ├── CollectHealthMetricsCommand.php
    │   └── GenerateDocumentationCommand.php
    ├── Filament\             # Filament-specific components
    │   ├── Pages\            # Custom Filament pages
    │   └── Resources\        # Filament resources
    ├── Http\                 # HTTP layer components
    │   └── Controllers\      # Controllers
    ├── Listeners\            # Event listeners
    ├── Models\               # Eloquent models
    ├── Services\             # Business logic services
    └── Widgets\              # Dashboard widgets</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection