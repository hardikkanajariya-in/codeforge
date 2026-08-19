<?php

namespace HkDevs\CodeForgeStudio\Resources\DataSeederResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use HkDevs\CodeForgeStudio\Models\DataSeeder;
use HkDevs\CodeForgeStudio\Resources\DataSeederResource;

class CreateDataSeeder extends CreateRecord
{
    protected static string $resource = DataSeederResource::class;

    protected function getRedirectUrl(): string
    {
        // Fix for Laravel bug where lastInsertId() returns wrong value
        // Find the actual record by name and use its real ID
        if ($this->record && $this->record->name) {
            $actualRecord = DataSeeder::where('name', $this->record->name)
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
