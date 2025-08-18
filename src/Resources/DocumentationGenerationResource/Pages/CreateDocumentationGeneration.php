<?php

namespace HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource;
use Filament\Notifications\Notification;
use HkDevs\CodeForgeStudio\Services\DocumentationGenerationService;

class CreateDocumentationGeneration extends CreateRecord
{
    protected static string $resource = DocumentationGenerationResource::class;

    protected function getRedirectUrl(): string
    {
        // Fix for Laravel bug where lastInsertId() returns wrong value
        // Find the actual record by name and use its real ID
        if ($this->record && $this->record->name) {
            $actualRecord = \HkDevs\CodeForgeStudio\Models\DocumentationGeneration::where('name', $this->record->name)
                ->orderBy('id', 'desc')
                ->first();

            if ($actualRecord) {
                return $this->getResource()::getUrl('view', ['record' => $actualRecord->id]);
            }
        }

        // Fallback to index if we can't find the record
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Optionally auto-generate the documentation immediately
        if (request()->boolean('generate_immediately')) {
            try {
                $service = app(DocumentationGenerationService::class, ['generation' => $this->record]);
                $service->generate();

                Notification::make()
                    ->title('Documentation Generated')
                    ->body('Your documentation has been generated successfully.')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Generation Failed')
                    ->body($e->getMessage())
                    ->warning()
                    ->send();
            }
        } else {
            Notification::make()
                ->title('Documentation Queued')
                ->body('Your documentation has been queued for generation. Click "Generate" to create it.')
                ->success()
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),

            \Filament\Actions\Action::make('create_and_generate')
                ->label('Create & Generate')
                ->color('success')
                ->action(function () {
                    // Set flag to generate immediately
                    request()->merge(['generate_immediately' => true]);
                    $this->create();
                }),

            $this->getCancelFormAction(),
        ];
    }
}
