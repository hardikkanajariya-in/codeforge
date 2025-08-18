<?php

namespace HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use HkDevs\CodeForgeStudio\Resources\SchemaSnapshotResource;
use HkDevs\CodeForgeStudio\Services\SchemaDocumentationService;
use Filament\Notifications\Notification;

class CreateSchemaSnapshot extends CreateRecord
{
    protected static string $resource = SchemaSnapshotResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            // Generate the actual schema data before creating the record
            $service = app(SchemaDocumentationService::class, ['connection' => $data['database_connection']]);
            $snapshot = $service->generateSchemaSnapshot(
                $data['name'],
                $data['description'] ?? null
            );

            // Add the generated schema data to the form data
            $data['schema_data'] = $snapshot->schema_data;
            $data['table_relationships'] = $snapshot->table_relationships;
            $data['model_mappings'] = $snapshot->model_mappings;
            $data['validation_rules'] = $snapshot->validation_rules;
            $data['policy_information'] = $snapshot->policy_information;
            $data['tables_count'] = $snapshot->tables_count;
            $data['relationships_count'] = $snapshot->relationships_count;
            $data['models_count'] = $snapshot->models_count;
            $data['hash'] = $snapshot->hash;
            $data['captured_at'] = now();
            $data['captured_by'] = auth()->user()?->name ?? 'System';

            // Delete the duplicate record created by the service
            $snapshot->delete();

            return $data;
        } catch (\Exception $e) {
            // If schema generation fails, show error and prevent creation
            Notification::make()
                ->title('Schema Generation Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Re-throw to prevent record creation
            throw $e;
        }
    }

    protected function getRedirectUrl(): string
    {
        // Fix for Laravel bug where lastInsertId() returns wrong value
        // Find the actual record by name and use its real ID
        if ($this->record && $this->record->name) {
            $actualRecord = \HkDevs\CodeForgeStudio\Models\SchemaSnapshot::where('name', $this->record->name)
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
        Notification::make()
            ->title('Schema Snapshot Created')
            ->body("Captured {$this->record->tables_count} tables and {$this->record->relationships_count} relationships.")
            ->success()
            ->send();
    }
}
