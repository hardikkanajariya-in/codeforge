<?php

namespace HkDevs\CodeForgeStudio\Resources;

use HkDevs\CodeForgeStudio\Models\DataSeeder;
use HkDevs\CodeForgeStudio\Resources\DataSeederResource\Pages;
use HkDevs\CodeForgeStudio\Services\SeederExecutionService;
use HkDevs\CodeForgeStudio\Services\SeederDiscoveryService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * DataSeederResource
 * 
 * Filament resource for managing database seeders with comprehensive
 * execution tracking, configuration management, and monitoring capabilities.
 * 
 * Key Features:
 * - Complete seeder lifecycle management (create, edit, execute, monitor)
 * - Execution history tracking with detailed logging
 * - Priority-based seeder ordering and dependency management
 * - Auto-run configuration for automated deployment pipelines
 * - Status monitoring and health tracking
 * - Integration with SeederExecutionService
 * 
 * Resource Configuration:
 * - DataSeeder model integration
 * - Database icon for seeder identification
 * - Positioned in 'Data Management' navigation group
 * - Organized for efficient seeder discovery and execution
 * 
 * Seeder Management:
 * - Seeder registration and configuration
 * - Class name and file path management
 * - Execution configuration and parameters
 * - Priority ordering for dependency resolution
 * - Auto-run settings for automated execution
 * 
 * Table Features:
 * - Seeder listing with name, status, and last execution
 * - Priority-based ordering and organization
 * - Status indicators with color coding
 * - Execution history and performance tracking
 * - Bulk operations for seeder management
 * 
 * Form Configuration:
 * - Seeder metadata input (name, description, class)
 * - File path configuration and validation
 * - JSON configuration for execution parameters
 * - Status management and type classification
 * - Priority setting for execution order
 * 
 * Execution Features:
 * - Manual seeder execution from interface
 * - Bulk execution for multiple seeders
 * - Execution logging and error tracking
 * - Performance monitoring and optimization
 * - Integration with SeederExecutionLog tracking
 * 
 * Advanced Features:
 * - Seeder dependency analysis and visualization
 * - Execution scheduling and automation
 * - Performance analytics and optimization recommendations
 * - Integration with migration and schema management
 * 
 * @package HkDevs\CodeForgeStudio\Resources
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */

use Filament\Notifications\Notification;

class DataSeederResource extends Resource
{
    protected static ?string $model = DataSeeder::class;
    protected static ?string $navigationIcon = 'heroicon-o-play';
    protected static ?string $navigationLabel = 'Data Seeders';
    protected static ?string $modelLabel = 'Data Seeder';
    protected static ?string $pluralModelLabel = 'Data Seeders';
    protected static ?int $navigationSort = 5;

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

