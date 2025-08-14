<?php

namespace HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource\Pages;

use HkDevs\CodeForgeStudio\Resources\DataGenerationTemplateResource;
use Filament\Resources\Pages\EditRecord;

class EditDataGenerationTemplate extends EditRecord
{
    protected static string $resource = DataGenerationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ViewAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
