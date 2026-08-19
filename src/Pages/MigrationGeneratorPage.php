<?php

namespace HkDevs\CodeForgeStudio\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use HkDevs\CodeForgeStudio\Services\MigrationGeneratorService;
use HkDevs\CodeForgeStudio\Support\Grid;
use HkDevs\CodeForgeStudio\Support\Section;
use Illuminate\Support\Facades\Schema as DbSchema;

/**
 * MigrationGeneratorPage
 *
 * Comprehensive migration generator providing table creation, modification,
 * and database schema management with intelligent field type detection.
 *
 * Key Features:
 * - Complete migration generation for table operations
 * - Intelligent field type detection and validation
 * - Index and constraint generation with optimization
 * - Foreign key relationship setup and management
 * - Migration rollback method generation
 * - Batch migration support for complex schema changes
 *
 * Migration Types:
 * - Create Table: New table creation with complete structure
 * - Modify Table: Column additions, modifications, and deletions
 * - Drop Table: Safe table removal with rollback support
 * - Index Management: Index creation, modification, and optimization
 * - Constraint Management: Foreign keys, unique constraints, checks
 *
 * Field Configuration:
 * - Laravel migration field types with proper syntax
 * - Nullable, default value, and constraint configuration
 * - Index creation for performance optimization
 * - Foreign key relationship setup with cascading options
 * - Custom field types and modifiers
 *
 * Advanced Features:
 * - Schema analysis for existing table modifications
 * - Relationship detection and foreign key generation
 * - Migration dependency management and ordering
 * - Rollback method generation for safe reversibility
 * - Preview functionality with generated code display
 *
 * Validation:
 * - Field name and type validation
 * - Constraint compatibility checking
 * - Migration syntax validation
 * - Dependency conflict detection
 *
 * Integration:
 * - Extends BaseGeneratorPage for workflow management
 * - MigrationGeneratorService for generation logic
 * - Schema analyzer integration for existing table analysis
 * - Template service for migration code generation
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class MigrationGeneratorPage extends BaseGeneratorPage
{
    protected string $view = 'codeforge-studio::pages.migration-generator';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $title = 'Migration Generator';

    protected static ?string $navigationLabel = 'Migration Generator';

    protected static ?int $navigationSort = 1;

    protected function initializeConfiguration(): void
    {
        $this->generationConfig = [
            'enabled' => true,
            'type' => 'create',
            'table_name' => '',
            'columns' => [],
            'indexes' => [],
            'foreign_keys' => [],
            'constraints' => [],
            'timestamps' => true,
            'soft_deletes' => false,
            'uuid_primary' => false,
            'morph_fields' => [],
            'json_fields' => [],
            'fulltext_indexes' => [],
            'custom_schema_methods' => [],
        ];
    }

    protected function getGeneratorService(): MigrationGeneratorService
    {
        return app(MigrationGeneratorService::class);
    }

    protected function syncFormData(): void
    {
        try {
            $formData = $this->form->getState();
            $this->generationConfig = array_merge($this->generationConfig, $formData);
        } catch (\Exception $e) {
            // If form has validation errors, we'll catch them in validation
        }
    }

    public function generatePreview(): void
    {
        $this->syncFormData();
        parent::generatePreview();
    }

    public function generateFiles(): void
    {
        $this->syncFormData();
        parent::generateFiles();
    }

    public function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'generationConfig' => $this->generationConfig,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('type')
                        ->label('Migration Type')
                        ->options([
                            'create' => 'Create Table',
                            'update' => 'Update Table',
                            'drop' => 'Drop Table',
                            'rename' => 'Rename Table',
                        ])
                        ->default('create')
                        ->required(),

                    Forms\Components\TextInput::make('table_name')
                        ->label('Table Name')
                        ->placeholder('e.g., users, products, orders')
                        ->required()
                        ->live(debounce: 300)
                        ->afterStateUpdated(function ($state) {
                            if ($state && $this->generationConfig['type'] === 'create') {
                                $this->autoSuggestFromTableName($state);
                            }
                        })
                        ->rule(function ($get) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                if ($value && $get('type') === 'create' && preg_match('/^[a-z_][a-z0-9_]*$/', $value)) {
                                    // Check for existing migration
                                    $existingMigration = $this->findExistingCreateTableMigration($value);
                                    if ($existingMigration) {
                                        $fail("A migration for creating table '{$value}' already exists: {$existingMigration}");

                                        return;
                                    }

                                    // Check if table exists in database
                                    try {
                                        if (DbSchema::hasTable($value)) {
                                            $fail("Table '{$value}' already exists in the database");

                                            return;
                                        }
                                    } catch (\Exception $e) {
                                        // Database check failed, skip
                                    }

                                    // Check for reserved names
                                    $reservedError = $this->isReservedName($value, 'table');
                                    if ($reservedError) {
                                        $fail($reservedError);
                                    }
                                }
                            };
                        }),
                ]),

            Grid::make(3)
                ->schema([
                    Forms\Components\Toggle::make('timestamps')
                        ->label('Include Timestamps')
                        ->default(true),

                    Forms\Components\Toggle::make('soft_deletes')
                        ->label('Include Soft Deletes'),

                    Forms\Components\Toggle::make('uuid_primary')
                        ->label('Use UUID Primary Key'),
                ]),

            Section::make('Columns')
                ->schema([
                    Forms\Components\Repeater::make('columns')
                        ->label('Table Columns')
                        ->schema($this->getColumnSchema())
                        ->columnSpanFull()
                        ->addActionLabel('Add Column')
                        ->defaultItems(0)
                        ->collapsible(),
                ]),

            Section::make('Indexes & Constraints')
                ->columns(2)
                ->schema([
                    Forms\Components\Repeater::make('indexes')
                        ->label('Indexes')
                        ->schema($this->getIndexSchema())
                        ->addActionLabel('Add Index')
                        ->defaultItems(0)
                        ->collapsible(),

                    Forms\Components\Repeater::make('foreign_keys')
                        ->label('Foreign Keys')
                        ->schema($this->getForeignKeySchema())
                        ->addActionLabel('Add Foreign Key')
                        ->defaultItems(0)
                        ->collapsible(),
                ]),
        ])
            ->statePath('generationConfig');
    }

    protected function getColumnSchema(): array
    {
        return [
            Grid::make(4)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Column Name')
                        ->required()
                        ->live(debounce: 300)
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) {
                                $suggestions = $this->suggestColumnConfiguration($state);
                                foreach ($suggestions as $key => $value) {
                                    $set($key, $value);
                                }
                            }
                        }),

                    Forms\Components\Select::make('type')
                        ->label('Data Type')
                        ->options([
                            'id' => 'ID (Auto Increment)',
                            'bigInteger' => 'Big Integer',
                            'integer' => 'Integer',
                            'smallInteger' => 'Small Integer',
                            'tinyInteger' => 'Tiny Integer',
                            'string' => 'String/Varchar',
                            'text' => 'Text',
                            'longText' => 'Long Text',
                            'boolean' => 'Boolean',
                            'decimal' => 'Decimal',
                            'float' => 'Float',
                            'double' => 'Double',
                            'date' => 'Date',
                            'dateTime' => 'Date Time',
                            'timestamp' => 'Timestamp',
                            'time' => 'Time',
                            'year' => 'Year',
                            'json' => 'JSON',
                            'jsonb' => 'JSONB',
                            'uuid' => 'UUID',
                            'foreignId' => 'Foreign ID',
                            'enum' => 'Enum',
                            'set' => 'Set',
                            'binary' => 'Binary',
                        ])
                        ->required()
                        ->searchable(),

                    Forms\Components\TextInput::make('length')
                        ->label('Length/Precision')
                        ->placeholder('255 or 8,2'),

                    Forms\Components\TextInput::make('default')
                        ->label('Default Value'),
                ]),

            Grid::make(6)
                ->schema([
                    Forms\Components\Toggle::make('nullable')
                        ->label('Nullable'),

                    Forms\Components\Toggle::make('unique')
                        ->label('Unique'),

                    Forms\Components\Toggle::make('index')
                        ->label('Index'),

                    Forms\Components\Toggle::make('unsigned')
                        ->label('Unsigned'),

                    Forms\Components\Toggle::make('auto_increment')
                        ->label('Auto Increment'),

                    Forms\Components\Toggle::make('primary')
                        ->label('Primary Key'),
                ]),

            Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('comment')
                        ->label('Comment'),

                    Forms\Components\Select::make('after')
                        ->label('After Column')
                        ->placeholder('Select column to place after'),
                ]),
        ];
    }

    protected function getIndexSchema(): array
    {
        return [
            Forms\Components\Select::make('type')
                ->label('Index Type')
                ->options([
                    'index' => 'Regular Index',
                    'unique' => 'Unique Index',
                    'primary' => 'Primary Key',
                    'fulltext' => 'Full Text Index',
                    'spatial' => 'Spatial Index',
                ])
                ->required(),

            Forms\Components\TagsInput::make('columns')
                ->label('Columns')
                ->required(),

            Forms\Components\TextInput::make('name')
                ->label('Index Name (Optional)'),
        ];
    }

    protected function getForeignKeySchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('column')
                        ->label('Local Column')
                        ->required(),

                    Forms\Components\TextInput::make('referenced_column')
                        ->label('References Column')
                        ->default('id')
                        ->required(),
                ]),

            Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('referenced_table')
                        ->label('Referenced Table')
                        ->required(),

                    Forms\Components\TextInput::make('name')
                        ->label('Constraint Name (Optional)'),
                ]),

            Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('on_delete')
                        ->label('On Delete')
                        ->options([
                            'CASCADE' => 'CASCADE',
                            'SET NULL' => 'SET NULL',
                            'RESTRICT' => 'RESTRICT',
                            'NO ACTION' => 'NO ACTION',
                        ]),

                    Forms\Components\Select::make('on_update')
                        ->label('On Update')
                        ->options([
                            'CASCADE' => 'CASCADE',
                            'SET NULL' => 'SET NULL',
                            'RESTRICT' => 'RESTRICT',
                            'NO ACTION' => 'NO ACTION',
                        ]),
                ]),
        ];
    }

    protected function validateConfiguration(): array
    {
        $errors = [];

        // Ensure configuration is an array
        if (! is_array($this->generationConfig)) {
            $this->generationConfig = [];
        }

        if (empty($this->generationConfig['table_name'] ?? '')) {
            $errors[] = 'Table name is required.';
        } elseif (! preg_match('/^[a-z_][a-z0-9_]*$/', $this->generationConfig['table_name'])) {
            $errors[] = 'Table name must contain only lowercase letters, numbers and underscores.';
        } else {
            // Check for duplicate migration files only for 'create' type
            $tableName = $this->generationConfig['table_name'];
            $type = $this->generationConfig['type'] ?? 'create';

            if ($type === 'create') {
                $existingMigration = $this->findExistingCreateTableMigration($tableName);
                if ($existingMigration) {
                    $errors[] = "A migration for creating table '{$tableName}' already exists: {$existingMigration}. You cannot create duplicate table creation migrations.";
                }

                // Check if table already exists in database
                try {
                    if (DbSchema::hasTable($tableName)) {
                        $errors[] = "Table '{$tableName}' already exists in the database. You cannot create a migration for an existing table.";
                    }
                } catch (\Exception $e) {
                    // If we can't check the database, we'll just skip this validation
                    // This could happen if database connection is not available
                }

                // Check for reserved table names
                $reservedError = $this->isReservedName($tableName, 'table');
                if ($reservedError) {
                    $errors[] = $reservedError;
                }
            }
        }

        $type = $this->generationConfig['type'] ?? 'create';
        if ($type === 'create' && empty($this->generationConfig['columns'] ?? [])) {
            $errors[] = 'At least one column is required for table creation.';
        }

        // Validate column configurations
        $columns = $this->generationConfig['columns'] ?? [];
        if (is_array($columns)) {
            foreach ($columns as $index => $column) {
                if (! is_array($column)) {
                    continue;
                }

                if (empty($column['name'] ?? '')) {
                    $errors[] = 'Column #'.($index + 1).': Name is required.';
                }

                if (empty($column['type'] ?? '')) {
                    $errors[] = 'Column #'.($index + 1).': Data type is required.';
                }

                // Validate column name format
                if (! empty($column['name']) && ! preg_match('/^[a-z_][a-z0-9_]*$/', $column['name'])) {
                    $errors[] = 'Column #'.($index + 1).": Column name '{$column['name']}' must contain only lowercase letters, numbers and underscores.";
                }
            }
        }

        // Validate foreign key configurations
        $foreignKeys = $this->generationConfig['foreign_keys'] ?? [];
        if (is_array($foreignKeys)) {
            foreach ($foreignKeys as $index => $foreignKey) {
                if (! is_array($foreignKey)) {
                    continue;
                }

                $fkNumber = $index + 1;

                if (empty($foreignKey['column'] ?? '')) {
                    $errors[] = 'Foreign Key #'.$fkNumber.': Local column is required.';
                }

                if (empty($foreignKey['referenced_table'] ?? '')) {
                    $errors[] = 'Foreign Key #'.$fkNumber.': Referenced table is required.';
                }

                if (empty($foreignKey['referenced_column'] ?? '')) {
                    $errors[] = 'Foreign Key #'.$fkNumber.': Referenced column is required.';
                }

                // Validate column names format
                if (! empty($foreignKey['column']) && ! preg_match('/^[a-z_][a-z0-9_]*$/', $foreignKey['column'])) {
                    $errors[] = 'Foreign Key #'.$fkNumber.': Local column name must contain only lowercase letters, numbers and underscores.';
                }

                if (! empty($foreignKey['referenced_table']) && ! preg_match('/^[a-z_][a-z0-9_]*$/', $foreignKey['referenced_table'])) {
                    $errors[] = 'Foreign Key #'.$fkNumber.': Referenced table name must contain only lowercase letters, numbers and underscores.';
                }

                if (! empty($foreignKey['referenced_column']) && ! preg_match('/^[a-z_][a-z0-9_]*$/', $foreignKey['referenced_column'])) {
                    $errors[] = 'Foreign Key #'.$fkNumber.': Referenced column name must contain only lowercase letters, numbers and underscores.';
                }

                // Validate on delete/update actions
                $validActions = ['CASCADE', 'SET NULL', 'RESTRICT', 'NO ACTION'];
                if (! empty($foreignKey['on_delete'] ?? '') && ! in_array($foreignKey['on_delete'], $validActions)) {
                    $errors[] = 'Foreign Key #'.$fkNumber.": Invalid 'On Delete' action. Must be one of: ".implode(', ', $validActions);
                }

                if (! empty($foreignKey['on_update'] ?? '') && ! in_array($foreignKey['on_update'], $validActions)) {
                    $errors[] = 'Foreign Key #'.$fkNumber.": Invalid 'On Update' action. Must be one of: ".implode(', ', $validActions);
                }
            }
        }

        // Validate index configurations
        $indexes = $this->generationConfig['indexes'] ?? [];
        if (is_array($indexes)) {
            foreach ($indexes as $index => $indexData) {
                if (! is_array($indexData)) {
                    continue;
                }

                $indexNumber = $index + 1;

                $columns = $indexData['columns'] ?? [];
                if (empty($columns) || (is_array($columns) && count($columns) === 0)) {
                    $errors[] = 'Index #'.$indexNumber.': At least one column is required.';
                }

                $validIndexTypes = ['index', 'unique', 'primary', 'fulltext', 'spatial'];
                if (! empty($indexData['type'] ?? '') && ! in_array($indexData['type'], $validIndexTypes)) {
                    $errors[] = 'Index #'.$indexNumber.': Invalid index type. Must be one of: '.implode(', ', $validIndexTypes);
                }
            }
        }

        return $errors;
    }

    /**
     * Find existing create table migration for the given table name
     */
    protected function findExistingCreateTableMigration(string $tableName): ?string
    {
        $migrationPath = database_path('migrations');

        if (! is_dir($migrationPath)) {
            return null;
        }

        $files = glob($migrationPath.'/*_create_'.$tableName.'_table.php');

        if (! empty($files)) {
            return basename($files[0]);
        }

        return null;
    }

    protected function autoSuggestNames(string $modelName, ?string $tableName = null): void
    {
        // Auto-suggest common columns for the table
        if (empty($this->generationConfig['columns'])) {
            $commonColumns = $this->getCommonColumnsForTable($this->generationConfig['table_name']);
            $this->generationConfig['columns'] = $commonColumns;
        }
    }

    protected function suggestColumnConfiguration(string $columnName): array
    {
        $suggestions = [];

        // Suggest column type based on name patterns
        if (str_ends_with($columnName, '_id') || $columnName === 'id') {
            $suggestions['type'] = $columnName === 'id' ? 'id' : 'foreignId';
            $suggestions['unsigned'] = true;
        } elseif (str_contains($columnName, 'email')) {
            $suggestions['type'] = 'string';
            $suggestions['unique'] = true;
        } elseif (str_contains($columnName, 'password')) {
            $suggestions['type'] = 'string';
        } elseif (str_contains($columnName, 'phone')) {
            $suggestions['type'] = 'string';
            $suggestions['length'] = '20';
        } elseif (str_contains($columnName, 'url') || str_contains($columnName, 'link')) {
            $suggestions['type'] = 'string';
            $suggestions['length'] = '500';
        } elseif (str_contains($columnName, 'description') || str_contains($columnName, 'content')) {
            $suggestions['type'] = 'text';
            $suggestions['nullable'] = true;
        } elseif (str_contains($columnName, 'price') || str_contains($columnName, 'amount') || str_contains($columnName, 'cost')) {
            $suggestions['type'] = 'decimal';
            $suggestions['length'] = '8,2';
        } elseif (str_contains($columnName, 'count') || str_contains($columnName, 'quantity') || str_contains($columnName, 'number')) {
            $suggestions['type'] = 'integer';
            $suggestions['default'] = '0';
        } elseif (str_starts_with($columnName, 'is_') || str_starts_with($columnName, 'has_') || str_starts_with($columnName, 'can_')) {
            $suggestions['type'] = 'boolean';
            $suggestions['default'] = 'false';
        } elseif (str_contains($columnName, 'date') || str_ends_with($columnName, '_at')) {
            $suggestions['type'] = str_ends_with($columnName, '_at') ? 'timestamp' : 'date';
            $suggestions['nullable'] = true;
        } elseif (str_contains($columnName, 'time')) {
            $suggestions['type'] = 'time';
            $suggestions['nullable'] = true;
        } elseif (str_contains($columnName, 'json') || str_contains($columnName, 'meta') || str_contains($columnName, 'config')) {
            $suggestions['type'] = 'json';
            $suggestions['nullable'] = true;
        } elseif (str_contains($columnName, 'uuid')) {
            $suggestions['type'] = 'uuid';
        } else {
            // Default to string for unknown patterns
            $suggestions['type'] = 'string';
        }

        return $suggestions;
    }

    protected function autoSuggestFromTableName(string $tableName): void
    {
        // Auto-populate columns based on table name if no columns exist
        if (empty($this->generationConfig['columns'])) {
            $this->generationConfig['columns'] = $this->getCommonColumnsForTable($tableName);
        }
    }

    protected function getCommonColumnsForTable(string $tableName): array
    {
        $commonColumns = [
            [
                'name' => 'id',
                'type' => 'id',
                'nullable' => false,
                'unique' => false,
                'index' => false,
                'unsigned' => false,
                'auto_increment' => false,
                'primary' => false,
            ],
        ];

        // Add specific columns based on table name patterns
        if (str_contains($tableName, 'user')) {
            $commonColumns = array_merge($commonColumns, [
                ['name' => 'name', 'type' => 'string', 'nullable' => false],
                ['name' => 'email', 'type' => 'string', 'nullable' => false, 'unique' => true],
                ['name' => 'email_verified_at', 'type' => 'timestamp', 'nullable' => true],
                ['name' => 'password', 'type' => 'string', 'nullable' => false],
            ]);
        } elseif (str_contains($tableName, 'product')) {
            $commonColumns = array_merge($commonColumns, [
                ['name' => 'name', 'type' => 'string', 'nullable' => false],
                ['name' => 'description', 'type' => 'text', 'nullable' => true],
                ['name' => 'price', 'type' => 'decimal', 'length' => '8,2', 'nullable' => false],
                ['name' => 'is_active', 'type' => 'boolean', 'default' => 'true'],
            ]);
        } elseif (str_contains($tableName, 'order')) {
            $commonColumns = array_merge($commonColumns, [
                ['name' => 'user_id', 'type' => 'foreignId', 'nullable' => false],
                ['name' => 'total_amount', 'type' => 'decimal', 'length' => '10,2', 'nullable' => false],
                ['name' => 'status', 'type' => 'string', 'default' => 'pending'],
                ['name' => 'order_date', 'type' => 'timestamp', 'nullable' => false],
            ]);
        }

        return $commonColumns;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Code Generators';
    }
}
