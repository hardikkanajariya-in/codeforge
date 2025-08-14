<?php

namespace HkDevs\CodeForgeStudio\Resources\DatabaseHealthMetricResource\Pages;

use HkDevs\CodeForgeStudio\Resources\DatabaseHealthMetricResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDatabaseHealthMetric extends ViewRecord
{
    protected static string $resource = DatabaseHealthMetricResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to List')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
                ->color('gray'),

            Actions\Action::make('refresh_metrics')
                ->label('Refresh Metrics')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function () {
                    // Trigger a new health check for this connection
                    $healthService = app(\HkDevs\CodeForgeStudio\Services\DatabaseHealthService::class);
                    $healthService->testConnection($this->record->connection);

                    $this->refreshFormData(['record']);

                    $this->notify('success', 'Health metrics refreshed successfully!');
                })
                ->requiresConfirmation()
                ->modalHeading('Refresh Health Metrics')
                ->modalDescription('This will run a new health check for this database connection.'),
        ];
    }
}
