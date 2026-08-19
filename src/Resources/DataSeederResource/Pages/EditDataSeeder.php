<?php

namespace HkDevs\CodeForgeStudio\Resources\DataSeederResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use HkDevs\CodeForgeStudio\Resources\DataSeederResource;

class EditDataSeeder extends EditRecord
{
    protected static string $resource = DataSeederResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
