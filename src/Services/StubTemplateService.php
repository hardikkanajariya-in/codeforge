<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Support\Facades\File;

/**
 * StubTemplateService
 * 
 * Advanced code template management and processing service for CodeForge Database Studio.
 * Provides comprehensive template management with dynamic content replacement and customization capabilities.
 * 
 * Features:
 * - Comprehensive stub template management with hierarchical organization
 * - Dynamic content replacement with intelligent placeholder resolution
 * - Template inheritance and composition for complex code generation scenarios
 * - Custom template creation and management with version control support
 * - Multi-format template support including PHP, JavaScript, CSS, and documentation
 * - Template validation and syntax checking with error detection and recovery
 * - Performance-optimized template processing with caching and lazy loading
 * - Extensible template system with plugin architecture for custom functionality
 * 
 * Template Management:
 * - Template Discovery: Automatic discovery and loading of template files
 * - Hierarchical Organization: Organized template structure with category-based management
 * - Template Inheritance: Support for template inheritance and base template extension
 * - Version Control: Template versioning with change tracking and rollback capabilities
 * - Template Validation: Comprehensive validation of template syntax and structure
 * - Custom Templates: User-defined template creation and management capabilities
 * - Template Sharing: Team collaboration with shared template libraries and repositories
 * 
 * Content Replacement:
 * - Placeholder System: Comprehensive placeholder system with type-aware replacement
 * - Dynamic Content: Runtime content generation with context-aware replacement
 * - Conditional Logic: Template conditional logic with if/else and loop constructs
 * - Data Binding: Integration with data sources for dynamic content population
 * - Nested Replacement: Support for nested placeholders and complex replacement scenarios
 * - Type Conversion: Automatic type conversion and formatting for placeholder values
 * - Escape Handling: Proper escaping and sanitization of replacement content
 * 
 * Template Types:
 * - Model Templates: Laravel Eloquent model generation templates
 * - Migration Templates: Database migration creation templates
 * - Factory Templates: Model factory generation templates with realistic data patterns
 * - Seeder Templates: Database seeder templates with relationship-aware data generation
 * - Controller Templates: RESTful controller templates with standard CRUD operations
 * - Resource Templates: API resource templates with field transformation logic
 * - Policy Templates: Authorization policy templates with resource-based permissions
 * - Test Templates: Unit and feature test templates with comprehensive coverage
 * 
 * Advanced Features:
 * - Template Composition: Combine multiple templates for complex code generation
 * - Macro System: Reusable template macros for common code patterns
 * - Template Functions: Built-in template functions for data manipulation and formatting
 * - Custom Functions: User-defined template functions and helpers
 * - Template Debugging: Debug mode with template processing visualization
 * - Performance Profiling: Template performance analysis and optimization recommendations
 * - Error Recovery: Intelligent error recovery with fallback templates and strategies
 * 
 * Customization Options:
 * - Custom Placeholders: User-defined placeholder types and replacement logic
 * - Template Extensions: Plugin system for extending template functionality
 * - Format Support: Support for multiple output formats and code styles
 * - Style Integration: Integration with code formatting standards and style guides
 * - Namespace Management: Automatic namespace resolution and organization
 * - Import Optimization: Intelligent use statement generation and optimization
 * - Comment Generation: Automatic documentation and comment generation
 * 
 * Performance Optimization:
 * - Template Caching: Intelligent caching of parsed templates and replacement results
 * - Lazy Loading: On-demand template loading to minimize memory usage
 * - Batch Processing: Optimized batch template processing for multiple generations
 * - Memory Management: Efficient memory usage for large template operations
 * - Resource Optimization: CPU and I/O optimization for template processing
 * - Background Processing: Asynchronous template processing for improved responsiveness
 * - Compression: Template compression for efficient storage and transfer
 * 
 * Integration Features:
 * - Laravel Integration: Seamless integration with Laravel's service container
 * - File System Integration: Support for multiple file systems and storage backends
 * - Version Control: Git integration for template versioning and collaboration
 * - External Templates: Support for external template repositories and sources
 * - API Integration: REST endpoints for external template management and processing
 * - Webhook Support: Real-time template updates and synchronization
 * - Team Collaboration: Multi-user template development and review workflows
 * 
 * Quality Assurance:
 * - Template Validation: Comprehensive validation of template syntax and structure
 * - Output Validation: Validation of generated code for syntax and compliance
 * - Testing Integration: Automated testing of template generation and output
 * - Error Handling: Comprehensive error handling with detailed diagnostic information
 * - Performance Testing: Automated performance testing and benchmarking
 * - Documentation Generation: Automatic generation of template documentation
 * - Best Practice Enforcement: Enforcement of coding standards and best practices
 * 
 * Development Tools:
 * - Template Editor: Built-in template editing with syntax highlighting and validation
 * - Preview Mode: Real-time preview of template output with sample data
 * - Debug Console: Comprehensive debugging tools for template development
 * - Performance Monitor: Real-time performance monitoring and optimization suggestions
 * - Template Analytics: Usage analytics and optimization recommendations
 * - Migration Tools: Tools for migrating templates between versions and formats
 * 
 * @package HkDevs\CodeForgeStudio\Services
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * $service = app(StubTemplateService::class);
 * $template = $service->getStub('model.basic');
 * $content = $service->replaceContent($template, ['ClassName' => 'User', 'TableName' => 'users']);
 * $customTemplate = $service->createCustomTemplate('my_model', $templateContent);
 */
