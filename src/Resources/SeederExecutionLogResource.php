<?php

namespace HkDevs\CodeForgeStudio\Resources;

use HkDevs\CodeForgeStudio\Models\SeederExecutionLog;
use HkDevs\CodeForgeStudio\Resources\SeederExecutionLogResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * SeederExecutionLogResource
 * 
 * Filament resource for managing seeder execution logs with comprehensive
 * tracking, monitoring, and performance analysis capabilities.
 * 
 * Key Features:
 * - Complete seeder execution history and audit trail
 * - Performance tracking with detailed execution metrics
 * - Record-level statistics for created, updated, and failed operations
 * - Error analysis and troubleshooting capabilities
 * - Integration with DataSeeder management
 * - Advanced filtering and search functionality
 * 
 * Resource Configuration:
 * - SeederExecutionLog model integration
 * - Play icon for execution identification
 * - Positioned in 'Data Management' navigation group
 * - Organized for efficient execution monitoring and analysis
 * 
 * Execution Monitoring:
 * - Real-time execution status tracking and monitoring
 * - Performance metrics with timing and record counts
 * - Success rate analysis and failure investigation
 * - User attribution and execution context tracking
 * - Output capture and error message logging
 * 
 * Table Features:
 * - Execution log listing with seeder name, status, and timing
 * - Record statistics display (created/updated/failed)
 * - Status indicators with color coding
 * - Execution duration and performance metrics
 * - User and timestamp information tracking
 * 
 * Analysis Features:
 * - Execution performance trend analysis
 * - Success rate tracking and failure pattern identification
 * - Resource usage monitoring and optimization
 * - Seeder efficiency analysis and recommendations
 * - Error categorization and resolution guidance
 * 
 * Filtering & Search:
 * - Seeder-based filtering and organization
 * - Status-based filtering (running/completed/failed)
 * - Date range filtering for historical analysis
 * - User-based filtering and attribution
 * - Performance-based filtering and analysis
 * 
 * Reporting Features:
 * - Execution summary reports and analytics
 * - Performance benchmarking and comparison
 * - Error analysis and troubleshooting reports
 * - Success rate tracking and trend analysis
 * - Resource utilization and optimization insights
 * 
 * Integration:
 * - DataSeeder relationship for seeder context
 * - Execution service integration for monitoring
 * - Performance analytics and trending
 * - Error handling and notification systems
 * 
 * @package HkDevs\CodeForgeStudio\Resources
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class SeederExecutionLogResource extends Resource
{
    protected static ?string $model = SeederExecutionLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Execution Logs';
    protected static ?string $modelLabel = 'Execution Log';
    protected static ?string $pluralModelLabel = 'Execution Logs';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('seeder_name')
                            ->required()
                            ->disabled(),

                        Forms\Components\TextInput::make('seeder_class')
                            ->required()
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'started' => 'Started',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->disabled(),

                        Forms\Components\TextInput::make('executed_by')
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('records_created')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('records_updated')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('records_failed')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('execution_time')
                            ->numeric()
                            ->suffix('seconds')
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Output & Errors')
                    ->schema([
                        Forms\Components\Textarea::make('output')
                            ->rows(10)
                            ->disabled(),

                        Forms\Components\Textarea::make('error_message')
                            ->rows(5)
                            ->disabled(),
                    ]),

                Forms\Components\Section::make('Metadata')
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->disabled(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('seeder_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'completed',
                        'danger' => 'failed',
                        'warning' => 'started',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'completed',
                        'heroicon-o-x-circle' => 'failed',
                        'heroicon-o-clock' => 'started',
                    ]),

                Tables\Columns\TextColumn::make('records_created')
                    ->label('Created')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('records_updated')
                    ->label('Updated')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('records_failed')
                    ->label('Failed')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('execution_time')
                    ->label('Duration')
                    ->getStateUsing(function (SeederExecutionLog $record): string {
                        return $record->duration;
                    })
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('executed_by')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'started' => 'Started',
                    ]),

                Tables\Filters\SelectFilter::make('seeder_name')
                    ->options(function () {
                        return SeederExecutionLog::distinct('seeder_name')
                            ->pluck('seeder_name', 'seeder_name')
                            ->toArray();
                    }),

                Tables\Filters\Filter::make('recent')
                    ->query(fn(Builder $query): Builder => $query->where('started_at', '>=', now()->subDays(7)))
                    ->label('Recent (7 days)'),

                Tables\Filters\Filter::make('failed_only')
                    ->query(fn(Builder $query): Builder => $query->where('status', 'failed'))
                    ->label('Failed Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('view_output')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn(SeederExecutionLog $record): string => 'Execution Output - ' . $record->seeder_name)
                    ->modalContent(function (SeederExecutionLog $record) {
                        return view('codeforge-studio::components.execution-output', [
                            'output' => $record->output,
                            'error' => $record->error_message,
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('started_at', 'desc');
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
            'index' => Pages\ListSeederExecutionLogs::route('/'),
            'view' => Pages\ViewSeederExecutionLog::route('/{record}'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Seeder Manager';
    }

    public static function getNavigationSort(): ?int
    {
        return config('codeforge-database-studio.navigation.sort', 100) + 6;
    }
}
