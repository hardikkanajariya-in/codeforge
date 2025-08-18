@extends('codeforge-studio::layout.docs')

@section('title', 'Schema Designer - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Schema Designer</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection


@section('title', 'Schema Designer & Analysis')

@section('content')
    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center space-x-4 mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Schema Designer & Analysis</h1>
                    <p class="text-xl text-gray-600">Advanced schema analysis with automatic relationship discovery and
                        optimization recommendations</p>
                </div>
            </div>
        </div>

        <h3>Performance Analysis</h3>
        <div class="bg-blue-50 p-4 rounded-md">
            <ul>
                <li><strong>Index Analysis:</strong> Comprehensive index usage and effectiveness analysis</li>
                <li><strong>Query Performance:</strong> Analysis of query patterns and performance bottlenecks</li>
                <li><strong>Table Statistics:</strong> Detailed table size, row count, and growth analysis</li>
                <li><strong>Optimization Recommendations:</strong> Automated suggestions for performance improvements</li>
            </ul>
        </div>

        <h3>Schema Visualization</h3>
        <div class="bg-green-50 p-4 rounded-md">
            <ul>
                <li><strong>Interactive ERD:</strong> Interactive Entity Relationship Diagrams</li>
                <li><strong>Dependency Graphs:</strong> Visual representation of table dependencies</li>
                <li><strong>Relationship Mapping:</strong> Clear visualization of all database relationships</li>
                <li><strong>Schema Navigation:</strong> Easy navigation through complex database structures</li>
            </ul>
        </div>

        <h2>Schema Analysis Tools</h2>
        <p>Comprehensive tools for understanding your database structure:</p>

        <h3>Relationship Analysis</h3>
        <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>? Foreign key relationship discovery
        ? Implicit relationship detection
        ? Circular dependency identification
        ? Orphaned record detection
        ? Referential integrity validation</code></pre>

        <h3>Performance Insights</h3>
        <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>? Index utilization analysis
        ? Query performance patterns
        ? Table fragmentation assessment
        ? Storage optimization opportunities
        ? Query optimization recommendations</code></pre>

        <h2>Schema Optimization</h2>
        <p>Get intelligent recommendations for schema improvements:</p>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <ul>
                <li><strong>Index Recommendations:</strong> Suggestions for new indexes based on query patterns</li>
                <li><strong>Normalization Analysis:</strong> Identification of normalization opportunities</li>
                <li><strong>Data Type Optimization:</strong> Recommendations for optimal data types</li>
                <li><strong>Constraint Validation:</strong> Suggestions for additional constraints and validations</li>
            </ul>
        </div>

        <h2>Relationship Discovery Engine</h2>
        <p>Advanced relationship detection capabilities:</p>

        <div class="bg-blue-100 p-4 rounded-md">
            <h4 class="font-semibold">Discovery Methods:</h4>
            <ul>
                <li><strong>Explicit Relationships:</strong> Foreign key constraints and declared relationships</li>
                <li><strong>Naming Conventions:</strong> Relationships inferred from naming patterns</li>
                <li><strong>Data Patterns:</strong> Relationships discovered through data analysis</li>
                <li><strong>Query Analysis:</strong> Relationships identified from JOIN patterns in queries</li>
            </ul>
        </div>

        <h2>Configuration</h2>
        <p>Configure schema analysis in your settings:</p>

        <pre class="bg-gray-800 text-white p-4 rounded-md"><code>'features' => [
            'schema_designer' => true,
        ],

        'schema_analysis' => [
            'auto_discovery' => true,
            'relationship_detection' => [
                'foreign_keys' => true,
                'naming_conventions' => true,
                'data_patterns' => true,
                'query_analysis' => false,
            ],
            'performance_analysis' => [
                'index_analysis' => true,
                'query_patterns' => true,
                'optimization_suggestions' => true,
            ],
            'cache_duration' => 3600, // 1 hour
        ],</code></pre>

        <h2>Usage Examples</h2>
        <p>Access schema analysis programmatically:</p>

        <pre class="bg-gray-800 text-white p-4 rounded-md"><code>use HkDevs\CodeForgeStudio\Services\SchemaAnalyzerService;

        $analyzer = app(SchemaAnalyzerService::class);

        // Discover all relationships
        $relationships = $analyzer->discoverRelationships();

        // Analyze table performance
        $performance = $analyzer->analyzeTablePerformance('users');

        // Get optimization recommendations
        $recommendations = $analyzer->getOptimizationRecommendations();

        // Generate schema summary
        $summary = $analyzer->generateSchemaSummary();</code></pre>

        <h2>Schema Reports</h2>
        <p>Generate comprehensive schema analysis reports:</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded-md">
                <h4 class="font-semibold">Relationship Report</h4>
                <p>Complete mapping of all table relationships and dependencies</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-md">
                <h4 class="font-semibold">Performance Report</h4>
                <p>Detailed analysis of query performance and optimization opportunities</p>
            </div>
            <div class="bg-green-50 p-4 rounded-md">
                <h4 class="font-semibold">Health Report</h4>
                <p>Overall schema health assessment with improvement suggestions</p>
            </div>
        </div>

        <h2>Benefits</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="bg-green-50 p-4 rounded-md">
                <h4 class="font-semibold text-green-800">Automatic Discovery</h4>
                <p>Automatically discover hidden relationships and dependencies</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-md">
                <h4 class="font-semibold text-blue-800">Performance Optimization</h4>
                <p>Get intelligent recommendations for schema performance improvements</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-md">
                <h4 class="font-semibold text-purple-800">Visual Understanding</h4>
                <p>Clear visualization of complex database relationships</p>
            </div>
            <div class="bg-orange-50 p-4 rounded-md">
                <h4 class="font-semibold text-orange-800">Integrity Validation</h4>
                <p>Ensure data integrity with comprehensive validation checks</p>
            </div>
        </div>
    </div>
@endsection
