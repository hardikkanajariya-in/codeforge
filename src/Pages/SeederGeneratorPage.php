<?php

namespace HkDevs\CodeForgeStudio\Pages;
use Filament\Schemas\Schema;
use HkDevs\CodeForgeStudio\Support\Grid;
use HkDevs\CodeForgeStudio\Support\Section;

use Filament\Forms;
use Filament\Forms\Form;
use HkDevs\CodeForgeStudio\Services\SeederGeneratorService;

/**
 * SeederGeneratorPage
 * 
 * Comprehensive database seeder generator with intelligent data generation,
 * relationship handling, and realistic test data creation capabilities.
 * 
 * Key Features:
 * - Complete Laravel seeder generation with realistic data
 * - Intelligent field-based data generation using Faker
 * - Relationship-aware seeding with foreign key handling
 * - Custom data provider integration for specialized fields
 * - Batch seeding support for large datasets
 * - Data consistency and referential integrity management
 * 
 * Data Generation:
 * - Automatic field type detection and appropriate Faker methods
 * - Realistic data patterns based on field names and types
 * - Localization support for international data generation
 * - Custom data providers for domain-specific requirements
 * - Unique constraint handling and duplicate prevention
 * 
 * Relationship Handling:
 * - Foreign key relationship detection and proper seeding order
 * - Parent-child relationship management with dependency resolution
 * - Many-to-many relationship seeding with pivot data
 * - Polymorphic relationship support and data generation
 * - Cross-table data consistency maintenance
 * 
 * Advanced Features:
 * - Configurable record counts with batch processing
 * - Custom seeder method generation for specialized logic
 * - Data template support for repeatable patterns
 * - Factory integration for consistent data generation
 * - Performance optimization for large dataset seeding
 * 
 * Configuration Options:
 * - Target model and table selection
 * - Record count and batch size configuration
 * - Field mapping and data type customization
 * - Relationship configuration and dependency management
 * - Custom data provider and pattern selection
 * 
 * Quality Assurance:
 * - Data validation and constraint checking
 * - Referential integrity verification
 * - Performance monitoring and optimization
 * - Error handling and rollback capabilities
 * 
 * Integration:
 * - Extends BaseGeneratorPage for workflow management
 * - SeederGeneratorService for generation logic
 * - Model analysis for field and relationship detection
 * - Template service for seeder code generation
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class SeederGeneratorPage extends BaseGeneratorPage
{
    protected string $view = 'codeforge-studio::pages.seeder-generator';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $title = 'Seeder Generator';
    protected static ?string $navigationLabel = 'Seeder Generator';
    protected static ?int $navigationSort = 4;

    protected function initializeConfiguration(): void
    {
        $this->generationConfig = [
            'enabled' => true,
            'class_name' => '',
            'model' => '',
            'namespace' => 'Database\\Seeders',
            'count' => 10,
            'use_factory' => true,
            'factory_states' => [],
            'manual_data' => [],
            'truncate_table' => false,
            'disable_foreign_keys' => false,
            'chunk_size' => 1000,
            'call_other_seeders' => [],
            'run_in_transaction' => true,
            'environment_specific' => false,
            'allowed_environments' => ['local', 'testing'],
        ];
    }

    protected function getGeneratorService()
    {
        return app(SeederGeneratorService::class);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('class_name')
                            ->label('Seeder Class Name')
                            ->placeholder('e.g., UserSeeder, ProductSeeder')
                            ->required()
                            ->live(debounce: 300)
                            ->afterStateUpdated(function ($state) {
                                if ($state) {
                                    $this->autoSuggestNames($state);
                                }
                            })
                            ->rule(function ($get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if ($value && preg_match('/^[A-Z][a-zA-Z0-9]*Seeder$/', $value)) {
                                        $namespace = $get('namespace') ?: 'Database\\Seeders';
                                        $seederPath = $this->getSeederFilePath($value, $namespace);
                                        $overwriteError = $this->wouldOverwriteFile($seederPath, 'seeder');
                                        if ($overwriteError) {
                                            $fail($overwriteError);
                                        }
                                    }
                                };
                            }),

                        Forms\Components\TextInput::make('model')
                            ->label('Target Model')
                            ->placeholder('e.g., User, Product, Order')
                            ->required(),

                        Forms\Components\TextInput::make('namespace')
                            ->label('Namespace')
                            ->default('Database\\Seeders')
                            ->required(),

                        Forms\Components\TextInput::make('count')
                            ->label('Default Record Count')
                            ->numeric()
                            ->default(10)
                            ->minValue(1),
                    ]),

                Grid::make(3)
                    ->schema([
                        Forms\Components\Toggle::make('use_factory')
                            ->label('Use Factory')
                            ->default(true)
                            ->live(debounce: 300),

                        Forms\Components\Toggle::make('truncate_table')
                            ->label('Truncate Table Before Seeding'),

                        Forms\Components\Toggle::make('disable_foreign_keys')
                            ->label('Disable Foreign Key Checks'),

                        Forms\Components\Toggle::make('run_in_transaction')
                            ->label('Run in Database Transaction')
                            ->default(true),

                        Forms\Components\Toggle::make('environment_specific')
                            ->label('Environment Specific')
                            ->live(debounce: 300),

                        Forms\Components\TextInput::make('chunk_size')
                            ->label('Chunk Size')
                            ->numeric()
                            ->default(1000)
                            ->minValue(1)
                            ->helperText('For large datasets, process in chunks'),
                    ]),

                Forms\Components\TagsInput::make('allowed_environments')
                    ->label('Allowed Environments')
                    ->default(['local', 'testing'])
                    ->suggestions(['local', 'testing', 'staging', 'production'])
                    ->visible(fn(Forms\Get $get) => $get('environment_specific')),

                Section::make('Factory Configuration')
                    ->schema([
                        Forms\Components\TagsInput::make('factory_states')
                            ->label('Factory States to Use')
                            ->placeholder('Add factory states')
                            ->helperText('Leave empty to use default factory'),
                    ])
                    ->visible(fn(Forms\Get $get) => $get('use_factory')),

                Section::make('Manual Data')
                    ->schema([
                        Forms\Components\Repeater::make('manual_data')
                            ->label('Manual Record Data')
                            ->schema([
                                Forms\Components\Textarea::make('data')
                                    ->label('Record Data (PHP Array)')
                                    ->placeholder("[\n    'name' => 'John Doe',\n    'email' => 'john@example.com',\n]")
                                    ->required()
                                    ->rows(4),
                            ])
                            ->columnSpanFull()
                            ->addActionLabel('Add Manual Record')
                            ->defaultItems(0)
                            ->collapsible(),
                    ])
                    ->visible(fn(Forms\Get $get) => !$get('use_factory')),

                Section::make('Advanced Options')
                    ->schema([
                        Forms\Components\TagsInput::make('call_other_seeders')
                            ->label('Call Other Seeders')
                            ->placeholder('Add seeder class names')
                            ->helperText('Other seeders to call from this seeder'),

                        Forms\Components\Textarea::make('custom_before_code')
                            ->label('Custom Code (Before Seeding)')
                            ->placeholder('// Custom code to run before seeding')
                            ->rows(3),

                        Forms\Components\Textarea::make('custom_after_code')
                            ->label('Custom Code (After Seeding)')
                            ->placeholder('// Custom code to run after seeding')
                            ->rows(3),
                    ]),
            ])
            ->statePath('generationConfig');
    }

    protected function validateConfiguration(): array
    {
        $errors = [];

        if (empty($this->generationConfig['class_name'])) {
            $errors[] = 'Seeder class name is required.';
        } elseif (!preg_match('/^[A-Z][a-zA-Z0-9]*Seeder$/', $this->generationConfig['class_name'])) {
            $errors[] = 'Seeder class name must end with "Seeder" and be a valid PHP class name.';
        } else {
            // Check if seeder file already exists
            $className = $this->generationConfig['class_name'];
            $namespace = $this->generationConfig['namespace'] ?? 'Database\\Seeders';
            $seederPath = $this->getSeederFilePath($className, $namespace);

            $overwriteError = $this->wouldOverwriteFile($seederPath, 'seeder');
            if ($overwriteError) {
                $errors[] = $overwriteError;
            }
        }

        if (empty($this->generationConfig['model'])) {
            $errors[] = 'Target model is required.';
        }

        if (!$this->generationConfig['use_factory'] && empty($this->generationConfig['manual_data'])) {
            $errors[] = 'Either use factory or provide manual data.';
        }

        if ($this->generationConfig['count'] < 1) {
            $errors[] = 'Record count must be at least 1.';
        }

        if ($this->generationConfig['chunk_size'] < 1) {
            $errors[] = 'Chunk size must be at least 1.';
        }

        // Validate manual data if not using factory
        if (!$this->generationConfig['use_factory']) {
            foreach ($this->generationConfig['manual_data'] ?? [] as $index => $record) {
                if (empty($record['data'])) {
                    $errors[] = "Manual record #" . ($index + 1) . ": Data is required.";
                } else {
                    // Try to validate PHP array syntax
                    $data = trim($record['data']);
                    if (!str_starts_with($data, '[') || !str_ends_with($data, ']')) {
                        $errors[] = "Manual record #" . ($index + 1) . ": Data must be a valid PHP array.";
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Get the file path for a seeder based on class name and namespace
     */
    protected function getSeederFilePath(string $className, string $namespace): string
    {
        $namespacePath = $this->namespaceToPath($namespace);
        return base_path($namespacePath . '/' . $className . '.php');
    }

    protected function autoSuggestNames(string $className, ?string $tableName = null): void
    {
        // Extract model name from seeder class name
        if (str_ends_with($className, 'Seeder')) {
            $modelName = str_replace('Seeder', '', $className);
            if (empty($this->generationConfig['model'])) {
                $this->generationConfig['model'] = $modelName;
            }

            // Auto-suggest count based on model type
            if (empty($this->generationConfig['count']) || $this->generationConfig['count'] === 10) {
                $this->generationConfig['count'] = $this->getSuggestedCountForModel($modelName);
            }

            // Auto-suggest factory states
            if (empty($this->generationConfig['factory_states'])) {
                $this->generationConfig['factory_states'] = $this->getSuggestedFactoryStates($modelName);
            }
        }
    }

    protected function getSuggestedCountForModel(string $modelName): int
    {
        $modelLower = strtolower($modelName);

        if (str_contains($modelLower, 'user')) {
            return 50; // Moderate number of users
        } elseif (str_contains($modelLower, 'admin')) {
            return 5; // Few admin users
        } elseif (str_contains($modelLower, 'product')) {
            return 100; // Many products
        } elseif (str_contains($modelLower, 'category')) {
            return 20; // Moderate categories
        } elseif (str_contains($modelLower, 'order')) {
            return 200; // Many orders
        } elseif (str_contains($modelLower, 'review')) {
            return 500; // Many reviews
        }

        return 10; // Default
    }

    protected function getSuggestedFactoryStates(string $modelName): array
    {
        $modelLower = strtolower($modelName);

        if (str_contains($modelLower, 'user')) {
            return ['verified', 'unverified'];
        } elseif (str_contains($modelLower, 'product')) {
            return ['active', 'inactive', 'featured'];
        } elseif (str_contains($modelLower, 'order')) {
            return ['pending', 'completed', 'cancelled'];
        }

        return [];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Code Generators';
    }
}
