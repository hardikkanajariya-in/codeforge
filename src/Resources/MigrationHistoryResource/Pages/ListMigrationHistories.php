<?php

namespace HkDevs\CodeForgeStudio\Resources\MigrationHistoryResource\Pages;

use HkDevs\CodeForgeStudio\Resources\MigrationHistoryResource;
use Filament\Resources\Pages\ListRecords;

class ListMigrationHistories extends ListRecords
{
    protected static string $resource = MigrationHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Add any header actions if needed
        ];
    }
}
