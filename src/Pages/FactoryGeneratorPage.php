<?php

namespace HkDevs\CodeForgeStudio\Pages;
use Filament\Schemas\Schema;
use HkDevs\CodeForgeStudio\Support\Grid;
use HkDevs\CodeForgeStudio\Support\Section;

use Filament\Forms;
use Filament\Forms\Form;
use HkDevs\CodeForgeStudio\Services\FactoryGeneratorService;

/**
 * FactoryGeneratorPage
 * 
 * Specialized generator page for creating Laravel model factories with
 * intelligent field type detection and realistic data generation.
 * 
 * Key Features:
 * - Automated factory generation based on model analysis
 * - Intelligent field type detection and appropriate faker methods
 * - Relationship-aware factory generation with foreign key handling
 * - Custom provider integration for specialized data types
 * - State and trait generation for factory variations
 * - Realistic data patterns with localization support
 * 
 * Factory Generation:
 * - Model introspection for automatic field mapping
 * - Faker method selection based on field names and types
 * - Relationship factory references and associations
 * - Custom data providers for domain-specific fields
 * - Sequence generation for ordered data
 * 
 * Advanced Features:
 * - Factory states for different model variations
 * - Trait generation for reusable factory components
 * - Custom method generation for specialized scenarios
 * - Seed data integration with realistic patterns
 * - Localization support for international data
 * 
 * Configuration Options:
 * - Model selection and analysis
 * - Field mapping customization
 * - Faker method override capabilities
 * - Relationship handling strategies
 * - Output formatting and organization
 * 
 * Integration:
 * - Extends BaseGeneratorPage for common functionality
 * - FactoryGeneratorService integration for generation logic
 * - Model analysis service for field introspection
 * - Template service for factory code generation
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class FactoryGeneratorPage extends BaseGeneratorPage
{
    protected string $view = 'codeforge-studio::pages.factory-generator';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $title = 'Factory Generator';
    protected static ?string $navigationLabel = 'Factory Generator';
    protected static ?int $navigationSort = 3;

    protected function initializeConfiguration(): void
    {
        $this->generationConfig = [
            'enabled' => true,
            'class_name' => '',
            'model' => '',
            'namespace' => 'Database\\Factories',
            'fake_data' => [],
            'states' => [],
            'after_creating' => [],
            'after_making' => [],
            'sequences' => [],
            'traits' => [],
            'count' => 1,
            'use_relationships' => true,
            'locale' => 'en_US',
            'custom_providers' => [],
        ];
    }

    protected function getGeneratorService()
    {
        return app(FactoryGeneratorService::class);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('class_name')
                            ->label('Factory Class Name')
                            ->placeholder('e.g., UserFactory, ProductFactory')
                            ->required()
                            ->live(debounce: 300)
                            ->afterStateUpdated(function ($state) {
                                if ($state) {
                                    $this->autoSuggestNames($state);
                                }
                            })
                            ->rule(function ($get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if ($value && preg_match('/^[A-Z][a-zA-Z0-9]*Factory$/', $value)) {
                                        $namespace = $get('namespace') ?: 'Database\\Factories';
                                        $factoryPath = $this->getFactoryFilePath($value, $namespace);
                                        $overwriteError = $this->wouldOverwriteFile($factoryPath, 'factory');
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
                            ->default('Database\\Factories')
                            ->required(),

                        Forms\Components\Select::make('locale')
                            ->label('Faker Locale')
                            ->options([
                                'en_US' => 'English (US)',
                                'en_GB' => 'English (UK)',
                                'fr_FR' => 'French (France)',
                                'de_DE' => 'German (Germany)',
                                'es_ES' => 'Spanish (Spain)',
                                'it_IT' => 'Italian (Italy)',
                                'pt_BR' => 'Portuguese (Brazil)',
                                'ja_JP' => 'Japanese (Japan)',
                                'ko_KR' => 'Korean (Korea)',
                                'zh_CN' => 'Chinese (China)',
                            ])
                            ->default('en_US')
                            ->searchable(),
                    ]),

                Grid::make(2)
                    ->schema([
                        Forms\Components\Toggle::make('use_relationships')
                            ->label('Generate Related Data')
                            ->default(true),

                        Forms\Components\TextInput::make('count')
                            ->label('Default Count')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                    ]),

                Section::make('Fake Data Configuration')
                    ->schema([
                        Forms\Components\Repeater::make('fake_data')
                            ->label('Field Definitions')
                            ->schema($this->getFakeDataSchema())
                            ->columnSpanFull()
                            ->addActionLabel('Add Field')
                            ->defaultItems(0)
                            ->collapsible(),
                    ]),

                Section::make('States & Sequences')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Repeater::make('states')
                            ->label('Factory States')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('State Name')
                                    ->required(),
                                Forms\Components\Textarea::make('definition')
                                    ->label('State Definition')
                                    ->placeholder("return ['field' => 'value'];")
                                    ->required(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add State')
                            ->defaultItems(0),

                        Forms\Components\Repeater::make('sequences')
                            ->label('Sequences')
                            ->schema([
                                Forms\Components\TextInput::make('field')
                                    ->label('Field Name')
                                    ->required(),
                                Forms\Components\TagsInput::make('values')
                                    ->label('Sequence Values')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add Sequence')
                            ->defaultItems(0),
                    ]),

                Section::make('Callbacks')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Repeater::make('after_creating')
                            ->label('After Creating Callbacks')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Callback Name'),
                                Forms\Components\Textarea::make('code')
                                    ->label('Callback Code')
                                    ->placeholder('function ($model) { ... }')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add Callback')
                            ->defaultItems(0),

                        Forms\Components\Repeater::make('after_making')
                            ->label('After Making Callbacks')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Callback Name'),
                                Forms\Components\Textarea::make('code')
                                    ->label('Callback Code')
                                    ->placeholder('function ($model) { ... }')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add Callback')
                            ->defaultItems(0),
                    ]),
            ])
            ->statePath('generationConfig');
    }

    protected function getFakeDataSchema(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('field')
                        ->label('Field Name')
                        ->required(),

                    Forms\Components\Select::make('faker_method')
                        ->label('Faker Method')
                        ->options($this->getFakerMethods())
                        ->required()
                        ->searchable(),

                    Forms\Components\TextInput::make('parameters')
                        ->label('Parameters')
                        ->placeholder('e.g., 1, 100 or "prefix"'),
                ]),

            Grid::make(2)
                ->schema([
                    Forms\Components\Toggle::make('nullable')
                        ->label('Can be Null (20% chance)'),

                    Forms\Components\Toggle::make('unique')
                        ->label('Unique Values'),
                ]),

            Forms\Components\Textarea::make('custom_code')
                ->label('Custom Code')
                ->placeholder('Custom faker code if needed')
                ->columnSpanFull(),
        ];
    }

    protected function getFakerMethods(): array
    {
        return [
            // Personal Information
            'name' => 'Full Name',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email Address',
            'safeEmail' => 'Safe Email',
            'phoneNumber' => 'Phone Number',
            'userName' => 'Username',

            // Address
            'address' => 'Full Address',
            'streetAddress' => 'Street Address',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'postcode' => 'Postal Code',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',

            // Company
            'company' => 'Company Name',
            'jobTitle' => 'Job Title',
            'catchPhrase' => 'Catch Phrase',
            'bs' => 'Business Speak',

            // Text
            'text' => 'Random Text',
            'sentence' => 'Random Sentence',
            'paragraph' => 'Random Paragraph',
            'word' => 'Random Word',
            'words' => 'Random Words',
            'realText' => 'Real Text',

            // Numbers
            'randomNumber' => 'Random Number',
            'numberBetween' => 'Number Between',
            'randomFloat' => 'Random Float',
            'biasedNumberBetween' => 'Biased Number',

            // Dates & Times
            'date' => 'Random Date',
            'dateTime' => 'Random DateTime',
            'dateTimeBetween' => 'DateTime Between',
            'time' => 'Random Time',
            'unixTime' => 'Unix Timestamp',
            'iso8601' => 'ISO8601 Date',

            // Internet
            'url' => 'URL',
            'domainName' => 'Domain Name',
            'slug' => 'URL Slug',
            'ipv4' => 'IPv4 Address',
            'ipv6' => 'IPv6 Address',
            'macAddress' => 'MAC Address',
            'password' => 'Password',

            // Colors
            'hexColor' => 'Hex Color',
            'rgbColor' => 'RGB Color',
            'colorName' => 'Color Name',
            'safeColorName' => 'Safe Color Name',

            // Files
            'fileExtension' => 'File Extension',
            'mimeType' => 'MIME Type',
            'imageUrl' => 'Image URL',

            // Payment
            'creditCardNumber' => 'Credit Card Number',
            'creditCardType' => 'Credit Card Type',
            'iban' => 'IBAN',
            'swiftBicNumber' => 'SWIFT/BIC',

            // Miscellaneous
            'boolean' => 'Boolean',
            'uuid' => 'UUID',
            'md5' => 'MD5 Hash',
            'sha1' => 'SHA1 Hash',
            'isbn13' => 'ISBN-13',
            'ean13' => 'EAN-13',
        ];
    }

    protected function validateConfiguration(): array
    {
        $errors = [];

        if (empty($this->generationConfig['class_name'])) {
            $errors[] = 'Factory class name is required.';
        } elseif (!preg_match('/^[A-Z][a-zA-Z0-9]*Factory$/', $this->generationConfig['class_name'])) {
            $errors[] = 'Factory class name must end with "Factory" and be a valid PHP class name.';
        } else {
            // Check if factory file already exists
            $className = $this->generationConfig['class_name'];
            $namespace = $this->generationConfig['namespace'] ?? 'Database\\Factories';
            $factoryPath = $this->getFactoryFilePath($className, $namespace);

            $overwriteError = $this->wouldOverwriteFile($factoryPath, 'factory');
            if ($overwriteError) {
                $errors[] = $overwriteError;
            }
        }

        if (empty($this->generationConfig['model'])) {
            $errors[] = 'Target model is required.';
        }

        // Validate fake data configurations
        foreach ($this->generationConfig['fake_data'] ?? [] as $index => $data) {
            if (empty($data['field'])) {
                $errors[] = "Field #" . ($index + 1) . ": Field name is required.";
            }

            if (empty($data['faker_method'])) {
                $errors[] = "Field #" . ($index + 1) . ": Faker method is required.";
            }
        }

        return $errors;
    }

    /**
     * Get the file path for a factory based on class name and namespace
     */
    protected function getFactoryFilePath(string $className, string $namespace): string
    {
        $namespacePath = $this->namespaceToPath($namespace);
        return base_path($namespacePath . '/' . $className . '.php');
    }

    protected function autoSuggestNames(string $className, ?string $tableName = null): void
    {
        // Extract model name from factory class name
        if (str_ends_with($className, 'Factory')) {
            $modelName = str_replace('Factory', '', $className);
            if (empty($this->generationConfig['model'])) {
                $this->generationConfig['model'] = $modelName;
            }

            // Auto-suggest common fake data based on model name
            if (empty($this->generationConfig['fake_data'])) {
                $this->generationConfig['fake_data'] = $this->getCommonFakeDataForModel($modelName);
            }
        }
    }

    protected function getCommonFakeDataForModel(string $modelName): array
    {
        $commonFields = [];

        if (str_contains(strtolower($modelName), 'user')) {
            $commonFields = [
                ['field' => 'name', 'faker_method' => 'name'],
                ['field' => 'email', 'faker_method' => 'safeEmail', 'unique' => true],
                ['field' => 'email_verified_at', 'faker_method' => 'dateTime', 'nullable' => true],
                ['field' => 'password', 'faker_method' => 'password'],
            ];
        } elseif (str_contains(strtolower($modelName), 'product')) {
            $commonFields = [
                ['field' => 'name', 'faker_method' => 'words', 'parameters' => '3, true'],
                ['field' => 'description', 'faker_method' => 'paragraph'],
                ['field' => 'price', 'faker_method' => 'randomFloat', 'parameters' => '2, 10, 1000'],
                ['field' => 'is_active', 'faker_method' => 'boolean'],
            ];
        } elseif (str_contains(strtolower($modelName), 'order')) {
            $commonFields = [
                ['field' => 'total_amount', 'faker_method' => 'randomFloat', 'parameters' => '2, 50, 500'],
                ['field' => 'status', 'faker_method' => 'randomElement', 'parameters' => "['pending', 'processing', 'shipped', 'delivered']"],
                ['field' => 'order_date', 'faker_method' => 'dateTimeBetween', 'parameters' => "'-1 year', 'now'"],
            ];
        } elseif (str_contains(strtolower($modelName), 'category')) {
            $commonFields = [
                ['field' => 'name', 'faker_method' => 'word'],
                ['field' => 'description', 'faker_method' => 'sentence'],
                ['field' => 'is_active', 'faker_method' => 'boolean'],
            ];
        }

        return $commonFields;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Code Generators';
    }
}