                        Forms\Components\Select::make('class_name')
                            ->required()
                            ->searchable()
                            ->options(function () {
                                return app(SeederDiscoveryService::class)
                                    ->getSeederOptions();
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $discoveryService = app(SeederDiscoveryService::class);
                                    $filePath = $discoveryService->getSeederFilePath($state);
                                    $set('file_path', $filePath);
                                }
                            })
                            ->live(debounce: 300)
                            ->helperText('Select an available seeder class from your project'),

                        Forms\Components\Placeholder::make('file_path_display')
                            ->label('File Path')
                            ->content(function (callable $get) {
                                $className = $get('class_name');
                                if ($className) {
                                    $discoveryService = app(SeederDiscoveryService::class);
                                    $filePath = $discoveryService->getSeederFilePath($className);
                                    return $filePath ?
                                        \Illuminate\Support\Str::limit($filePath, 80) :
                                        'File path will be determined automatically';
                                }
                                return 'Select a seeder class to see the file path';
                            })
                            ->helperText('This path is automatically determined based on the selected seeder class'),

                        Forms\Components\Hidden::make('file_path'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'laravel' => 'Laravel Seeder',
                                'generated' => 'Generated Seeder',
                                'custom' => 'Custom Seeder',
                            ])
                            ->required()
                            ->default('laravel'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->native(false)
                            ->required()
                            ->default('draft'),

                        Forms\Components\TextInput::make('priority')
                            ->numeric()
                            ->default(100)
                            ->helperText('Lower numbers run first'),

                        Forms\Components\Toggle::make('auto_run')
                            ->helperText('Run automatically in batch operations'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Advanced Configuration')
                    ->schema([
                        Forms\Components\KeyValue::make('configuration')
                            ->addActionLabel('Add Configuration')
                            ->keyLabel('Setting')
                            ->valueLabel('Value')
                            ->default([
                                'batch_size' => '1000',
                                'memory_limit' => '512M',
                                'use_transactions' => 'true',
                                'truncate_before_seed' => 'false',
                                'timeout' => '300',
                                'connection' => 'default',
                            ])
                            ->helperText('Common seeder configuration options are pre-filled. You can modify or add custom settings.'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'laravel',
                        'success' => 'generated',
                        'warning' => 'custom',
                    ])
                    ->icons([
                        'heroicon-o-code-bracket' => 'laravel',
                        'heroicon-o-cpu-chip' => 'generated',
                        'heroicon-o-wrench-screwdriver' => 'custom',
                    ]),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'gray' => 'inactive',
                        'warning' => 'draft',
                    ]),

                Tables\Columns\TextColumn::make('priority')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('auto_run')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('latest_execution_status')
                    ->label('Last Run')
                    ->getStateUsing(function (DataSeeder $record) {
                        $latest = $record->latestExecution();
                        return $latest ? $latest->status : 'Never run';
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'failed' => 'danger',
                        'started' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'draft' => 'Draft',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'laravel' => 'Laravel',
                        'generated' => 'Generated',
                        'custom' => 'Custom',
                    ]),

                Tables\Filters\Filter::make('auto_run')
                    ->query(fn(Builder $query): Builder => $query->where('auto_run', true))
                    ->label('Auto Run Only'),
            ])
            ->actions([
                Tables\Actions\Action::make('execute')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Execute Seeder')
                    ->modalDescription(fn(DataSeeder $record) => "Are you sure you want to execute '{$record->name}'?")
                    ->action(function (DataSeeder $record) {
                        try {
                            $service = app(SeederExecutionService::class);
                            $log = $service->executeSeeder($record);

                            if ($log->isCompleted()) {
                                Notification::make()
                                    ->title('Seeder executed successfully')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Seeder execution failed')
                                    ->body($log->error_message)
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Execution Error')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn(DataSeeder $record) => $record->canExecute()),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('view_logs')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(
                        fn(DataSeeder $record): string =>
                        route('filament.admin.resources.seeder-execution-logs.index') . '?tableFilters[seeder_name][value]=' . urlencode($record->name)
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('execute_selected')
                        ->icon('heroicon-o-play')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Execute Selected Seeders')
                        ->modalDescription('Are you sure you want to execute all selected seeders?')
                        ->action(function (Collection $records) {
                            $service = app(SeederExecutionService::class);
                            $results = $service->executeMultipleSeeders($records->pluck('id')->toArray());

                            $successful = collect($results)->filter(function ($result) {
                                return is_object($result) && $result->isCompleted();
                            })->count();

                            $failed = count($results) - $successful;

                            if ($failed === 0) {
                                Notification::make()
                                    ->title("All {$successful} seeders executed successfully")
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title("Batch execution completed")
                                    ->body("{$successful} successful, {$failed} failed")
                                    ->warning()
                                    ->send();
                            }
                        }),

                    BulkAction::make('activate')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn(Collection $records) => $records->each->update(['status' => 'active'])),

                    BulkAction::make('deactivate')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn(Collection $records) => $records->each->update(['status' => 'inactive'])),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('priority');
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
            'index' => Pages\ListDataSeeders::route('/'),
            'create' => Pages\CreateDataSeeder::route('/create'),
            'view' => Pages\ViewDataSeeder::route('/{record}'),
            'edit' => Pages\EditDataSeeder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Seeder Manager';
    }

    public static function getNavigationSort(): ?int
    {
        return config('codeforge-database-studio.navigation.sort', 100) + 5;
    }
}
