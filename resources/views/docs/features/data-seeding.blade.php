@extends('codeforge-database-studio::layout.docs')

@section('title', 'Data Seeding - CodeForge Database Studio')

@section('breadcrumbs')
    <li class='flex items-center'>
        <a href='{{ route('codeforge.docs.home') }}' class='text-gray-500 hover:text-primary-600'>Documentation</a>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='flex items-center'>
        <span class='text-gray-500'>Features</span>
        <svg class='ml-2 mr-2 w-4 h-4 text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7'></path>
        </svg>
    </li>
    <li class='text-primary-600 font-medium'>Data Seeding</li>
@endsection

@section('navigation')
    @include('codeforge-database-studio::docs.partials.navigation')
@endsection


@section('title', 'Smart Data Seeding')

@section('content')
    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Smart Data Seeding</h1>
                    <p class="text-xl text-gray-600">Intelligent data generation with relationship awareness and realistic
                        patterns</p>
                </div>
            </div>
        </div>

        <h3>Intelligent Data Generation</h3>
        <div class="bg-gray-50 p-4 rounded-md">
            <ul>
                <li><strong>Relationship-Aware:</strong> Automatically handles foreign key relationships</li>
                <li><strong>Constraint Validation:</strong> Respects all database constraints and rules</li>
                <li><strong>Realistic Patterns:</strong> Generates realistic data based on field names and types</li>
                <li><strong>Localization Support:</strong> Multi-language data generation capabilities</li>
            </ul>
        </div>

        <h3>Template System</h3>
        <div class="bg-blue-50 p-4 rounded-md">
            <ul>
                <li><strong>Custom Templates:</strong> Define custom data generation templates</li>
                <li><strong>Business Logic Integration:</strong> Incorporate business rules into data generation</li>
                <li><strong>Field-Specific Patterns:</strong> Specialized patterns for different data types</li>
                <li><strong>Conditional Generation:</strong> Context-aware data generation based on other fields</li>
            </ul>
        </div>

        <h3>Advanced Seeding Options</h3>
        <div class="bg-green-50 p-4 rounded-md">
            <ul>
                <li><strong>Bulk Operations:</strong> Efficient bulk data generation and insertion</li>
                <li><strong>Incremental Seeding:</strong> Add data to existing datasets without conflicts</li>
                <li><strong>Performance Optimization:</strong> Optimized for large-scale data generation</li>
                <li><strong>Memory Management:</strong> Efficient memory usage for large datasets</li>
            </ul>
        </div>

        <h2>Data Generation Patterns</h2>
        <p>Intelligent pattern recognition for realistic data:</p>

        <h3>Field Pattern Recognition</h3>
        <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>✓ Email fields → realistic email addresses
        ✓ Name fields → proper names with cultural diversity
        ✓ Address fields → valid addresses with proper formatting
        ✓ Phone fields → valid phone numbers with regional patterns
        ✓ Date fields → realistic date ranges and patterns
        ✓ URL fields → valid URLs and domain patterns</code></pre>

        <h3>Relationship Handling</h3>
        <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>✓ Foreign key consistency
        ✓ Many-to-many relationship seeding
        ✓ Polymorphic relationship support
        ✓ Circular dependency resolution
        ✓ Orphaned record prevention</code></pre>

        <h2>Template Configuration</h2>
        <p>Create custom data generation templates:</p>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <h4 class="font-semibold">Template Features:</h4>
            <ul>
                <li><strong>Field-Specific Rules:</strong> Define generation rules for specific fields</li>
                <li><strong>Business Logic:</strong> Incorporate complex business rules</li>
                <li><strong>Data Relationships:</strong> Define how related data should be generated</li>
                <li><strong>Validation Rules:</strong> Ensure generated data meets validation requirements</li>
            </ul>
        </div>

        <h2>Seeding Strategies</h2>
        <p>Multiple seeding strategies for different needs:</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-md">
                <h4 class="font-semibold">Fresh Seeding</h4>
                <p>Complete database seeding from scratch</p>
                <ul class="text-sm mt-2">
                    <li>Truncates existing data</li>
                    <li>Generates fresh dataset</li>
                    <li>Ensures data consistency</li>
                </ul>
            </div>
            <div class="bg-blue-50 p-4 rounded-md">
                <h4 class="font-semibold">Incremental Seeding</h4>
                <p>Add data to existing datasets</p>
                <ul class="text-sm mt-2">
                    <li>Preserves existing data</li>
                    <li>Avoids duplicate entries</li>
                    <li>Maintains referential integrity</li>
                </ul>
            </div>
        </div>

        <h2>Configuration</h2>
        <p>Configure data seeding options:</p>

        <pre class="bg-gray-800 text-white p-4 rounded-md"><code>'features' => [
            'data_seeding' => true,
        ],

        'data_seeding' => [
            'default_count' => 50,
            'batch_size' => 1000,
            'locales' => ['en_US', 'en_GB', 'es_ES'],
            'templates' => [
                'users' => [
                    'name' => 'realistic_name',
                    'email' => 'unique_email',
                    'phone' => 'regional_phone',
                ],
            ],
            'relationships' => [
                'auto_discover' => true,
                'respect_constraints' => true,
                'handle_polymorphic' => true,
            ],
        ],</code></pre>

        <h2>Usage Examples</h2>
        <p>Generate data programmatically:</p>

        <pre class="bg-gray-800 text-white p-4 rounded-md"><code>use HkDevs\CodeForgeStudio\Services\DataGenerationService;

        $generator = app(DataGenerationService::class);

        // Generate data for a specific table
        $data = $generator->generateForTable('users', 100);

        // Generate data with custom template
        $data = $generator->generateWithTemplate('users', [
            'name' => 'realistic_name',
            'email' => 'company_email',
            'role' => 'weighted_choice:admin:5,user:95'
        ]);

        // Bulk seed multiple tables
        $generator->seedMultipleTables([
            'users' => 100,
            'posts' => 500,
            'comments' => 1000
        ]);</code></pre>

        <h2>Data Types Support</h2>
        <p>Comprehensive support for all common data types:</p>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 border-b font-semibold">Data Type</th>
                        <th class="px-4 py-2 border-b font-semibold">Generation Pattern</th>
                        <th class="px-4 py-2 border-b font-semibold">Examples</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border-b">String</td>
                        <td class="px-4 py-2 border-b">Context-aware patterns</td>
                        <td class="px-4 py-2 border-b">Names, addresses, descriptions</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-4 py-2 border-b">Integer</td>
                        <td class="px-4 py-2 border-b">Range-based generation</td>
                        <td class="px-4 py-2 border-b">IDs, counts, quantities</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border-b">DateTime</td>
                        <td class="px-4 py-2 border-b">Realistic date ranges</td>
                        <td class="px-4 py-2 border-b">Created dates, birthdays</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-4 py-2 border-b">Boolean</td>
                        <td class="px-4 py-2 border-b">Weighted distribution</td>
                        <td class="px-4 py-2 border-b">Flags, status indicators</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>Benefits</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="bg-green-50 p-4 rounded-md">
                <h4 class="font-semibold text-green-800">Realistic Data</h4>
                <p>Generate realistic test data that mimics production patterns</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-md">
                <h4 class="font-semibold text-blue-800">Relationship Integrity</h4>
                <p>Automatically maintain referential integrity across all tables</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-md">
                <h4 class="font-semibold text-purple-800">Performance Optimized</h4>
                <p>Efficient bulk operations for large-scale data generation</p>
            </div>
            <div class="bg-orange-50 p-4 rounded-md">
                <h4 class="font-semibold text-orange-800">Customizable</h4>
                <p>Flexible templates and patterns for any data requirement</p>
            </div>
        </div>
    </div>
@endsection