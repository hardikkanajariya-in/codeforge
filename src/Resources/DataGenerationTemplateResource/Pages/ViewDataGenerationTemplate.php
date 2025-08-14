<?php

namespace HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource\Pages;

use HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource;
use Filament\Resources\Pages\ViewRecord;

class ViewDataGenerationTemplate extends ViewRecord
{
    protected static string $resource = DataGenerationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
        ];
    }
}
