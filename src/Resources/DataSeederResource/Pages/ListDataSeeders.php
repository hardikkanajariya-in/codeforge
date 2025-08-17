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
                        $updated = 0;

                        foreach ($discovered as $seederData) {
                            // Check if seeder already exists
                            $existingSeeder = \HkDevs\CodeForgeStudio\Models\DataSeeder::where('class_name', $seederData['class_name'])->first();

                            if (!$existingSeeder) {
                                // Create new seeder
                                \HkDevs\CodeForgeStudio\Models\DataSeeder::create($seederData);
                                $count++;
                            } else {
                                // Update existing seeder with correct file path if it's different
                                if ($existingSeeder->file_path !== $seederData['file_path']) {
                                    $existingSeeder->update([
                                        'file_path' => $seederData['file_path'],
                                        'type' => $seederData['type'],
                                    ]);
                                    $updated++;
                                }
                            }
                        }

                        if ($count > 0 || $updated > 0) {
                            $message = [];
                            if ($count > 0) {
                                $message[] = "{$count} new seeders registered";
                            }
                            if ($updated > 0) {
                                $message[] = "{$updated} seeders updated with correct paths";
                            }

                            Notification::make()
                                ->title('Seeders Discovery Complete')
                                ->body(implode(', ', $message))
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('No Changes Needed')
                                ->body('All existing seeders are already registered with correct paths')
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

            Actions\Action::make('cleanup_invalid_seeders')
                ->label('Cleanup Invalid Seeders')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Cleanup Invalid Seeders')
                ->modalDescription('This will remove seeders whose files no longer exist or classes cannot be loaded.')
                ->action(function () {
                    try {
                        $invalidSeeders = collect();
                        $allSeeders = \HkDevs\CodeForgeStudio\Models\DataSeeder::all();

                        foreach ($allSeeders as $seeder) {
                            if (!$seeder->exists() || !class_exists($seeder->class_name)) {
                                $invalidSeeders->push($seeder);
                            }
                        }

                        if ($invalidSeeders->isEmpty()) {
                            Notification::make()
                                ->title('No Invalid Seeders')
                                ->body('All registered seeders are valid')
                                ->info()
                                ->send();
                            return;
                        }

                        // Delete invalid seeders
                        $deletedCount = 0;
                        foreach ($invalidSeeders as $seeder) {
                            $seeder->delete();
                            $deletedCount++;
                        }

                        Notification::make()
                            ->title('Cleanup Complete')
                            ->body("Removed {$deletedCount} invalid seeders")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Cleanup Failed')
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
