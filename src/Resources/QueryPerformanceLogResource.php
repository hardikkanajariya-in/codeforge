<?php

namespace HkDevs\CodeForgeStudio\Resources;

use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use HkDevs\CodeForgeStudio\Models\QueryPerformanceLog;
use HkDevs\CodeForgeStudio\Resources\QueryPerformanceLogResource\Pages;
use HkDevs\CodeForgeStudio\Support\Grid;
use HkDevs\CodeForgeStudio\Support\Section;
use Illuminate\Database\Eloquent\Builder;

/**
 * QueryPerformanceLogResource
 *
 * Filament resource for managing database query performance logs with
 * comprehensive monitoring, analysis, and optimization capabilities.
 *
 * Key Features:
 * - Complete query performance monitoring and analysis
 * - Real-time performance tracking with microsecond precision
 * - Query pattern analysis and optimization recommendations
 * - Multi-connection support for complex database architectures
 * - Advanced filtering and search capabilities
 * - Performance trend analysis and alerting
 *
 * Resource Configuration:
 * - QueryPerformanceLog model integration
 * - Chart bar icon for performance monitoring identification
 * - Positioned in 'Performance Monitoring' navigation group
 * - Organized for efficient performance analysis and optimization
 *
 * Performance Monitoring:
 * - Query execution time tracking and analysis
 * - Connection-based performance organization
 * - Query type classification and monitoring
 * - Error tracking and failure analysis
 * - Resource usage monitoring and optimization
 *
 * Table Features:
 * - Performance log listing with query, time, and status
 * - Execution time formatting with appropriate units
 * - Connection and query type organization
 * - Status indicators for success/failure tracking
 * - Query hash grouping for duplicate analysis
 *
 * Analysis Features:
 * - Slow query identification and alerting
 * - Query pattern analysis and optimization suggestions
 * - Performance trend visualization and reporting
 * - Resource usage analysis and capacity planning
 * - Error pattern identification and resolution
 *
 * Filtering & Search:
 * - Connection-based filtering and organization
 * - Query type classification and filtering
 * - Execution time range filtering
 * - Status-based filtering (success/error)
 * - Full-text query search capabilities
 *
 * Optimization Tools:
 * - Query optimization recommendations
 * - Index analysis and suggestions
 * - Performance baseline comparison
 * - Resource utilization analysis
 * - Capacity planning and scaling insights
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class QueryPerformanceLogResource extends Resource
{
    protected static ?string $model = QueryPerformanceLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Query Performance';

    protected static string|\UnitEnum|null $navigationGroup = 'Database Health';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('connection')
                ->required()
                ->maxLength(100),
            Forms\Components\Textarea::make('query')
                ->required()
                ->rows(4),
            Forms\Components\TextInput::make('execution_time')
                ->required()
                ->numeric()
                ->suffix('ms'),
            Forms\Components\Select::make('type')
                ->options([
                    'select' => 'SELECT',
                    'insert' => 'INSERT',
                    'update' => 'UPDATE',
                    'delete' => 'DELETE',
                    'create' => 'CREATE',
                    'drop' => 'DROP',
                    'alter' => 'ALTER',
                    'other' => 'OTHER',
                ]),
            Forms\Components\Select::make('status')
                ->options([
                    'success' => 'Success',
                    'error' => 'Error',
                ])
                ->default('success'),
            Forms\Components\Textarea::make('error_message')
                ->rows(3),
            Forms\Components\DateTimePicker::make('executed_at')
                ->required(),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Performance Summary')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('type')
                                    ->badge()
                                    ->size('lg')
                                    ->color(fn (QueryPerformanceLog $record): string => match ($record->type) {
                                        'select' => 'success',
                                        'insert' => 'info',
                                        'update' => 'warning',
                                        'delete' => 'danger',
                                        default => 'gray',
                                    }),

                                Infolists\Components\TextEntry::make('execution_time')
                                    ->label('Execution Time')
                                    ->formatStateUsing(fn ($state) => number_format($state, 2).' ms')
                                    ->badge()
                                    ->size('lg')
                                    ->color(fn (QueryPerformanceLog $record): string => match ($record->performance_status) {
                                        'fast' => 'success',
                                        'moderate' => 'warning',
                                        'slow' => 'danger',
                                        default => 'gray',
                                    }),

                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->size('lg')
                                    ->color(fn (string $state): string => match ($state) {
                                        'success' => 'success',
                                        'error' => 'danger',
                                        default => 'gray',
                                    }),

                                Infolists\Components\TextEntry::make('connection')
                                    ->badge()
                                    ->size('lg')
                                    ->color('primary'),
                            ]),

                        Infolists\Components\TextEntry::make('executed_at')
                            ->label('Executed')
                            ->dateTime()
                            ->since()
                            ->badge()
                            ->color('gray'),
                    ])
                    ->compact()
                    ->icon('heroicon-o-chart-bar'),

                Section::make('Query Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('connection')
                                    ->badge()
                                    ->color('primary'),
                                Infolists\Components\TextEntry::make('type')
                                    ->badge()
                                    ->color(fn (QueryPerformanceLog $record): string => match ($record->type) {
                                        'select' => 'success',
                                        'insert' => 'info',
                                        'update' => 'warning',
                                        'delete' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('execution_time')
                                    ->label('Execution Time')
                                    ->formatStateUsing(fn ($state) => number_format($state, 2).' ms')
                                    ->badge()
                                    ->color(fn (QueryPerformanceLog $record): string => match ($record->performance_status) {
                                        'fast' => 'success',
                                        'moderate' => 'warning',
                                        'slow' => 'danger',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'success' => 'success',
                                        'error' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),
                        Infolists\Components\TextEntry::make('executed_at')
                            ->dateTime()
                            ->since(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Query Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('complete_query')
                            ->label('Complete SQL Query (with bindings)')
                            ->copyable()
                            ->copyMessage('Complete query copied to clipboard!')
                            ->copyMessageDuration(2000)
                            ->columnSpanFull()
                            ->fontFamily('mono')
                            ->size('sm')
                            ->color('primary')
                            ->weight('medium')
                            ->extraAttributes([
                                'class' => 'bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border-l-4 border-blue-500 whitespace-pre-wrap break-all',
                                'style' => 'font-family: "Fira Code", "Monaco", "Consolas", monospace; line-height: 1.5;',
                            ]),

                        Infolists\Components\TextEntry::make('query')
                            ->label('Raw SQL Query (with placeholders)')
                            ->copyable()
                            ->copyMessage('Raw query copied!')
                            ->columnSpanFull()
                            ->fontFamily('mono')
                            ->size('xs')
                            ->color('gray')
                            ->extraAttributes([
                                'class' => 'bg-gray-100 dark:bg-gray-700 p-2 rounded border text-xs opacity-75',
                                'style' => 'font-family: "Fira Code", "Monaco", "Consolas", monospace;',
                            ]),

                        Infolists\Components\TextEntry::make('query_hash')
                            ->label('Query Hash')
                            ->copyable()
                            ->copyMessage('Hash copied!')
                            ->fontFamily('mono')
                            ->size('xs')
                            ->color('gray')
                            ->extraAttributes([
                                'class' => 'bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs',
                            ]),
                    ])
                    ->collapsible()
                    ->persistCollapsed()
                    ->icon('heroicon-o-code-bracket'),

                Section::make('Bindings & Metadata')
                    ->schema([
                        Infolists\Components\TextEntry::make('bindings')
                            ->label('Query Bindings')
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return '// No parameter bindings';
                                }

                                return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                            })
                            ->copyable()
                            ->copyMessage('Bindings copied!')
                            ->fontFamily('mono')
                            ->size('sm')
                            ->color(fn ($state) => empty($state) ? 'gray' : 'info')
                            ->extraAttributes([
                                'class' => 'bg-slate-50 dark:bg-slate-800 p-3 rounded-lg border border-slate-200 dark:border-slate-600 whitespace-pre',
                                'style' => 'font-family: "Fira Code", "Monaco", "Consolas", monospace; line-height: 1.4;',
                            ])
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('user_id')
                                    ->label('Executed By')
                                    ->formatStateUsing(fn ($state) => $state ? "User ID: {$state}" : 'System/Anonymous')
                                    ->badge()
                                    ->color(fn ($state) => $state ? 'info' : 'gray'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Logged At')
                                    ->dateTime()
                                    ->since()
                                    ->badge()
                                    ->color('success'),
                            ]),
                    ])
                    ->columns(1)
                    ->collapsible()
                    ->persistCollapsed()
                    ->icon('heroicon-o-cog-6-tooth'),

                Section::make('Error Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('error_message')
                            ->label('Error Message')
                            ->formatStateUsing(fn ($state) => $state ?: 'No errors detected')
                            ->copyable()
                            ->copyMessage('Error message copied!')
                            ->color(fn ($state) => $state ? 'danger' : 'success')
                            ->weight(fn ($state) => $state ? 'bold' : 'normal')
                            ->extraAttributes(fn ($state) => $state ? [
                                'class' => 'bg-red-50 dark:bg-red-900/20 p-3 rounded-lg border-l-4 border-red-500 font-mono text-sm',
                            ] : [
                                'class' => 'bg-green-50 dark:bg-green-900/20 p-2 rounded-lg border-l-4 border-green-500',
                            ]),
                    ])
                    ->visible(fn (QueryPerformanceLog $record) => $record->status === 'error' || ! empty($record->error_message))
                    ->icon(fn (QueryPerformanceLog $record) => $record->status === 'error' ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-circle'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('connection')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'select' => 'success',
                        'insert' => 'info',
                        'update' => 'warning',
                        'delete' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('complete_query')
                    ->label('SQL Query')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->complete_query ?? '')
                    ->searchable(['query'])
                    ->fontFamily('mono')
                    ->size('sm'),
                Tables\Columns\TextColumn::make('execution_time')
                    ->label('Execution Time')
                    ->formatStateUsing(fn ($state) => number_format($state, 2).' ms')
                    ->color(fn (QueryPerformanceLog $record): string => match ($record->performance_status) {
                        'fast' => 'success',
                        'moderate' => 'warning',
                        'slow' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'error' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('executed_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('connection')
                    ->options(function () {
                        return QueryPerformanceLog::distinct()
                            ->pluck('connection', 'connection')
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'select' => 'SELECT',
                        'insert' => 'INSERT',
                        'update' => 'UPDATE',
                        'delete' => 'DELETE',
                        'create' => 'CREATE',
                        'drop' => 'DROP',
                        'alter' => 'ALTER',
                        'other' => 'OTHER',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'success' => 'Success',
                        'error' => 'Error',
                    ]),
                Tables\Filters\Filter::make('slow_queries')
                    ->label('Slow Queries (>1s)')
                    ->query(fn (Builder $query): Builder => $query->where('execution_time', '>=', 1000)),
                Tables\Filters\Filter::make('recent')
                    ->label('Last 24 Hours')
                    ->query(fn (Builder $query): Builder => $query->where('executed_at', '>=', now()->subDay())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('executed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQueryPerformanceLogs::route('/'),
            'view' => Pages\ViewQueryPerformanceLog::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->latest('executed_at');
    }
}