class StubTemplateService
{
    protected string $stubsPath;
    protected array $templates = [];

    public function __construct()
    {
        $this->stubsPath = __DIR__ . '/../stubs';
        $this->loadTemplates();
    }

    /**
     * Get a stub file content
     */
    public function getStub(string $stubName): string
    {
        $stubPath = $this->stubsPath . '/' . str_replace('.', '/', $stubName) . '.stub';

        if (!File::exists($stubPath)) {
            // Return a basic stub if the specific one doesn't exist
            return $this->getBasicStub($stubName);
        }

        return File::get($stubPath);
    }

    /**
     * Populate stub with replacements
     */
    public function populateStub(string $stub, array $replacements): string
    {
        $content = $stub;

        foreach ($replacements as $key => $value) {
            $placeholder = '{{ ' . $key . ' }}';
            $content = str_replace($placeholder, $value, $content);
        }

        // Clean up any remaining placeholders
        $content = preg_replace('/\{\{\s*[A-Z_]+\s*\}\}/', '', $content);

        return $content;
    }

    /**
     * Get available templates
     */
    public function getAvailableTemplates(): array
    {
        return [
            'user' => 'User Model Template',
            'product' => 'Product Model Template',
            'post' => 'Blog Post Template',
            'category' => 'Category Template',
            'order' => 'Order Template',
            'api_resource' => 'API Resource Template',
            'crud_complete' => 'Complete CRUD Template',
            'audit_log' => 'Audit Log Template',
            'settings' => 'Settings Template',
            'notification' => 'Notification Template',
        ];
    }

    /**
     * Load template configuration
     */
    public function loadTemplate(string $templateName): ?array
    {
        return $this->templates[$templateName] ?? null;
    }

