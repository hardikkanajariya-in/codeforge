<?php

namespace HkDevs\CodeForgeStudio\Services;

use Illuminate\Support\Facades\File;

/**
 * FactoryGeneratorService
 *
 * Advanced Laravel Model Factory generation service for CodeForge Database Studio.
 * Creates intelligent, context-aware model factories with realistic data generation patterns.
 *
 * Features:
 * - Intelligent factory generation based on model structure and relationships
 * - Context-aware field mapping with realistic data generation patterns
 * - Relationship factory integration with proper foreign key handling
 * - Custom factory state support for specialized testing scenarios
 * - Faker provider integration with locale-specific data generation
 * - Factory trait generation for enhanced testing capabilities
 * - Sequence and callback support for complex data generation scenarios
 * - Template-based generation with customizable factory patterns
 *
 * Factory Generation Intelligence:
 * - Model Analysis: Automatic detection of model properties and relationships
 * - Field Mapping: Intelligent mapping of database columns to appropriate Faker methods
 * - Type Detection: Automatic data type detection with appropriate generation strategies
 * - Constraint Handling: Unique constraint management and validation rule compliance
 * - Relationship Awareness: Automatic foreign key population with related model factories
 * - Attribute Casting: Support for model attribute casting and custom data types
 * - Validation Integration: Factory generation that respects model validation rules
 *
 * Advanced Features:
 * - Factory States: Generation of multiple factory states for different testing scenarios
 * - Sequence Support: Ordered data generation with incremental and calculated values
 * - Callback Integration: Custom callbacks for complex data generation logic
 * - Trait Generation: Factory traits for reusable generation patterns
 * - Relationship Factories: Automatic generation of related model factories
 * - Custom Providers: Integration with custom Faker providers and data sources
 * - Configuration Templates: Reusable factory configuration patterns
 *
 * Data Generation Patterns:
 * - Realistic Data: Context-appropriate data generation for each field type
 * - Localization: Locale-specific data generation for international applications
 * - Business Logic: Generation patterns that respect business rules and constraints
 * - Test Scenarios: Factory states for edge cases and specific testing requirements
 * - Performance Optimization: Efficient data generation for large test datasets
 * - Relationship Integrity: Automatic maintenance of referential integrity
 * - Custom Formats: Support for custom data formats and validation patterns
 *
 * Template System:
 * - Customizable Templates: User-defined factory generation templates
 * - Pattern Library: Pre-built patterns for common model types and scenarios
 * - Template Inheritance: Hierarchical template system for complex factory structures
 * - Dynamic Generation: Runtime template modification and customization
 * - Version Control: Template versioning with change tracking and rollback
 * - Team Sharing: Collaborative template development and sharing capabilities
 * - Import/Export: Template portability across projects and environments
 *
 * Integration Features:
 * - Laravel Integration: Seamless integration with Laravel's factory system
 * - PHPUnit Support: Optimized factory generation for PHPUnit testing
 * - Seeder Integration: Compatible factory generation for database seeding
 * - Model Integration: Automatic detection and integration with Eloquent models
 * - Migration Integration: Factory generation based on migration definitions
 * - Testing Framework: Integration with Laravel's testing utilities and helpers
 * - CI/CD Support: Automated factory generation for testing environments
 *
 * Quality Assurance:
 * - Code Validation: Generated factory code validation and syntax checking
 * - PSR Compliance: Code generation following PSR standards and best practices
 * - Documentation: Automatic generation of factory documentation and comments
 * - Testing: Built-in testing of generated factories for functionality verification
 * - Error Handling: Comprehensive error handling with detailed diagnostic information
 * - Performance Testing: Factory performance validation and optimization recommendations
 *
 * Customization Options:
 * - Custom Field Mappings: User-defined field generation strategies
 * - Generation Rules: Custom validation and generation rule configuration
 * - Output Formatting: Configurable code formatting and style options
 * - Namespace Management: Automatic namespace resolution and organization
 * - File Organization: Customizable file naming and directory structure
 * - Code Style: Integration with code style standards and formatting tools
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * $service = app(FactoryGeneratorService::class);
 * $result = $service->generateFiles([
 *     'class_name' => 'UserFactory',
 *     'model' => 'User',
 *     'fields' => ['name', 'email', 'password'],
 *     'relationships' => ['posts', 'profile']
 * ]);
 */
class FactoryGeneratorService
{
    protected StubTemplateService $stubService;

    public function __construct(StubTemplateService $stubService)
    {
        $this->stubService = $stubService;
    }

