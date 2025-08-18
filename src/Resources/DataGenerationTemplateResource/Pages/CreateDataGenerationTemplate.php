<?php

namespace HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource\Pages;

use HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource;
use Filament\Resources\Pages\CreateRecord;
use HkDevs\CodeForgeStudio\Models\DataGenerationTemplate;

class CreateDataGenerationTemplate extends CreateRecord
{
    protected static string $resource = DataGenerationTemplateResource::class;

    protected function getRedirectUrl(): string
    {
        // Fix for Laravel bug where lastInsertId() returns wrong value
        // Find the actual record by name and use its real ID
        if ($this->record && $this->record->name) {
            $actualRecord = DataGenerationTemplate::where('name', $this->record->name)
                ->orderBy('id', 'desc')
                ->first();

            if ($actualRecord) {
                return $this->getResource()::getUrl('view', ['record' => $actualRecord->id]);
            }
        }

        // Fallback to index if we can't find the record
        return $this->getResource()::getUrl('index');
    }
}
