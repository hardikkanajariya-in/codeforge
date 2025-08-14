<?php

namespace HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource\Pages;

use HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource;
use Filament\Resources\Pages\ListRecords;

class ListDataGenerationTemplates extends ListRecords
{
    protected static string $resource = DataGenerationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
