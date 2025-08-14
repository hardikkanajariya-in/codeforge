<?php

namespace HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource;

class EditSchemaSnapshot extends EditRecord
{
    protected static string $resource = SchemaSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Remove array fields that might cause htmlspecialchars errors when populating form
        return array_diff_key($data, array_flip([
            'schema_data',
            'table_relationships',
            'model_mappings',
            'validation_rules',
            'policy_information'
        ]));
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Don't overwrite the JSON fields with empty values
        // Only update the editable fields
        return array_intersect_key($data, array_flip([
            'name',
            'description',
            'version',
            'is_baseline'
        ]));
    }
}
