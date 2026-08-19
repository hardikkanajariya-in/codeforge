<?php

namespace HkDevs\CodeForgeStudio\Resources\QueryPerformanceLogResource\Pages;

use Filament\Resources\Pages\ListRecords;
use HkDevs\CodeForgeStudio\Resources\QueryPerformanceLogResource;

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