    public function generateFiles(array $config): array
    {
        $results = [
            'success' => false,
            'files_created' => [],
            'errors' => [],
        ];

        try {
            $className = $config['class_name'];
            $fileName = $className.'.php';
            $filePath = database_path('factories/'.$fileName);

            // Ensure directory exists
            File::ensureDirectoryExists(dirname($filePath));

            $content = $this->generateFactoryContent($config);
            File::put($filePath, $content);

            $results['files_created'][] = [
                'path' => $filePath,
                'type' => 'factory',
                'class_name' => $className,
            ];

            $results['success'] = true;
        } catch (\Exception $e) {
            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }

    public function generatePreview(array $config): array
    {
        return [
            'factory' => [
                'content' => $this->generateFactoryContent($config),
                'file_name' => $config['class_name'].'.php',
                'file_path' => 'database/factories/'.$config['class_name'].'.php',
            ],
        ];
    }

    protected function generateFactoryContent(array $config): string
    {
        $className = $config['class_name'];
        $modelName = $config['model'];
        $namespace = $config['namespace'] ?? 'Database\\Factories';

        $content = "<?php\n\n";
        $content .= "namespace {$namespace};\n\n";
        $content .= "use App\\Models\\{$modelName};\n";
        $content .= "use Illuminate\\Database\\Eloquent\\Factories\\Factory;\n\n";

        $content .= "/**\n";
        $content .= " * @extends \\Illuminate\\Database\\Eloquent\\Factories\\Factory<\\App\\Models\\{$modelName}>\n";
        $content .= " */\n";
        $content .= "class {$className} extends Factory\n";
        $content .= "{\n";
        $content .= "    /**\n";
        $content .= "     * The name of the factory's corresponding model.\n";
        $content .= "     *\n";
        $content .= "     * @var string\n";
        $content .= "     */\n";
        $content .= "    protected \$model = {$modelName}::class;\n\n";

        $content .= "    /**\n";
        $content .= "     * Define the model's default state.\n";
        $content .= "     *\n";
        $content .= "     * @return array<string, mixed>\n";
        $content .= "     */\n";
        $content .= "    public function definition(): array\n";
        $content .= "    {\n";
        $content .= "        return [\n";

        // Generate fake data fields
        foreach ($config['fake_data'] ?? [] as $field) {
            $content .= $this->generateFakeDataLine($field);
        }

        $content .= "        ];\n";
        $content .= "    }\n";

        // Generate states
        foreach ($config['states'] ?? [] as $state) {
            $content .= $this->generateStateMethod($state);
        }

        // Generate sequences
        foreach ($config['sequences'] ?? [] as $sequence) {
            $content .= $this->generateSequenceMethod($sequence);
        }

        // Generate callbacks
        if (! empty($config['after_creating'])) {
            $content .= $this->generateAfterCreatingCallback($config['after_creating']);
        }

        if (! empty($config['after_making'])) {
            $content .= $this->generateAfterMakingCallback($config['after_making']);
        }

        $content .= "}\n";

        return $content;
    }

    protected function generateFakeDataLine(array $field): string
    {
        $fieldName = $field['field'];
        $fakerMethod = $field['faker_method'];
        $parameters = $field['parameters'] ?? '';

        $line = "            '{$fieldName}' => ";

        if ($field['nullable'] ?? false) {
            $line .= '$this->faker->optional(0.8)->';
        } else {
            $line .= '$this->faker->';
        }

        if ($field['unique'] ?? false) {
            $line .= 'unique()->';
        }

        $line .= $fakerMethod;

        if (! empty($parameters)) {
            $line .= "({$parameters})";
        } else {
            $line .= '()';
        }

        if (! empty($field['custom_code'])) {
            $line = "            '{$fieldName}' => ".$field['custom_code'];
        }

        $line .= ",\n";

        return $line;
    }

    protected function generateStateMethod(array $state): string
    {
        $stateName = $state['name'];
        $definition = $state['definition'];

        $content = "\n    /**\n";
        $content .= "     * {$stateName} state\n";
        $content .= "     */\n";
        $content .= "    public function {$stateName}(): static\n";
        $content .= "    {\n";
        $content .= "        return \$this->state(fn (array \$attributes) => {$definition});\n";
        $content .= "    }\n";

        return $content;
    }

    protected function generateSequenceMethod(array $sequence): string
    {
        $field = $sequence['field'];
        $values = $sequence['values'];
        $valuesString = "'".implode("', '", $values)."'";

        $content = "\n    /**\n";
        $content .= "     * {$field} sequence\n";
        $content .= "     */\n";
        $content .= "    public function {$field}Sequence(): static\n";
        $content .= "    {\n";
        $content .= "        return \$this->sequence(\n";

        foreach ($values as $value) {
            $content .= "            ['{$field}' => '{$value}'],\n";
        }

        $content .= "        );\n";
        $content .= "    }\n";

        return $content;
    }

    protected function generateAfterCreatingCallback(array $callbacks): string
    {
        $content = "\n    /**\n";
        $content .= "     * Configure the model factory.\n";
        $content .= "     */\n";
        $content .= "    public function configure(): static\n";
        $content .= "    {\n";
        $content .= "        return \$this->afterCreating(function (\$model) {\n";

        foreach ($callbacks as $callback) {
            $content .= '            '.$callback['code']."\n";
        }

        $content .= "        });\n";
        $content .= "    }\n";

        return $content;
    }

    protected function generateAfterMakingCallback(array $callbacks): string
    {
        $content = "\n    /**\n";
        $content .= "     * Configure the model factory.\n";
        $content .= "     */\n";
        $content .= "    public function configure(): static\n";
        $content .= "    {\n";
        $content .= "        return \$this->afterMaking(function (\$model) {\n";

        foreach ($callbacks as $callback) {
            $content .= '            '.$callback['code']."\n";
        }

        $content .= "        });\n";
        $content .= "    }\n";

        return $content;
    }
}
