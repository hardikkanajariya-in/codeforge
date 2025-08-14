<?php

namespace HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use HkDevs\CodeForgeStudio\Resources\DocumentationGenerationResource;

class EditDocumentationGeneration extends EditRecord
{
    protected static string $resource = DocumentationGenerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Reset status to pending if configuration was changed
        if ($this->record->wasChanged(['format', 'scope', 'included_tables', 'options'])) {
            $this->record->update([
                'status' => 'pending',
                'file_path' => null,
                'file_size' => null,
                'generated_at' => null,
                'error_message' => null,
            ]);
        }
    }
}
