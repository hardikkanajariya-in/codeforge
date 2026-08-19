<?php

namespace HkDevs\CodeForgeStudio\Resources\DataSeederResource\Pages;
use HkDevs\CodeForgeStudio\Support\Section;
use Filament\Schemas\Schema;

use HkDevs\CodeForgeStudio\Resources\DataSeederResource;
use HkDevs\CodeForgeStudio\Services\SeederExecutionService;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

use Filament\Infolists\Components;

class ViewDataSeeder extends ViewRecord
{
    protected static string $resource = DataSeederResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('execute')
                ->label('Execute Seeder')
                ->icon('heroicon-o-play')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Execute Seeder')
                ->modalDescription(fn() => "Are you sure you want to execute '{$this->record->name}'?")
                ->action(function () {
                    try {
                        $service = app(SeederExecutionService::class);
                        $log = $service->executeSeeder($this->record);

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
                ->visible(fn() => $this->record->canExecute()),

            Actions\EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Components\Section::make('Basic Information')
                    ->schema([
                        Components\TextEntry::make('name'),
                        Components\TextEntry::make('description'),
                        Components\TextEntry::make('class_name'),
                        Components\TextEntry::make('file_path'),
                    ])
                    ->columns(2),

                Components\Section::make('Configuration')
                    ->schema([
                        Components\TextEntry::make('type')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'laravel' => 'primary',
                                'generated' => 'success',
                                'custom' => 'warning',
                                default => 'gray',
                            }),

                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'gray',
                                'draft' => 'warning',
                                default => 'gray',
                            }),

                        Components\TextEntry::make('priority'),

                        Components\IconEntry::make('auto_run')
                            ->boolean(),
                    ])
                    ->columns(2),

                Components\Section::make('Execution History')
                    ->schema([
                        Components\TextEntry::make('latest_execution.status')
                            ->label('Last Execution Status')
                            ->badge()
                            ->color(fn(?string $state): string => match ($state) {
                                'completed' => 'success',
                                'failed' => 'danger',
                                'started' => 'warning',
                                default => 'gray',
                            })
                            ->default('Never executed'),

                        Components\TextEntry::make('latest_execution.started_at')
                            ->label('Last Execution Time')
                            ->dateTime()
                            ->placeholder('Never executed'),

                        Components\TextEntry::make('latest_execution.execution_time')
                            ->label('Execution Time')
                            ->suffix(' seconds')
                            ->placeholder('N/A'),

                        Components\TextEntry::make('execution_stats')
                            ->label('Success Rate')
                            ->getStateUsing(function () {
                                $total = $this->record->executionLogs()->count();
                                $successful = $this->record->successfulExecutions()->count();

                                if ($total === 0) {
                                    return 'No executions';
                                }

                                $rate = round(($successful / $total) * 100, 1);
                                return "{$successful}/{$total} ({$rate}%)";
                            }),
                    ])
                    ->columns(2),

                Components\Section::make('Advanced Configuration')
                    ->schema([
                        Components\KeyValueEntry::make('configuration')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