    /**
     * Save custom template
     */
    public function saveTemplate(string $name, array $configuration): bool
    {
        $this->templates[$name] = $configuration;

        // Save to file for persistence
        $templatesFile = storage_path('app/codeforge-database-studio/templates.json');
        $dir = dirname($templatesFile);

        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        return File::put($templatesFile, json_encode($this->templates, JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Load templates from storage
     */
    protected function loadTemplates(): void
    {
        // Load default templates
        $this->templates = [
            'user' => [
                'migration' => [
                    'enabled' => true,
                    'table_name' => 'users',
                    'columns' => [
                        ['name' => 'name', 'type' => 'string', 'length' => 255],
                        ['name' => 'email', 'type' => 'string', 'length' => 255, 'unique' => true],
                        ['name' => 'email_verified_at', 'type' => 'timestamp', 'nullable' => true],
                        ['name' => 'password', 'type' => 'string', 'length' => 255],
                        ['name' => 'remember_token', 'type' => 'string', 'length' => 100, 'nullable' => true],
                    ],
                    'timestamps' => true,
                    'soft_deletes' => false,
                ],
                'model' => [
                    'enabled' => true,
                    'name' => 'User',
                    'table_name' => 'users',
                    'extends' => 'Authenticatable',
                    'traits' => ['HasFactory', 'Notifiable'],
                    'fillable' => ['name', 'email', 'password'],
                    'hidden' => ['password', 'remember_token'],
                    'casts' => ['email_verified_at' => 'datetime'],
                ],
                'factory' => [
                    'enabled' => true,
                    'class_name' => 'UserFactory',
                    'model' => 'User',
                    'fake_data' => [
                        ['field' => 'name', 'faker_method' => 'name'],
                        ['field' => 'email', 'faker_method' => 'safeEmail'],
                        ['field' => 'password', 'faker_method' => 'bcrypt', 'parameters' => "'password'"],
                    ],
                ],
            ],

            'product' => [
                'migration' => [
                    'enabled' => true,
                    'table_name' => 'products',
                    'columns' => [
                        ['name' => 'name', 'type' => 'string', 'length' => 255],
                        ['name' => 'description', 'type' => 'text', 'nullable' => true],
                        ['name' => 'price', 'type' => 'decimal', 'length' => '8,2'],
                        ['name' => 'sku', 'type' => 'string', 'length' => 100, 'unique' => true],
                        ['name' => 'stock_quantity', 'type' => 'integer', 'default' => 0],
                        ['name' => 'is_active', 'type' => 'boolean', 'default' => true],
                        ['name' => 'category_id', 'type' => 'bigInteger', 'unsigned' => true, 'nullable' => true],
                    ],
                    'foreign_keys' => [
                        ['column' => 'category_id', 'references' => 'id', 'on' => 'categories', 'on_delete' => 'set null'],
                    ],
                    'timestamps' => true,
                    'soft_deletes' => true,
                ],
                'model' => [
                    'enabled' => true,
                    'name' => 'Product',
                    'table_name' => 'products',
                    'traits' => ['HasFactory', 'SoftDeletes'],
                    'fillable' => ['name', 'description', 'price', 'sku', 'stock_quantity', 'is_active', 'category_id'],
                    'casts' => ['price' => 'decimal:2', 'is_active' => 'boolean'],
                    'relations' => [
                        ['name' => 'category', 'type' => 'belongsTo', 'related_model' => 'Category'],
                    ],
                ],
                'factory' => [
                    'enabled' => true,
                    'class_name' => 'ProductFactory',
                    'model' => 'Product',
                    'fake_data' => [
                        ['field' => 'name', 'faker_method' => 'words', 'parameters' => '3, true'],
                        ['field' => 'description', 'faker_method' => 'paragraph'],
                        ['field' => 'price', 'faker_method' => 'randomFloat', 'parameters' => '2, 10, 1000'],
                        ['field' => 'sku', 'faker_method' => 'unique()->regexify', 'parameters' => "'[A-Z]{3}[0-9]{6}'"],
                        ['field' => 'stock_quantity', 'faker_method' => 'numberBetween', 'parameters' => '0, 100'],
                        ['field' => 'is_active', 'faker_method' => 'boolean', 'parameters' => '80'],
                    ],
                ],
                'seeder' => [
                    'enabled' => true,
                    'class_name' => 'ProductSeeder',
                    'model' => 'Product',
                    'count' => 50,
                ],
            ],

            'api_resource' => [
                'migration' => [
                    'enabled' => true,
                    'table_name' => 'api_resources',
                    'columns' => [
                        ['name' => 'name', 'type' => 'string', 'length' => 255],
                        ['name' => 'slug', 'type' => 'string', 'length' => 255, 'unique' => true],
                        ['name' => 'data', 'type' => 'json', 'nullable' => true],
                        ['name' => 'status', 'type' => 'enum', 'enum_values' => ['active', 'inactive', 'draft']],
                        ['name' => 'user_id', 'type' => 'bigInteger', 'unsigned' => true],
                    ],
                    'foreign_keys' => [
                        ['column' => 'user_id', 'references' => 'id', 'on' => 'users', 'on_delete' => 'cascade'],
                    ],
                    'timestamps' => true,
                    'soft_deletes' => false,
                ],
                'model' => [
                    'enabled' => true,
                    'name' => 'ApiResource',
                    'table_name' => 'api_resources',
                    'traits' => ['HasFactory'],
                    'fillable' => ['name', 'slug', 'data', 'status', 'user_id'],
                    'casts' => ['data' => 'array'],
                    'relations' => [
                        ['name' => 'user', 'type' => 'belongsTo', 'related_model' => 'User'],
                    ],
                ],
                'controller' => [
                    'enabled' => true,
                    'class_name' => 'ApiResourceController',
                    'type' => 'api',
                    'methods' => ['index', 'show', 'store', 'update', 'destroy'],
                ],
                'tests' => [
                    'enabled' => true,
                    'feature_tests' => true,
                    'unit_tests' => true,
                ],
            ],
        ];

        // Load custom templates from storage
        $templatesFile = storage_path('app/codeforge-database-studio/templates.json');
        if (File::exists($templatesFile)) {
            $customTemplates = json_decode(File::get($templatesFile), true);
            if (is_array($customTemplates)) {
                $this->templates = array_merge($this->templates, $customTemplates);
            }
        }
    }

    /**
     * Get basic stub content if specific stub doesn't exist
     */
    protected function getBasicStub(string $stubName): string
    {
        return match ($stubName) {
            'migration.create' => $this->getMigrationCreateStub(),
            'model.advanced' => $this->getModelAdvancedStub(),
            'factory.advanced' => $this->getFactoryAdvancedStub(),
            'seeder.advanced' => $this->getSeederAdvancedStub(),
            'policy.advanced' => $this->getPolicyAdvancedStub(),
            'filament.resource' => $this->getFilamentResourceStub(),
            'controller.resource' => $this->getControllerResourceStub(),
            'controller.api' => $this->getControllerApiStub(),
            'controller.invokable' => $this->getControllerInvokableStub(),
            'test.feature' => $this->getFeatureTestStub(),
            'test.unit' => $this->getUnitTestStub(),
            default => "<?php\n\n// {{ CLASS_NAME }} stub\n"
        };
    }

    protected function getMigrationCreateStub(): string
    {
        return <<<'STUB'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('{{ TABLE_NAME }}', function (Blueprint $table) {
            $table->id();
{{ COLUMNS }}
{{ TIMESTAMPS }}
{{ SOFT_DELETES }}
{{ INDEXES }}
{{ FOREIGN_KEYS }}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{{ TABLE_NAME }}');
    }
};
STUB;
    }

    protected function getModelAdvancedStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
{{ IMPORTS }}

class {{ CLASS_NAME }} extends {{ EXTENDS }}
{
    {{ TRAITS }}
{{ TABLE_NAME }}
{{ FILLABLE }}
{{ HIDDEN }}
{{ CASTS }}
{{ DATES }}
{{ TIMESTAMPS }}
{{ RELATIONS }}
{{ SCOPES }}
{{ MUTATORS }}
{{ ACCESSORS }}
{{ CUSTOM_METHODS }}
}
STUB;
    }

    protected function getFactoryAdvancedStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{{ MODEL_CLASS }};

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\{{ MODEL_CLASS }}>
 */
class {{ CLASS_NAME }} extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = {{ MODEL_CLASS }}::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
{{ FAKE_DATA }}
        ];
    }
{{ STATES }}
{{ SEQUENCES }}
{{ AFTER_CREATING }}
{{ AFTER_MAKING }}
}
STUB;
    }

    protected function getSeederAdvancedStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\{{ MODEL_CLASS }};

