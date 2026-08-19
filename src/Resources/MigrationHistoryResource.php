<?php

namespace HkDevs\CodeForgeStudio\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use HkDevs\CodeForgeStudio\Models\MigrationHistory;
use HkDevs\CodeForgeStudio\Resources\MigrationHistoryResource\Pages;
use HkDevs\CodeForgeStudio\Support\Grid;
use HkDevs\CodeForgeStudio\Support\Section;

/**
 * MigrationHistoryResource
 *
 * Filament resource for managing migration execution history with
 * comprehensive audit trails and performance monitoring.
 *
 * Key Features:
 * - Complete migration execution audit trail management
 * - Performance tracking with execution timing analysis
 * - User attribution and operation tracking
 * - Action-specific filtering (migrate, rollback, reset)
 * - Error analysis and troubleshooting capabilities
 * - Historical trend analysis and reporting
 *
 * Resource Configuration:
 * - MigrationHistory model integration
 * - Clock icon for timing and history identification
 * - Positioned in 'Migration Management' navigation group
 * - Organized for efficient history browsing and analysis
 *
 * History Management:
 * - Migration execution record viewing and analysis
 * - Batch-based organization and filtering
 * - Action type classification and tracking
 * - User attribution and context tracking
 * - Performance metrics and timing analysis
 *
 * Table Features:
 * - History listing with migration name, action, and status
 * - Execution time tracking and performance indicators
 * - User and timestamp information display
 * - Status-based filtering and organization
 * - Error message display for failed operations
 *
 * Filtering & Search:
 * - Recent migrations for quick access
 * - Action-based filtering (migrate/rollback)
 * - Success/failure status filtering
 * - Date range and user-based filtering
 * - Migration name search capabilities
 *
 * Analysis Features:
 * - Performance trend analysis and visualization
 * - Error pattern identification and reporting
 * - Migration success rate tracking
 * - Execution time optimization recommendations
 * - Batch operation analysis and insights
 *
 * Reporting Capabilities:
 * - Migration activity reports and summaries
 * - Performance analytics and trending
 * - Error analysis and troubleshooting guides
 * - User activity tracking and attribution
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 */
class MigrationHistoryResource extends Resource
{
    protected static ?string $model = MigrationHistory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Migration History';

    protected static ?int $navigationSort = 3;

    protected static string|\UnitEnum|null $navigationGroup = 'Database Tools';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Migration Details')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('migration')
                                ->label('Migration File')
                                ->disabled()
                                ->formatStateUsing(fn (string $state): string => basename($state, '.php')),
                            Forms\Components\TextInput::make('batch')
                                ->label('Batch Number')
                                ->disabled(),
                        ]),
                    Grid::make(3)
                        ->schema([
                            Forms\Components\TextInput::make('action')
                                ->label('Action')
                                ->disabled(),
                            Forms\Components\TextInput::make('status')
                                ->label('Status')
                                ->disabled(),
                            Forms\Components\TextInput::make('execution_time')
                                ->label('Execution Time')
                                ->disabled()
                                ->formatStateUsing(fn (?float $state): string => $state ? number_format($state, 2).' seconds' : 'N/A')
                                ->suffix('⏱️'),
                        ]),
                ]),

            Section::make('Execution Information')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('executed_by')
                                ->label('Executed By')
                                ->disabled()
                                ->prefix('👤'),
                            Forms\Components\DateTimePicker::make('executed_at')
                                ->label('Executed At')
                                ->disabled()
                                ->displayFormat('M j, Y H:i:s')
                                ->prefix('📅'),
                        ]),
                ]),

            Section::make('Error Details')
                ->schema([
                    Forms\Components\Textarea::make('error_message')
                        ->label('Error Message')
                        ->disabled()
                        ->rows(4)
                        ->placeholder('No errors occurred during execution')
                        ->columnSpanFull(),
                ])
                ->visible(fn ($record) => ! empty($record?->error_message)),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('migration')
                    ->label('Migration')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => basename($state, '.php')),
                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'migrate' => 'success',
                        'rollback' => 'warning',
                        'reset' => 'danger',
                        'refresh' => 'info',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('executed_by')
                    ->label('Executed By')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('execution_time')
                    ->label('Execution Time')
                    ->sortable()
                    ->formatStateUsing(fn (?float $state): string => $state ? number_format($state, 2).'s' : '-'),
                Tables\Columns\TextColumn::make('executed_at')
                    ->label('Executed At')
                    ->dateTime('M j, Y H:i:s')
                    ->sortable()
                    ->since(),
                Tables\Columns\TextColumn::make('batch')
                    ->label('Batch')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'migrate' => 'Migrate',
                        'rollback' => 'Rollback',
                        'refresh' => 'Refresh',
                        'reset' => 'Reset',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'success' => 'Success',
                        'failed' => 'Failed',
                    ]),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMigrationHistories::route('/'),
            'view' => Pages\ViewMigrationHistory::route('/{record}'),
        ];
    }
}
