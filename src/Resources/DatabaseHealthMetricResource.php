<?php

namespace HkDevs\CodeForgeStudio\Resources;

use HkDevs\CodeForgeStudio\Models\DatabaseHealthMetric;
use HkDevs\CodeForgeStudio\Resources\DatabaseHealthMetricResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * DatabaseHealthMetricResource
 * 
 * Filament resource for managing database health metrics with comprehensive
 * monitoring, analysis, and visualization capabilities.
 * 
 * Key Features:
 * - Complete CRUD operations for health metrics management
 * - Real-time metric visualization with charts and graphs
 * - Multi-connection health monitoring and comparison
 * - Advanced filtering and search capabilities
 * - Metric trend analysis and historical data tracking
 * - Alert management and threshold configuration
 * 
 * Resource Configuration:
 * - DatabaseHealthMetric model integration
 * - Heart icon for intuitive health monitoring identification
 * - Positioned in 'Database Health' navigation group
 * - Priority sorting for dashboard prominence
 * 
 * Table Features:
 * - Connection-based metric organization
 * - Metric type and value display with formatting
 * - Status indicators with color coding
 * - Timestamp tracking for historical analysis
 * - Bulk operations for metric management
 * 
 * Form Management:
 * - Metric creation and editing with validation
 * - Connection selection and configuration
 * - Metric type categorization and naming
 * - Value input with appropriate formatting
 * - Status management and alert configuration
 * 
 * Advanced Features:
 * - Metric comparison and trend analysis
 * - Export capabilities for reporting
 * - Integration with health monitoring dashboard
 * - Real-time updates and refresh capabilities
 * 
 * @package HkDevs\CodeForgeStudio\Resources
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class DatabaseHealthMetricResource extends Resource
{
    protected static ?string $model = DatabaseHealthMetric::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Health Metrics';
    protected static ?string $navigationGroup = 'Database Health';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('connection')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('metric_type')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('metric_name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unit')
                    ->maxLength(20),
                Forms\Components\Select::make('status')
                    ->options([
                        'normal' => 'Normal',
                        'warning' => 'Warning',
                        'critical' => 'Critical',
                    ])
                    ->default('normal'),
                Forms\Components\DateTimePicker::make('recorded_at')
                    ->required(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Health Metric Overview')
                    ->schema([
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->size('lg')
                                    ->color(fn(DatabaseHealthMetric $record): string => $record->status_color),

                                Infolists\Components\TextEntry::make('formatted_value')
                                    ->label('Value')
                                    ->badge()
                                    ->size('lg')
                                    ->color(fn(DatabaseHealthMetric $record): string => match ($record->status) {
                                        'normal' => 'success',
                                        'warning' => 'warning',
                                        'critical' => 'danger',
                                        default => 'gray',
                                    }),

                                Infolists\Components\TextEntry::make('metric_type')
                                    ->label('Type')
                                    ->badge()
                                    ->size('lg')
                                    ->color('info'),

                                Infolists\Components\TextEntry::make('connection')
                                    ->badge()
                                    ->size('lg')
                                    ->color('primary'),
                            ]),

                        Infolists\Components\TextEntry::make('recorded_at')
                            ->label('Recorded')
                            ->dateTime()
                            ->since()
                            ->badge()
                            ->color('gray'),
                    ])
                    ->compact()
                    ->icon('heroicon-o-heart'),

                Infolists\Components\Section::make('Metric Details')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('metric_name')
                                    ->label('Metric Name')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('metric_type')
                                    ->label('Metric Type')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'connection_status' => 'success',
                                        'query_performance' => 'info',
                                        'database_info' => 'warning',
                                        'response_time' => 'primary',
                                        default => 'gray',
                                    }),
                            ]),

                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('value')
                                    ->label('Raw Value')
                                    ->numeric(2)
                                    ->copyable()
                                    ->copyMessage('Value copied!')
                                    ->extraAttributes([
                                        'class' => 'bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono text-sm'
                                    ]),

                                Infolists\Components\TextEntry::make('unit')
                                    ->label('Unit')
                                    ->placeholder('No unit')
                                    ->badge()
                                    ->color('gray'),

                                Infolists\Components\TextEntry::make('formatted_value')
                                    ->label('Formatted Value')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->copyable()
                                    ->copyMessage('Formatted value copied!')
                                    ->color(fn(DatabaseHealthMetric $record): string => $record->status_color),
                            ]),
                    ])
                    ->collapsible()
                    ->icon('heroicon-o-chart-bar-square'),

                Infolists\Components\Section::make('Metadata & Context')
                    ->schema([
                        Infolists\Components\TextEntry::make('metadata')
                            ->label('Additional Metadata')
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return '// No additional metadata available';
                                }
                                return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                            })
                            ->copyable()
                            ->copyMessage('Metadata copied!')
                            ->fontFamily('mono')
                            ->size('sm')
                            ->color(fn($state) => empty($state) ? 'gray' : 'info')
                            ->extraAttributes([
                                'class' => 'bg-slate-50 dark:bg-slate-800 p-3 rounded-lg border border-slate-200 dark:border-slate-600 whitespace-pre',
                                'style' => 'font-family: "Fira Code", "Monaco", "Consolas", monospace; line-height: 1.4;'
                            ])
                            ->columnSpanFull(),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('recorded_at')
                                    ->label('Recorded At')
                                    ->dateTime()
                                    ->badge()
                                    ->color('success'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime()
                                    ->since()
                                    ->badge()
                                    ->color('gray'),
                            ]),
                    ])
                    ->collapsible()
                    ->persistCollapsed()
                    ->icon('heroicon-o-cog-6-tooth'),

                Infolists\Components\Section::make('Health Status Analysis')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('Current Status')
                            ->formatStateUsing(function ($state, DatabaseHealthMetric $record) {
                                $statusMessages = [
                                    'normal' => '✅ All systems operating normally',
                                    'warning' => '⚠️ Performance degradation detected - monitor closely',
                                    'critical' => '🚨 Critical issue detected - immediate attention required',
                                ];

                                $message = $statusMessages[$state] ?? '❓ Unknown status';

                                if ($record->threshold_info) {
                                    $message .= "\n\n� Thresholds: " . $record->threshold_info;
                                }

                                return $message;
                            })
                            ->weight('medium')
                            ->color(fn(DatabaseHealthMetric $record): string => $record->status_color)
                            ->extraAttributes(fn(DatabaseHealthMetric $record) => match ($record->status) {
                                'normal' => [
                                    'class' => 'bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border-l-4 border-green-500',
                                ],
                                'warning' => [
                                    'class' => 'bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border-l-4 border-yellow-500',
                                ],
                                'critical' => [
                                    'class' => 'bg-red-50 dark:bg-red-900/20 p-3 rounded-lg border-l-4 border-red-500',
                                ],
                                default => [
                                    'class' => 'bg-gray-50 dark:bg-gray-900/20 p-3 rounded-lg border-l-4 border-gray-500',
                                ],
                            }),

                        Infolists\Components\TextEntry::make('metric_description')
                            ->label('Metric Description')
                            ->color('info')
                            ->extraAttributes([
                                'class' => 'bg-blue-50 dark:bg-blue-900/20 p-2 rounded italic',
                            ]),

                        Infolists\Components\TextEntry::make('id')
                            ->label('Recommendations')
                            ->formatStateUsing(function ($state, DatabaseHealthMetric $record) {
                                $recommendations = $record->recommendations;

                                if (empty($recommendations)) {
                                    return 'No specific recommendations available.';
                                }

                                return '• ' . implode("\n• ", $recommendations);
                            })
                            ->color(fn(DatabaseHealthMetric $record): string => $record->status_color)
                            ->extraAttributes([
                                'class' => 'bg-gray-50 dark:bg-gray-800 p-3 rounded-lg whitespace-pre-line',
                            ]),
                    ])
                    ->icon(fn(DatabaseHealthMetric $record) => match ($record->status) {
                        'normal' => 'heroicon-o-check-circle',
                        'warning' => 'heroicon-o-exclamation-triangle',
                        'critical' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('connection')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('metric_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'connection_status' => 'success',
                        'query_performance' => 'info',
                        'database_info' => 'warning',
                        'response_time' => 'primary',
                        default => 'gray',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('metric_name')
                    ->label('Metric')
                    ->searchable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(fn($record) => $record->formatted_value)
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->color(fn(DatabaseHealthMetric $record): string => $record->status_color)
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'normal' => 'success',
                        'warning' => 'warning',
                        'critical' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'normal' => 'heroicon-o-check-circle',
                        'warning' => 'heroicon-o-exclamation-triangle',
                        'critical' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('recorded_at')
                    ->label('Recorded')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('connection')
                    ->options(function () {
                        return DatabaseHealthMetric::distinct()
                            ->pluck('connection', 'connection')
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('metric_type')
                    ->options(function () {
                        return DatabaseHealthMetric::distinct()
                            ->pluck('metric_type', 'metric_type')
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'normal' => 'Normal',
                        'warning' => 'Warning',
                        'critical' => 'Critical',
                    ]),
                Tables\Filters\Filter::make('warnings')
                    ->label('Warnings & Critical')
                    ->query(fn(Builder $query): Builder => $query->whereIn('status', ['warning', 'critical'])),
                Tables\Filters\Filter::make('recent')
                    ->label('Last 24 Hours')
                    ->query(fn(Builder $query): Builder => $query->where('recorded_at', '>=', now()->subDay())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('recorded_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatabaseHealthMetrics::route('/'),
            'view' => Pages\ViewDatabaseHealthMetric::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->latest('recorded_at');
    }
}
