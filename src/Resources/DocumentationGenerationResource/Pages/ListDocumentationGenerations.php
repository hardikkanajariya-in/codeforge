<?php

namespace HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource;
use Filament\Actions\Action;
use HkDevs\CodeForgeStudio\Services\SchemaDocumentationService;
use Filament\Notifications\Notification;

class ListDocumentationGenerations extends ListRecords
{
    protected static string $resource = DocumentationGenerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Action::make('create_snapshot')
                ->label('Create Schema Snapshot')
                ->icon('heroicon-o-camera')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->required()
                        ->placeholder('e.g., Pre-deployment snapshot'),
                    \Filament\Forms\Components\Textarea::make('description')
                        ->placeholder('Optional description'),
                ])
                ->action(function (array $data) {
                    try {
                        $service = app(SchemaDocumentationService::class);
                        $snapshot = $service->generateSchemaSnapshot($data['name'], $data['description'] ?? null);

                        Notification::make()
                            ->title('Schema Snapshot Created')
                            ->body("Snapshot '{$snapshot->name}' has been created with {$snapshot->tables_count} tables.")
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
