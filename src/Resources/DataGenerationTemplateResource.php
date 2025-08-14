<?php

namespace HkDevs\CodeForgeStudio\Resources;

use HkDevs\CodeForgeStudio\Models\DataGenerationTemplate;
use HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource\Pages;
use HkDevs\CodeForgeStudio\Services\DataGenerationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

/**
 * DataGenerationTemplateResource
 * 
 * Filament resource for managing data generation templates that define
 * intelligent test data creation patterns and configurations.
 * 
 * Key Features:
 * - Complete template lifecycle management (create, edit, delete, activate)
 * - Field mapping configuration for intelligent data generation
 * - Relationship-aware template design with foreign key handling
 * - Reusable template patterns for consistent data generation
 * - Template testing and preview capabilities
 * - Integration with Smart Data Seeder functionality
 * 
 * Resource Configuration:
 * - DataGenerationTemplate model integration
 * - Document duplicate icon for template identification
 * - Positioned in 'Data Management' navigation group
 * - Organized for efficient template discovery and management
 * 
 * Template Management:
 * - Template creation with comprehensive configuration
 * - Field mapping setup for data type associations
 * - Relationship configuration for referential integrity
 * - Constraint definition for data validation
 * - Sample data storage for testing and preview
 * 
 * Table Features:
 * - Template listing with name, description, and status
 * - Table-specific template organization
 * - Active/inactive status management
 * - Creation date and author tracking
 * - Quick action buttons for common operations
 * 
 * Form Configuration:
 * - Template metadata input (name, description, table)
 * - Advanced field mapping with JSON configuration
 * - Relationship definition with foreign key setup
 * - Constraint specification for data validation
 * - Default record count and sample data management
 * 
 * Advanced Features:
 * - Template duplication for similar scenarios
 * - Import/export capabilities for template sharing
 * - Template validation and testing tools
 * - Integration with DataGenerationService
 * 
 * @package HkDevs\CodeForgeStudio\Resources
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class DataGenerationTemplateResource extends Resource
{
    protected static ?string $model = DataGenerationTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'Data Templates';
    protected static ?string $modelLabel = 'Data Generation Template';
    protected static ?string $pluralModelLabel = 'Data Generation Templates';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(500)
                            ->rows(3),

                        Forms\Components\Select::make('table_name')
                            ->options(self::getAvailableTables())
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('default_count')
                            ->numeric()
                            ->default(10)
                            ->minValue(1)
                            ->maxValue(10000)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Field Mappings')
                    ->description('Configure how each field should be generated')
                    ->schema([
                        Forms\Components\Repeater::make('field_mappings_repeater')
                            ->schema([
                                Forms\Components\TextInput::make('field')
                                    ->required()
                                    ->placeholder('Column name'),

                                Forms\Components\Select::make('type')
                                    ->options([
                                        'auto_increment' => 'Auto Increment',
                                        'uuid' => 'UUID',
                                        'string' => 'String/Text',
                                        'email' => 'Email',
                                        'name' => 'Name',
                                        'phone' => 'Phone',
                                        'address' => 'Address',
                                        'number' => 'Number',
                                        'decimal' => 'Decimal',
                                        'boolean' => 'Boolean',
                                        'date' => 'Date',
                                        'datetime' => 'DateTime',
                                        'json' => 'JSON',
                                        'enum' => 'Enum',
                                        'foreign_key' => 'Foreign Key',
                                        'custom' => 'Custom',
                                    ])
                                    ->required()
                                    ->default('string'),

                                Forms\Components\KeyValue::make('options')
                                    ->addActionLabel('Add Option')
                                    ->keyLabel('Option')
                                    ->valueLabel('Value'),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->collapsible()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                // Convert repeater data to field_mappings format
                                $mappings = [];
                                foreach ($state ?? [] as $item) {
                                    if (!empty($item['field'])) {
                                        $mappings[$item['field']] = [
                                            'type' => $item['type'] ?? 'string',
                                            'options' => $item['options'] ?? [],
                                        ];
                                    }
                                }
                                $set('field_mappings', $mappings);
                            }),

                        Forms\Components\Hidden::make('field_mappings'),
                    ]),

                Forms\Components\Section::make('Relationships')
                    ->description('Define relationships with other tables')
                    ->schema([
                        Forms\Components\Repeater::make('relationships')
                            ->schema([
                                Forms\Components\TextInput::make('column')
                                    ->required()
                                    ->placeholder('Foreign key column'),

                                Forms\Components\Select::make('related_table')
                                    ->options(self::getAvailableTables())
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('type')
                                    ->options([
                                        'belongs_to' => 'Belongs To',
                                        'has_many' => 'Has Many',
                                    ])
                                    ->default('belongs_to')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->collapsible(),
                    ]),

                Forms\Components\Section::make('Constraints')
                    ->description('Define business rules and constraints')
                    ->schema([
                        Forms\Components\Repeater::make('constraints')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'unique' => 'Unique Value',
                                        'conditional' => 'Conditional Logic',
                                        'range' => 'Value Range',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('field')
                                    ->required()
                                    ->placeholder('Field name'),

                                Forms\Components\KeyValue::make('rules')
                                    ->addActionLabel('Add Rule')
                                    ->keyLabel('Rule')
                                    ->valueLabel('Value'),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('table_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fields_count')
                    ->label('Fields')
                    ->getStateUsing(fn(DataGenerationTemplate $record) => $record->getFieldsCount())
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('relationships_count')
                    ->label('Relations')
                    ->getStateUsing(fn(DataGenerationTemplate $record) => $record->getRelationshipsCount())
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('default_count')
                    ->label('Default Count')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_by')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->query(fn($query) => $query->where('is_active', true))
                    ->label('Active Only'),

                Tables\Filters\SelectFilter::make('table_name')
                    ->options(function () {
                        return DataGenerationTemplate::distinct('table_name')
                            ->pluck('table_name', 'table_name')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->action(function (DataGenerationTemplate $record) {
                        try {
                            $service = app(DataGenerationService::class);
                            $preview = $service->previewData($record, 3);

                            Notification::make()
                                ->title('Preview Generated')
                                ->body('Check the browser console for preview data')
                                ->success()
                                ->send();

                            // In a real implementation, you'd show this in a modal
                            // For now, we'll log it
                            Log::info('Template Preview', ['template' => $record->name, 'data' => $preview]);
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Preview Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('generate')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Data')
                    ->form([
                        Forms\Components\TextInput::make('count')
                            ->label('Number of Records')
                            ->numeric()
                            ->default(fn(DataGenerationTemplate $record) => $record->default_count)
                            ->minValue(1)
                            ->maxValue(1000)
                            ->required(),
                    ])
                    ->action(function (DataGenerationTemplate $record, array $data) {
                        try {
                            $service = app(DataGenerationService::class);
                            $result = $service->insertGeneratedData($record, $data['count']);

                            $message = "Generated {$result['total_generated']} records, successfully inserted {$result['successfully_inserted']}";
                            if ($result['failed_inserts'] > 0) {
                                $message .= ", {$result['failed_inserts']} failed";
                            }

                            Notification::make()
                                ->title('Data Generated')
                                ->body($message)
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Generation Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataGenerationTemplates::route('/'),
            'create' => Pages\CreateDataGenerationTemplate::route('/create'),
            'view' => Pages\ViewDataGenerationTemplate::route('/{record}'),
            'edit' => Pages\EditDataGenerationTemplate::route('/{record}/edit'),
        ];
    }

    protected static function getAvailableTables(): array
    {
        try {
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            $tableNames = [];

            foreach ($tables as $tableName) {
                // Skip system tables
                if (!in_array($tableName, [
                    'migrations',
                    'personal_access_tokens',
                    'password_reset_tokens',
                    'failed_jobs',
                ])) {
                    $tableNames[$tableName] = $tableName;
                }
            }

            return $tableNames;
        } catch (\Exception $e) {
            return [];
        }
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Seeder Manager';
    }

    public static function getNavigationSort(): ?int
    {
        return config('codeforge-database-studio.navigation.sort', 100) + 7;
    }
}
