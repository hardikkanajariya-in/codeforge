<?php

namespace HkDevs\CodeForgeStudio\Resources;
use Filament\Schemas\Schema;
use HkDevs\CodeForgeStudio\Support\Grid;
use HkDevs\CodeForgeStudio\Support\Section;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use HkDevs\CodeForgeStudio\Models\DocumentationGeneration;
use HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource\Pages;
use HkDevs\CodeForgeStudio\Services\DocumentationGenerationService;

/**
 * DocumentationGenerationResource
 * 
 * Filament resource for managing database documentation generation processes
 * with comprehensive tracking, versioning, and multi-format support.
 * 
 * Key Features:
 * - Complete documentation generation lifecycle management
 * - Multi-format documentation support (HTML, PDF, Markdown, JSON)
 * - Version tracking and documentation history management
 * - Schema snapshot integration for point-in-time documentation
 * - Advanced configuration and customization options
 * - Export and delivery management capabilities
 * 
 * Resource Configuration:
 * - DocumentationGeneration model integration
 * - Document text icon for documentation identification
 * - Positioned in 'Documentation' navigation group
 * - Organized for efficient documentation management
 * 
 * Documentation Management:
 * - Generation request creation and configuration
 * - Format selection and output customization
 * - Scope definition for selective documentation
 * - Table inclusion and exclusion management
 * - Version control and change tracking
 * 
 * Table Features:
 * - Documentation listing with title, format, and status
 * - Generation status tracking and monitoring
 * - File size and location management
 * - Creation date and author tracking
 * - Quick action buttons for download and regeneration
 * 
 * Form Configuration:
 * - Documentation metadata input (title, description, version)
 * - Format selection and output configuration
 * - Scope definition and table selection
 * - Advanced options and customization settings
 * - Schema snapshot association for versioning
 * 
 * Generation Features:
 * - Real-time generation progress tracking
 * - Error handling and failure analysis
 * - File management and storage organization
 * - Delivery and distribution capabilities
 * - Integration with DocumentationGenerationService
 * 
 * Advanced Features:
 * - Template customization and branding
 * - Scheduled documentation generation
 * - Automated delivery and distribution
 * - Integration with version control systems
 * 
 * @package HkDevs\CodeForgeStudio\Resources
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class DocumentationGenerationResource extends Resource
{
    protected static ?string $model = DocumentationGeneration::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Documentation Jobs';

    protected static string | \UnitEnum | null $navigationGroup = 'DB Docs Generation';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Database Schema Documentation'),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->placeholder('Optional description of what this documentation covers'),

                        Forms\Components\TextInput::make('version')
                            ->default('1.0.0')
                            ->required()
                            ->maxLength(20),
                    ])->columns(1),

                Section::make('Generation Settings')
                    ->schema([
                        Forms\Components\Select::make('format')
                            ->required()
                            ->options([
                                'markdown' => 'Markdown (.md)',
                                'html' => 'HTML (.html)',
                                'pdf' => 'PDF (.pdf)',
                            ])
                            ->default('markdown')
                            ->reactive()
                            ->helperText('Choose the output format for the documentation'),

                        Forms\Components\Select::make('scope')
                            ->required()
                            ->options([
                                'full_schema' => 'Full Database Schema',
                                'selected_tables' => 'Selected Tables',
                                'single_table' => 'Single Table',
                                'models_only' => 'Models Only',
                            ])
                            ->default('full_schema')
                            ->reactive()
                            ->helperText('Define what to include in the documentation'),

                        Forms\Components\Select::make('included_tables')
                            ->multiple()
                            ->searchable()
                            ->options(function () {
                                return static::getAvailableTables();
                            })
                            ->visible(fn(Forms\Get $get) => in_array($get('scope'), ['selected_tables', 'single_table']))
                            ->required(fn(Forms\Get $get) => in_array($get('scope'), ['selected_tables', 'single_table']))
                            ->helperText('Select which tables to include in the documentation'),
                    ])->columns(1),

                Section::make('Advanced Options')
                    ->schema([
                        Forms\Components\KeyValue::make('options')
                            ->addActionLabel('Add Option')
                            ->keyLabel('Option')
                            ->valueLabel('Value')
                            ->helperText('Additional generation options (JSON format)')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('format')
                    ->badge()
                    ->colors([
                        'info' => 'markdown',
                        'success' => 'html',
                        'warning' => 'pdf',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('scope')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'full_schema' => 'Full Schema',
                        'selected_tables' => 'Selected',
                        'single_table' => 'Single Table',
                        'models_only' => 'Models Only',
                        default => $state
                    })
                    ->colors([
                        'primary' => 'full_schema',
                        'success' => 'selected_tables',
                        'warning' => 'single_table',
                        'info' => 'models_only',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'pending',
                        'warning' => 'generating',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('formatted_file_size')
                    ->label('File Size')
                    ->sortable('file_size'),

                Tables\Columns\TextColumn::make('generated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('generated_by')
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'generating' => 'Generating',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),

                Tables\Filters\SelectFilter::make('format')
                    ->options([
                        'markdown' => 'Markdown',
                        'html' => 'HTML',
                        'pdf' => 'PDF',
                    ]),

                Tables\Filters\SelectFilter::make('scope')
                    ->options([
                        'full_schema' => 'Full Schema',
                        'selected_tables' => 'Selected Tables',
                        'single_table' => 'Single Table',
                        'models_only' => 'Models Only',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('generate')
                        ->icon('heroicon-o-play')
                        ->color('primary')
                        ->visible(fn(DocumentationGeneration $record) => $record->status === 'pending')
                        ->action(function (DocumentationGeneration $record) {
                            try {
                                $service = app(DocumentationGenerationService::class, ['generation' => $record]);
                                $service->generate();

                                Notification::make()
                                    ->title('Documentation Generated')
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

                    Action::make('download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn(DocumentationGeneration $record) => $record->status === 'completed')
                        ->url(fn(DocumentationGeneration $record) => route('admin.database-manager.documentation.download', $record))
                        ->openUrlInNewTab(),

                    Action::make('regenerate')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->visible(fn(DocumentationGeneration $record) => in_array($record->status, ['completed', 'failed']))
                        ->action(function (DocumentationGeneration $record) {
                            $record->update(['status' => 'pending']);

                            Notification::make()
                                ->title('Marked for Regeneration')
                                ->body('The documentation has been queued for regeneration.')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('generate_selected')
                        ->label('Generate Selected')
                        ->icon('heroicon-o-play')
                        ->color('primary')
                        ->action(function ($records) {
                            $generated = 0;
                            $failed = 0;

                            foreach ($records as $record) {
                                if ($record->status !== 'pending') {
                                    continue;
                                }

                                try {
                                    $service = app(DocumentationGenerationService::class, ['generation' => $record]);
                                    $service->generate();
                                    $generated++;
                                } catch (\Exception $e) {
                                    $failed++;
                                }
                            }

                            Notification::make()
                                ->title("Bulk Generation Complete")
                                ->body("Generated: {$generated}, Failed: {$failed}")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListDocumentationGenerations::route('/'),
            'create' => Pages\CreateDocumentationGeneration::route('/create'),
            'view' => Pages\ViewDocumentationGeneration::route('/{record}'),
            'edit' => Pages\EditDocumentationGeneration::route('/{record}/edit'),
        ];
    }

    protected static function getAvailableTables(): array
    {
        try {
            $connection = config('database.default');
            $tables = [];

            switch (config("database.connections.{$connection}.driver")) {
                case 'mysql':
                    $database = config("database.connections.{$connection}.database");
                    $results = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$database]);
                    $tables = collect($results)->pluck('table_name')->toArray();
                    break;

                case 'sqlite':
                    $results = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                    $tables = collect($results)->pluck('name')->toArray();
                    break;

                case 'pgsql':
                    $results = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
                    $tables = collect($results)->pluck('tablename')->toArray();
                    break;
            }

            return array_combine($tables, $tables);
        } catch (\Exception $e) {
            return [];
        }
    }
}
