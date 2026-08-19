<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use HkDevs\CodeForgeStudio\Models\QueryPerformanceLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class QueryPerformanceChart extends ChartWidget
{
    protected ?string $heading = 'Query Performance (24 Hours)';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $hours = collect(range(23, 0))->map(function ($hour) {
            return Carbon::now()->subHours($hour)->format('H:00');
        });

        $queryData = QueryPerformanceLog::where('executed_at', '>=', now()->subDay())
            ->selectRaw('HOUR(executed_at) as hour, AVG(execution_time) as avg_time, COUNT(*) as query_count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $avgTimes = $hours->map(function ($hour, $index) use ($queryData) {
            $hourIndex = 23 - $index;
            return $queryData->get($hourIndex)?->avg_time ?? 0;
        });

        $queryCounts = $hours->map(function ($hour, $index) use ($queryData) {
            $hourIndex = 23 - $index;
            return $queryData->get($hourIndex)?->query_count ?? 0;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Avg Response Time (ms)',
                    'data' => $avgTimes->values()->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Query Count',
                    'data' => $queryCounts->values()->toArray(),
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $hours->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Time (Hour)',
                    ],
                ],
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Response Time (ms)',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Query Count',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}
