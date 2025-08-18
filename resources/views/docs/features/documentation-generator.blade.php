@extends('codeforge-studio::layout.docs')

@section('title', 'Documentation Generator - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Documentation Generator</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection


@section('title', 'Documentation Generator')

@section('content')
    <div class="prose max-w-none">
        <h1>Documentation Generator</h1>

        <p>CodeForge Database Studio provides advanced database schema documentation and snapshot management capabilities
            with versioning and change tracking.</p>

        <h2>Overview</h2>
        <p>The Documentation Generator creates comprehensive schema documentation with automatic relationship discovery,
            validation rule extraction, and multi-format export capabilities.</p>

        <h2>Key Features</h2>

        <h3>Comprehensive Schema Documentation</h3>
        <div class="bg-gray-50 p-4 rounded-md">
            <ul>
                <li><strong>Complete Table Documentation:</strong> Detailed table structure with columns, indexes, and
                    constraints</li>
                <li><strong>Relationship Mapping:</strong> Comprehensive relationship documentation with visual
                    representation</li>
                <li><strong>Model Integration:</strong> Laravel Eloquent model detection and relationship mapping</li>
                <li><strong>Validation Documentation:</strong> Extraction and documentation of validation rules and
                    constraints</li>
            </ul>
        </div>

        <h3>Schema Snapshot Management</h3>
        <div class="bg-blue-50 p-4 rounded-md">
            <ul>
                <li><strong>Schema Snapshots:</strong> Complete database schema capture with metadata and statistics</li>
                <li><strong>Version Control:</strong> Schema versioning with change tracking and rollback capabilities</li>
                <li><strong>Change Detection:</strong> Automatic detection of schema changes with detailed diff generation
                </li>
                <li><strong>Historical Tracking:</strong> Long-term schema evolution tracking with trend analysis</li>
            </ul>
        </div>

        <h3>Advanced Documentation Features</h3>
        <div class="bg-green-50 p-4 rounded-md">
            <ul>
                <li><strong>Multi-Format Export:</strong> Export to Markdown, HTML, PDF, and JSON formats</li>
                <li><strong>Custom Templates:</strong> Customizable documentation templates with branding options</li>
                <li><strong>Security Analysis:</strong> Security assessment of schema design and access patterns</li>
                <li><strong>Compliance Reporting:</strong> Automated compliance reports for audit requirements</li>
            </ul>
        </div>

        <h2>Documentation Types</h2>
        <p>Generate various types of documentation:</p>

        <h3>Schema Documentation</h3>
        <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>? Complete table structure documentation
    ? Column details with data types and constraints
    ? Index and key documentation
    ? Foreign key relationships mapping
    ? Database statistics and metrics</code></pre>

        <h3>Model Integration</h3>
        <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>? Eloquent model discovery and mapping
    ? Relationship documentation with types
    ? Attribute documentation with casts
    ? Scope and accessor documentation
    ? Factory integration patterns</code></pre>

        <h2>Export Formats</h2>
        <p>Multiple export formats for different use cases:</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-md">
                <h4 class="font-semibold">Markdown</h4>
                <ul class="text-sm mt-2">
                    <li>GitHub/GitLab compatible</li>
                    <li>Version control friendly</li>
                    <li>Easy to maintain and update</li>
                </ul>
            </div>
            <div class="bg-blue-50 p-4 rounded-md">
                <h4 class="font-semibold">HTML</h4>
                <ul class="text-sm mt-2">
                    <li>Interactive navigation</li>
                    <li>Searchable content</li>
                    <li>Professional presentation</li>
                </ul>
            </div>
            <div class="bg-green-50 p-4 rounded-md">
                <h4 class="font-semibold">PDF</h4>
                <ul class="text-sm mt-2">
                    <li>Professional reports</li>
                    <li>Print-ready format</li>
                    <li>Compliance documentation</li>
                </ul>
            </div>
            <div class="bg-purple-50 p-4 rounded-md">
                <h4 class="font-semibold">JSON</h4>
                <ul class="text-sm mt-2">
                    <li>Machine-readable format</li>
                    <li>API integration</li>
                    <li>Data exchange</li>
                </ul>
            </div>
        </div>

        <h2>Snapshot Management</h2>
        <p>Advanced schema versioning and change tracking:</p>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <h4 class="font-semibold">Snapshot Features:</h4>
            <ul>
                <li><strong>Baseline Management:</strong> Establish and manage schema baselines for migration</li>
                <li><strong>Change Detection:</strong> Automatic detection of schema changes with impact analysis</li>
                <li><strong>Diff Generation:</strong> Detailed comparison between schema snapshots</li>
                <li><strong>Evolution Tracking:</strong> Track schema evolution over time with trends</li>
            </ul>
        </div>

        <h2>Configuration</h2>
        <p>Configure documentation generation settings:</p>

        <pre class="bg-gray-800 text-white p-4 rounded-md"><code>'features' => [
        'documentation_generator' => true,
    ],

    'documentation' => [
        'auto_generate' => true,
        'include_models' => true,
        'include_relationships' => true,
        'include_validation' => true,
        'export_formats' => ['markdown', 'html', 'pdf'],
        'templates' => [
            'default' => 'comprehensive',
            'custom_branding' => true,
            'include_diagrams' => true,
        ],
        'snapshots' => [
            'auto_create' => true,
            'retention_days' => 90,
            'diff_tracking' => true,
        ],
    ],</code></pre>

        <h2>Usage Examples</h2>
        <p>Generate documentation programmatically:</p>

        <pre class="bg-gray-800 text-white p-4 rounded-md"><code>use HkDevs\CodeForgeStudio\Services\SchemaDocumentationService;

    $generator = app(SchemaDocumentationService::class);

    // Generate complete schema documentation
    $documentation = $generator->generateSchemaDocumentation();

    // Create schema snapshot
    $snapshot = $generator->createSchemaSnapshot([
        'name' => 'Production Baseline',
        'description' => 'Schema baseline for production deployment'
    ]);

    // Compare schemas
    $diff = $generator->compareSchemas($snapshotA, $snapshotB);

    // Export documentation
    $generator->exportDocumentation('markdown', [
        'include_models' => true,
        'include_relationships' => true,
        'file_path' => 'docs/database-schema.md'
    ]);</code></pre>

        <h2>Documentation Templates</h2>
        <p>Flexible template system for customized documentation:</p>

        <div class="bg-blue-100 p-4 rounded-md">
            <h4 class="font-semibold">Template Options:</h4>
            <ul>
                <li><strong>Comprehensive:</strong> Complete documentation with all available information</li>
                <li><strong>Summary:</strong> High-level overview with key relationships and statistics</li>
                <li><strong>Technical:</strong> Detailed technical documentation for developers</li>
                <li><strong>Business:</strong> Business-focused documentation for stakeholders</li>
                <li><strong>Compliance:</strong> Audit and compliance-focused documentation</li>
                <li><strong>Custom:</strong> User-defined templates with custom sections and branding</li>
            </ul>
        </div>

        <h2>Integration Capabilities</h2>
        <p>Seamless integration with development workflows:</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-md">
                <h4 class="font-semibold">Version Control</h4>
                <ul class="text-sm mt-2">
                    <li>Git integration for documentation versioning</li>
                    <li>Automated documentation updates</li>
                    <li>Change tracking and history</li>
                </ul>
            </div>
            <div class="bg-green-50 p-4 rounded-md">
                <h4 class="font-semibold">CI/CD Integration</h4>
                <ul class="text-sm mt-2">
                    <li>Automated documentation generation</li>
                    <li>Schema validation in pipelines</li>
                    <li>Deployment documentation updates</li>
                </ul>
            </div>
        </div>

        <h2>Benefits</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="bg-green-50 p-4 rounded-md">
                <h4 class="font-semibold text-green-800">Automated Documentation</h4>
                <p>Generate comprehensive documentation automatically from your schema</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-md">
                <h4 class="font-semibold text-blue-800">Version Control</h4>
                <p>Track schema changes and evolution over time</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-md">
                <h4 class="font-semibold text-purple-800">Multiple Formats</h4>
                <p>Export documentation in various formats for different audiences</p>
            </div>
            <div class="bg-orange-50 p-4 rounded-md">
                <h4 class="font-semibold text-orange-800">Team Collaboration</h4>
                <p>Improve team understanding with clear, up-to-date documentation</p>
            </div>
        </div>
    </div>
@endsection