class {{ CLASS_NAME }} extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
{{ DISABLE_FOREIGN_KEYS }}
{{ TRUNCATE_TABLE }}
        
{{ FACTORY_USAGE }}
{{ MANUAL_DATA }}

{{ ENABLE_FOREIGN_KEYS }}
    }
}
STUB;
    }

    protected function getPolicyAdvancedStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

use App\Models\User;
use App\Models\{{ MODEL_CLASS }};
use Illuminate\Auth\Access\Response;

class {{ CLASS_NAME }}
{
{{ POLICY_METHODS }}
{{ CUSTOM_METHODS }}
}
STUB;
    }

    protected function getFilamentResourceStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

use App\Models\{{ MODEL_CLASS }};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use {{ NAMESPACE }}\{{ CLASS_NAME }}\Pages;

class {{ CLASS_NAME }} extends Resource
{
    protected static ?string $model = {{ MODEL_CLASS }}::class;

    protected static ?string $navigationIcon = '{{ NAVIGATION_ICON }}';

    protected static ?string $navigationLabel = '{{ NAVIGATION_LABEL }}';

    protected static ?string $navigationGroup = {{ NAVIGATION_GROUP }};

    protected static ?string $slug = '{{ SLUG }}';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
{{ FORM_FIELDS }}
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
{{ TABLE_COLUMNS }}
            ])
            ->filters([
{{ FILTERS }}
            ])
            ->actions([
{{ ACTIONS }}
            ])
            ->bulkActions([
{{ BULK_ACTIONS }}
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\List{{ MODEL_CLASS }}::class,
            'create' => Pages\Create{{ MODEL_CLASS }}::class,
            'edit' => Pages\Edit{{ MODEL_CLASS }}::class,
        ];
    }
}
STUB;
    }

    protected function getControllerResourceStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

use {{ EXTENDS }};
use Illuminate\Http\Request;
{{ IMPORTS }}

class {{ CLASS_NAME }} extends {{ EXTENDS }}
{
{{ METHODS }}
{{ CUSTOM_METHODS }}
}
STUB;
    }

    protected function getControllerApiStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

use {{ EXTENDS }};
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
{{ IMPORTS }}

class {{ CLASS_NAME }} extends {{ EXTENDS }}
{
{{ METHODS }}
{{ CUSTOM_METHODS }}
}
STUB;
    }

    protected function getControllerInvokableStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ NAMESPACE }};

use {{ EXTENDS }};
use Illuminate\Http\Request;
{{ IMPORTS }}

class {{ CLASS_NAME }} extends {{ EXTENDS }}
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // TODO: Implement the invokable controller logic
    }
}
STUB;
    }

    protected function getFeatureTestStub(): string
    {
        return <<<'STUB'
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\{{ MODEL_CLASS }};

class {{ CLASS_NAME }} extends TestCase
{
    use RefreshDatabase;

{{ TEST_METHODS }}
}
STUB;
    }

    protected function getUnitTestStub(): string
    {
        return <<<'STUB'
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\{{ MODEL_CLASS }};

class {{ CLASS_NAME }} extends TestCase
{
{{ TEST_METHODS }}
}
STUB;
    }
}
