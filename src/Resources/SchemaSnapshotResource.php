<?php

namespace HkDevs\CodeForgeStudio\Resources;
use Filament\Schemas\Schema;
use HkDevs\CodeForgeStudio\Support\Grid;
use HkDevs\CodeForgeStudio\Support\Section;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use HkDevs\CodeForgeStudio\Models\SchemaSnapshot;
use HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource\Pages;

/**
 * SchemaSnapshotResource
 * 
 * Filament resource for managing database schema snapshots with comprehensive
 * versioning, comparison, and change tracking capabilities.
 * 
 * Key Features:
 * - Complete schema snapshot lifecycle management
 * - Version tracking and baseline management
 * - Schema comparison and change detection
 * - Multi-connection schema capture and analysis
 * - Integration with documentation generation
 * - Advanced filtering and search capabilities
 * 
 * Resource Configuration:
 * - SchemaSnapshot model integration
 * - Camera icon for snapshot identification
 * - Positioned in 'Database Tools' navigation group
 * - Organized for efficient snapshot management and comparison
 * 
 * Snapshot Management:
 * - Schema capture and storage with comprehensive metadata
 * - Version control and baseline designation
 * - Connection-specific schema organization
 * - Hash-based change detection and comparison
 * - Relationship mapping and dependency tracking
 * 
 * Table Features:
 * - Snapshot listing with name, version, and capture date
 * - Connection and database organization
 * - Baseline indicator and version tracking
 * - Table count and relationship statistics
 * - Quick action buttons for comparison and documentation
 * 
 * Comparison Features:
 * - Side-by-side schema comparison visualization
 * - Change detection and difference highlighting
 * - Version progression tracking and analysis
 * - Migration script generation from differences
 * - Impact analysis and dependency mapping
 * 
 * Form Configuration:
 * - Snapshot metadata input (name, description, version)
 * - Connection selection and configuration
 * - Baseline designation and version control
 * - Capture scope and filtering options
 * - Integration with schema analysis services
 * 
 * Advanced Features:
 * - Automated snapshot scheduling and capture
 * - Schema evolution tracking and visualization
 * - Integration with migration generation tools
 * - Export capabilities for external analysis
 * - Documentation generation from snapshots
 * 
 * Analysis Capabilities:
 * - Schema health and integrity checking
 * - Performance impact analysis of changes
 * - Dependency mapping and relationship analysis
 * - Change risk assessment and recommendations
 * 
 * @package HkDevs\CodeForgeStudio\Resources
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class SchemaSnapshotResource extends Resource
{
    protected static ?string $model = SchemaSnapshot::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationLabel = 'Schema Snapshots';

    protected static string | \UnitEnum | null $navigationGroup = 'Database Tools';

    protected static ?int $navigationSort = 65;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                Section::make('Snapshot Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Pre-deployment snapshot'),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->placeholder('Optional description of this snapshot'),

                        Forms\Components\TextInput::make('version')
                            ->default('1.0.0')
                            ->required()
                            ->maxLength(20),

                        Forms\Components\Select::make('database_connection')
                            ->options(function () {
                                $connections = config('database.connections', []);
                                return collect($connections)
                                    ->keys()
                                    ->mapWithKeys(fn($key) => [$key => $key])
                                    ->toArray();
                            })
                            ->default(config('database.default'))
                            ->required()
                            ->disabled(fn($operation) => $operation === 'edit'),

                        Forms\Components\Toggle::make('is_baseline')
                            ->label('Mark as Baseline')
                            ->helperText('Baseline snapshots are used as reference points for comparisons'),
                    ])->columns(1),

                Section::make('Snapshot Data')
                    ->schema([
                        Forms\Components\Placeholder::make('schema_data_info')
                            ->label('Schema Data')
                            ->content(
                                fn($record) => $record ?
                                    'Contains schema information for ' . ($record->tables_count ?? 0) . ' tables' :
                                    'Schema data will be generated automatically when the snapshot is created'
                            ),

                        Forms\Components\Placeholder::make('relationships_info')
                            ->label('Table Relationships')
                            ->content(
                                fn($record) => $record ?
                                    'Contains ' . ($record->relationships_count ?? 0) . ' table relationships' :
                                    'Relationship data will be generated automatically when the snapshot is created'
                            ),

                        Forms\Components\Placeholder::make('models_info')
                            ->label('Model Mappings')
                            ->content(
                                fn($record) => $record ?
                                    'Contains mappings for ' . ($record->models_count ?? 0) . ' models' :
                                    'Model mappings will be generated automatically when the snapshot is created'
                            ),
                    ])
                    ->collapsed()
                    ->visible(fn($operation) => $operation === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('version')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('database_connection')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tables_count')
                    ->label('Tables')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('relationships_count')
                    ->label('Relationships')
                    ->numeric()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),

                Tables\Columns\TextColumn::make('models_count')
                    ->label('Models')
                    ->numeric()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_baseline')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('captured_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('captured_by')
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('database_connection')
                    ->options(function () {
                        $connections = config('database.connections', []);
                        return collect($connections)
                            ->keys()
                            ->mapWithKeys(fn($key) => [$key => $key])
                            ->toArray();
                    }),

                Tables\Filters\TernaryFilter::make('is_baseline')
                    ->label('Baseline Snapshots'),

                Tables\Filters\Filter::make('recent')
                    ->query(fn($query) => $query->where('captured_at', '>=', now()->subDays(30)))
                    ->label('Recent (30 days)'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Action::make('mark_baseline')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->visible(fn(SchemaSnapshot $record) => !$record->is_baseline)
                        ->action(function (SchemaSnapshot $record) {
                            $record->markAsBaseline();

                            Notification::make()
                                ->title('Baseline Updated')
                                ->body("'{$record->name}' is now the baseline snapshot.")
                                ->success()
                                ->send();
                        }),

                    Action::make('create_documentation')
                        ->icon('heroicon-o-document-text')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->default(fn(SchemaSnapshot $record) => "Documentation for {$record->name}"),
                            Forms\Components\Select::make('format')
                                ->required()
                                ->options([
                                    'markdown' => 'Markdown',
                                    'html' => 'HTML',
                                    'pdf' => 'PDF',
                                ])
                                ->default('markdown'),
                        ])
                        ->action(function (SchemaSnapshot $record, array $data) {
                            $generation = \HkDevs\CodeForgeStudio\Models\DocumentationGeneration::create([
                                'title' => $data['title'],
                                'format' => $data['format'],
                                'scope' => 'full_schema',
                                'schema_snapshot_id' => $record->id,
                            ]);

                            Notification::make()
                                ->title('Documentation Created')
                                ->body('Documentation generation has been queued.')
                                ->success()
                                ->send();
                        }),

                    Action::make('compare')
                        ->icon('heroicon-o-scale')
                        ->color('info')
                        ->url(fn(SchemaSnapshot $record) =>
                        static::getUrl('compare', ['record' => $record->id])),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('captured_at', 'desc');
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
            'index' => Pages\ListSchemaSnapshots::route('/'),
            'create' => Pages\CreateSchemaSnapshot::route('/create'),
            'view' => Pages\ViewSchemaSnapshot::route('/{record}'),
            'edit' => Pages\EditSchemaSnapshot::route('/{record}/edit'),
            'compare' => Pages\CompareSchemaSnapshots::route('/{record}/compare'),
        ];
    }
}
