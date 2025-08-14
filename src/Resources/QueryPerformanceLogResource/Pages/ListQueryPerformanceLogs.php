<?php

namespace HkDevs\CodeForgeStudio\Resources\QueryPerformanceLogResource\Pages;

use HkDevs\CodeForgeStudio\Resources\QueryPerformanceLogResource;
use Filament\Resources\Pages\ListRecords;

class ListQueryPerformanceLogs extends ListRecords
{
    protected static string $resource = QueryPerformanceLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action needed for logs
        ];
    }
}
