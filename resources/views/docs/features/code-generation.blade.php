@extends('codeforge-studio::layout.docs')
@extends('codeforge-studio::layout.docs')

@section('title', 'Code Generation - CodeForge Database Studio')

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
    <li class='text-primary-600 font-medium'>Code Generation</li>
@endsection

@section('navigation')
    @include('codeforge-studio::docs.partials.navigation')
@endsection


@section('title', 'Code Generation')

@section('content')
    <div class="prose max-w-none">
        <h1>Code Generation</h1>

        <p>CodeForge Database Studio provides comprehensive Laravel code generation capabilities with intelligent dependency
            management and transactional operations.</p>

        <h2>Overview</h2>
        <p>The Code Generation system automatically generates complete Laravel application components including models,
            migrations, factories, seeders, and more with intelligent dependency resolution.</p>

        <h2>Key Features</h2>

        <h3>Complete Component Generation</h3>
        <div class="bg-gray-50 p-4 rounded-md">
            <ul>
                <li><strong>Database Migrations:</strong> Complete table creation with columns, indexes, and constraints
                </li>
                <li><strong>Eloquent Models:</strong> Full model generation with relationships and attribute casting</li>
                <li><strong>Model Factories:</strong> Realistic data generation with Faker integration</li>
                <li><strong>Database Seeders:</strong> Data population with dependency-aware execution</li>
                <li><strong>Policy Classes:</strong> Authorization logic with resource-based permissions</li>
                <li><strong>Request Classes:</strong> Form validation with comprehensive rule sets</li>
                <li><strong>Controller Classes:</strong> RESTful controllers with standard CRUD operations</li>
            </ul>
        </div>

        <h3>Intelligent Dependency Management</h3>
        <div class="bg-blue-50 p-4 rounded-md">
            <ul>
                <li><strong>Dependency Resolution:</strong> Automatic detection and ordering of dependencies</li>
                <li><strong>Generation Ordering:</strong> Intelligent ordering to respect dependencies</li>
                <li><strong>Conflict Detection:</strong> Detection and resolution of naming conflicts</li>
                <li><strong>Relationship Mapping:</strong> Automatic relationship generation based on schema</li>
            </ul>
        </div>

        <h3>Transactional Operations</h3>
        <div class="bg-green-50 p-4 rounded-md">
            <ul>
                <li><strong>Atomic Generation:</strong> All-or-nothing generation with rollback capabilities</li>
                <li><strong>File Backup:</strong> Automatic backup of existing files before overwriting</li>
                <li><strong>Error Recovery:</strong> Comprehensive error handling with diagnostic information</li>
                <li><strong>Generation History:</strong> Complete tracking of generation operations</li>
            </ul>
        </div>

        <h2>Generation Workflow</h2>
        <p>The generation process follows a structured workflow:</p>

        <div class="bg-gray-100 p-4 rounded-md">
            <ol class="list-decimal list-inside space-y-2">
                <li><strong>Configuration Validation:</strong> Validate input parameters and settings</li>
                <li><strong>Dependency Analysis:</strong> Analyze dependencies and determine generation order</li>
                <li><strong>Template Processing:</strong> Process templates with dynamic content injection</li>
                <li><strong>File Generation:</strong> Create files with proper formatting and structure</li>
                <li><strong>Validation:</strong> Validate generated code syntax and structure</li>
                <li><strong>Transaction Commit:</strong> Finalize generation with history logging</li>
            </ol>
        </div>

        <h2>Component Types</h2>
        <p>Generate a wide variety of Laravel components:</p>

        <h3>Database Components</h3>
        <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>? Database migrations with complete schema definition
    ? Eloquent models with relationships and attributes
    ? Model factories with realistic data generation
    ? Database seeders with dependency management
    ? Pivot table handling for many-to-many relationships</code></pre>

        <h3>Application Components</h3>
        <pre class="bg-gray-800 text-green-400 p-4 rounded-md"><code>? RESTful controllers with CRUD operations
    ? Form request classes with validation rules
    ? Policy classes with authorization logic
    ? Resource classes for API transformations
    ? Event and listener classes for decoupled architecture</code></pre>

        <h2>Generation Templates</h2>
        <p>Flexible template system for customized code generation:</p>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <h4 class="font-semibold">Template Features:</h4>
            <ul>
                <li><strong>Customizable Templates:</strong> Modify generation templates to match your coding standards</li>
                <li><strong>Dynamic Content:</strong> Templates support dynamic content injection</li>
                <li><strong>Conditional Logic:</strong> Include conditional logic in templates</li>
                <li><strong>Multiple Formats:</strong> Support for different file formats and structures</li>
            </ul>
        </div>

        <h2>Safety Features</h2>
        <p>Comprehensive safety measures for reliable code generation:</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-red-50 p-4 rounded-md">
                <h4 class="font-semibold text-red-800">File Protection</h4>
                <ul class="text-sm mt-2">
                    <li>Automatic backup creation</li>
                    <li>Conflict detection and resolution</li>
                    <li>Preview mode for impact assessment</li>
                </ul>
            </div>
            <div class="bg-blue-50 p-4 rounded-md">
                <h4 class="font-semibold text-blue-800">Error Handling</h4>
                <ul class="text-sm mt-2">
                    <li>Comprehensive error reporting</li>
                    <li>Rollback capabilities</li>
                    <li>Diagnostic information collection</li>
                </ul>
            </div>
        </div>

        <h2>Configuration</h2>
        <p>Configure code generation settings:</p>

        <pre class="bg-gray-800 text-white p-4 rounded-md"><code>'features' => [
        'code_generation' => true,
    ],

    'code_generation' => [
        'backup_files' => true,
        'validate_syntax' => true,
        'templates' => [
            'model' => 'default',
            'migration' => 'default',
            'factory' => 'default',
            'seeder' => 'default',
        ],
        'generation_paths' => [
            'models' => 'app/Models',
            'migrations' => 'database/migrations',
            'factories' => 'database/factories',
            'seeders' => 'database/seeders',
        ],
        'naming_conventions' => [
            'model' => 'PascalCase',
            'table' => 'snake_case',
            'field' => 'snake_case',
        ],
    ],</code></pre>

        <h2>Usage Examples</h2>
        <p>Generate code programmatically:</p>

        <pre class="bg-gray-800 text-white p-4 rounded-md"><code>use HkDevs\CodeForgeStudio\Services\CodeGenerationService;

    $generator = app(CodeGenerationService::class);

    // Generate complete component set
    $result = $generator->generateComplete([
        'migration' => ['name' => 'create_posts_table', 'table' => 'posts'],
        'model' => ['name' => 'Post', 'table' => 'posts'],
        'factory' => ['name' => 'PostFactory', 'model' => 'Post'],
        'seeder' => ['name' => 'PostSeeder', 'model' => 'Post'],
    ]);

    // Generate individual components
    $generator->generateMigration('create_users_table', [
        'columns' => [
            'name' => 'string',
            'email' => 'string:unique',
            'password' => 'string',
        ]
    ]);

    // Generate model with relationships
    $generator->generateModel('User', [
        'relationships' => [
            'posts' => 'hasMany:Post',
            'profile' => 'hasOne:Profile',
        ]
    ]);</code></pre>

        <h2>Generation History</h2>
        <p>Track and manage your generation history:</p>

        <div class="bg-blue-100 p-4 rounded-md">
            <h4 class="font-semibold">History Features:</h4>
            <ul>
                <li><strong>Unique Tracking:</strong> Each generation gets a unique identifier</li>
                <li><strong>User Attribution:</strong> Track which user performed the generation</li>
                <li><strong>Timestamp Logging:</strong> Detailed timestamps for all operations</li>
                <li><strong>Success/Failure Tracking:</strong> Monitor generation success rates</li>
                <li><strong>File Listing:</strong> Complete list of generated files</li>
                <li><strong>Rollback Support:</strong> Ability to rollback failed generations</li>
            </ul>
        </div>

        <h2>Benefits</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="bg-green-50 p-4 rounded-md">
                <h4 class="font-semibold text-green-800">Rapid Development</h4>
                <p>Generate complete Laravel components in seconds</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-md">
                <h4 class="font-semibold text-blue-800">Consistent Code</h4>
                <p>Ensure consistent code structure across your project</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-md">
                <h4 class="font-semibold text-purple-800">Error Prevention</h4>
                <p>Reduce human errors with automated generation</p>
            </div>
            <div class="bg-orange-50 p-4 rounded-md">
                <h4 class="font-semibold text-orange-800">Best Practices</h4>
                <p>Generated code follows Laravel best practices and conventions</p>
            </div>
        </div>
    </div>
@endsection
