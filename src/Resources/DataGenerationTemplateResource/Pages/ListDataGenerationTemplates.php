<?php

namespace HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource;

class ListDataGenerationTemplates extends ListRecords
{
    protected static string $resource = DataGenerationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
