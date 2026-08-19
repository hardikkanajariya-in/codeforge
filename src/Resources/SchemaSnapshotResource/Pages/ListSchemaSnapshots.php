<?php

namespace HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource;
use HkDevs\CodeForgeStudio\Services\SchemaDocumentationService;

class ListSchemaSnapshots extends ListRecords
{
    protected static string $resource = SchemaSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('auto_snapshot')
                ->label('Auto Snapshot')
                ->icon('heroicon-o-camera')
                ->color('success')
                ->action(function () {
                    try {
                        $service = app(SchemaDocumentationService::class);
                        $snapshot = $service->generateSchemaSnapshot(
                            'Auto-generated snapshot '.now()->format('Y-m-d H:i:s'),
                            'Automatically generated schema snapshot'
                        );

                        Notification::make()
                            ->title('Snapshot Created')
                            ->body("Created snapshot with {$snapshot->tables_count} tables and {$snapshot->relationships_count} relationships.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Snapshot Creation Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
