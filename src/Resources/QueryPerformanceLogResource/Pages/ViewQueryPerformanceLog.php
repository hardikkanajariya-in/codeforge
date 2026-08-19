<?php

namespace HkDevs\CodeForgeStudio\Resources\QueryPerformanceLogResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use HkDevs\CodeForgeStudio\Resources\QueryPerformanceLogResource;

class ViewQueryPerformanceLog extends ViewRecord
{
    protected static string $resource = QueryPerformanceLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to List')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
                ->color('gray'),
        ];
    }
}
