<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use HkDevs\CodeForgeStudio\Models\DatabaseHealthMetric;
use HkDevs\CodeForgeStudio\Models\QueryPerformanceLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DatabaseHealthMetricsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $connection = config('database.default');
        $recent = now()->subHours(24);

        // Get connection status
        $latestConnectionCheck = DatabaseHealthMetric::where('connection', $connection)
            ->where('metric_name', 'response_time')
            ->latest('recorded_at')
            ->first();

        $connectionStatus = $latestConnectionCheck ? 'Connected' : 'Unknown';
        $responseTime = $latestConnectionCheck ? number_format($latestConnectionCheck->value, 2) . ' ms' : 'N/A';

        // Get query statistics
        $totalQueries = QueryPerformanceLog::where('connection', $connection)
            ->where('executed_at', '>=', $recent)
            ->count();

        $avgResponseTime = QueryPerformanceLog::where('connection', $connection)
            ->where('executed_at', '>=', $recent)
            ->avg('execution_time');

        $slowQueries = QueryPerformanceLog::where('connection', $connection)
            ->where('executed_at', '>=', $recent)
            ->where('execution_time', '>=', 1000)
            ->count();

        $errorRate = QueryPerformanceLog::where('connection', $connection)
            ->where('executed_at', '>=', $recent)
            ->where('status', 'error')
            ->count();

        // Get database size
        $latestSizeMetric = DatabaseHealthMetric::where('connection', $connection)
            ->where('metric_name', 'database_size')
            ->latest('recorded_at')
            ->first();

        $databaseSize = $latestSizeMetric ? $latestSizeMetric->formatted_value : 'N/A';

        return [
            Stat::make('Connection Status', $connectionStatus)
                ->description("Response time: {$responseTime}")
                ->descriptionIcon('heroicon-m-signal')
                ->color($latestConnectionCheck ? 'success' : 'warning'),

            Stat::make('Queries (24h)', number_format($totalQueries))
                ->description('Total database queries')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),

            Stat::make('Avg Response Time', $avgResponseTime ? number_format($avgResponseTime, 2) . ' ms' : 'N/A')
                ->description('Average query execution time')
                ->descriptionIcon('heroicon-m-clock')
                ->color($avgResponseTime && $avgResponseTime < 100 ? 'success' : ($avgResponseTime && $avgResponseTime < 500 ? 'warning' : 'danger')),

            Stat::make('Slow Queries', number_format($slowQueries))
                ->description('Queries > 1000ms (24h)')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($slowQueries > 0 ? 'danger' : 'success'),

            Stat::make('Error Rate', number_format($errorRate))
                ->description('Failed queries (24h)')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($errorRate > 0 ? 'danger' : 'success'),

            Stat::make('Database Size', $databaseSize)
                ->description('Total database size')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('info'),
        ];
    }
}
