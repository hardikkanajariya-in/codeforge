<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\File;

class GeneratorStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            $this->getAvailableColumnTypes(),
            $this->getAvailableRelationTypes(),
            $this->getRecentGenerationsToday(),
        ];
    }

    protected function getAvailableColumnTypes(): Stat
    {
        $columnTypes = [
            'id',
            'bigId',
            'string',
            'char',
            'text',
            'mediumText',
            'longText',
            'integer',
            'bigInteger',
            'mediumInteger',
            'smallInteger',
            'tinyInteger',
            'float',
            'double',
            'decimal',
            'boolean',
            'enum',
            'json',
            'date',
            'dateTime',
            'timestamp',
            'time',
            'uuid',
            'ipAddress'
        ];

        return Stat::make('Column Types', count($columnTypes))
            ->description('Available for migrations')
            ->descriptionIcon('heroicon-m-list-bullet')
            ->color('blue');
    }

    protected function getAvailableRelationTypes(): Stat
    {
        $relationTypes = [
            'hasOne',
            'hasMany',
            'belongsTo',
            'belongsToMany',
            'morphTo',
            'morphOne',
            'morphMany'
        ];

        return Stat::make('Relation Types', count($relationTypes))
            ->description('Available for models')
            ->descriptionIcon('heroicon-m-link')
            ->color('green');
    }

    protected function getRecentGenerationsToday(): Stat
    {
        try {
            $historyFile = storage_path('app/codeforge-database-studio/generation-history.json');

            if (!File::exists($historyFile)) {
                return Stat::make('Today\'s Generations', 0)
                    ->description('No generations yet')
                    ->descriptionIcon('heroicon-m-calendar')
                    ->color('gray');
            }

            $history = json_decode(File::get($historyFile), true) ?? [];

            $today = collect($history)->filter(function ($item) {
                $createdAt = \Carbon\Carbon::parse($item['created_at']);
                return $createdAt->isToday();
            })->count();

            $color = match (true) {
                $today === 0 => 'gray',
                $today < 3 => 'yellow',
                $today < 10 => 'green',
                default => 'blue'
            };

            return Stat::make('Today\'s Generations', $today)
                ->description('Generated today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color($color);
        } catch (\Exception $e) {
            return Stat::make('Today\'s Generations', 'Error')
                ->description('Unable to count')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }
}
