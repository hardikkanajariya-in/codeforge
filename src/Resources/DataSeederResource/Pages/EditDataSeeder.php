<?php

namespace HkDevs\CodeForgeStudio\Resources\DataSeederResource\Pages;

use HkDevs\CodeForgeStudio\Resources\DataSeederResource;
use Filament\Resources\Pages\EditRecord;

class EditDataSeeder extends EditRecord
{
    protected static string $resource = DataSeederResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ViewAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
