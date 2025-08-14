<?php

namespace HkDevs\CodeForgeStudio\Resources\DataSeederResource\Pages;

use HkDevs\CodeForgeStudio\Resources\DataSeederResource;
use HkDevs\CodeForgeStudio\Services\SeederExecutionService;
use HkDevs\CodeForgeStudio\Widgets\SeederStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListDataSeeders extends ListRecords
{
    protected static string $resource = DataSeederResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('discover_seeders')
                ->label('Discover Seeders')
                ->icon('heroicon-o-magnifying-glass')
                ->color('info')
                ->action(function () {
                    try {
                        $service = app(SeederExecutionService::class);
                        $discovered = $service->discoverSeeders();

                        $count = 0;
                        foreach ($discovered as $seederData) {
                            // Check if seeder already exists
                            $exists = \HkDevs\CodeForgeStudio\Models\DataSeeder::where('class_name', $seederData['class_name'])->exists();

                            if (!$exists) {
                                \HkDevs\CodeForgeStudio\Models\DataSeeder::create($seederData);
                                $count++;
                            }
                        }

                        if ($count > 0) {
                            Notification::make()
                                ->title('Seeders Discovered')
                                ->body("Found and registered {$count} new seeders")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('No New Seeders')
                                ->body('All existing seeders are already registered')
                                ->info()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Discovery Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('run_auto_seeders')
                ->label('Run Auto Seeders')
                ->icon('heroicon-o-play')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Run Auto Seeders')
                ->modalDescription('This will execute all seeders marked for auto-run in priority order.')
                ->action(function () {
                    try {
                        $autoSeeders = \HkDevs\CodeForgeStudio\Models\DataSeeder::active()
                            ->autoRun()
                            ->byPriority()
                            ->get();

                        if ($autoSeeders->isEmpty()) {
                            Notification::make()
                                ->title('No Auto Seeders')
                                ->body('No seeders are configured for auto-run')
                                ->info()
                                ->send();
                            return;
                        }

                        $service = app(SeederExecutionService::class);
                        $results = $service->executeMultipleSeeders($autoSeeders->pluck('id')->toArray());

                        $successful = collect($results)->filter(function ($result) {
                            return is_object($result) && $result->isCompleted();
                        })->count();

                        $failed = count($results) - $successful;

                        if ($failed === 0) {
                            Notification::make()
                                ->title('Auto Seeders Completed')
                                ->body("Successfully executed {$successful} seeders")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Auto Seeders Completed with Issues')
                                ->body("{$successful} successful, {$failed} failed")
                                ->warning()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Execution Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SeederStatsWidget::class,
        ];
    }
}
