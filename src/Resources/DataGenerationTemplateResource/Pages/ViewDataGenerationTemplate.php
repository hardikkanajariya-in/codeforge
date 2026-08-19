<?php

namespace HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource;

class ViewDataGenerationTemplate extends ViewRecord
{
    protected static string $resource = DataGenerationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
