<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use HkDevs\CodeForgeStudio\Models\DataSeeder;
use HkDevs\CodeForgeStudio\Models\SeederExecutionLog;

class SeederStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            $this->getTotalSeedersCount(),
            $this->getActiveSeedersCount(),
            $this->getRecentExecutionsCount(),
            $this->getSuccessRateCount(),
        ];
    }

    protected function getTotalSeedersCount(): Stat
    {
        try {
            $total = DataSeeder::count();
            $active = DataSeeder::active()->count();

            return Stat::make('Total Seeders', $total)
                ->description($active.' active')
                ->descriptionIcon('heroicon-m-play')
                ->color('primary');
        } catch (\Exception $e) {
            return Stat::make('Total Seeders', 'Error')
                ->description('Unable to count seeders')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

    protected function getActiveSeedersCount(): Stat
    {
        try {
            $active = DataSeeder::active()->count();
            $autoRun = DataSeeder::active()->autoRun()->count();

            $color = $active > 0 ? 'success' : 'warning';
            $description = $autoRun > 0 ? $autoRun.' auto-run' : 'None set for auto-run';

            return Stat::make('Active Seeders', $active)
                ->description($description)
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($color);
        } catch (\Exception $e) {
            return Stat::make('Active Seeders', 'Error')
                ->description('Unable to count active seeders')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

    protected function getRecentExecutionsCount(): Stat
    {
        try {
            $recent = SeederExecutionLog::recent(7)->count();
            $today = SeederExecutionLog::where('started_at', '>=', today())->count();

            $color = $recent > 0 ? 'info' : 'gray';
            $description = $today > 0 ? $today.' today' : 'None today';

            return Stat::make('Recent Executions', $recent)
                ->description($description.' (7 days)')
                ->descriptionIcon('heroicon-m-clock')
                ->color($color);
        } catch (\Exception $e) {
            return Stat::make('Recent Executions', 'Error')
                ->description('Unable to count executions')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

    protected function getSuccessRateCount(): Stat
    {
        try {
            $total = SeederExecutionLog::recent(30)->count();
            $successful = SeederExecutionLog::recent(30)->completed()->count();

            if ($total === 0) {
                return Stat::make('Success Rate', 'N/A')
                    ->description('No executions in 30 days')
                    ->descriptionIcon('heroicon-m-question-mark-circle')
                    ->color('gray');
            }

            $rate = round(($successful / $total) * 100, 1);
            $failed = $total - $successful;

            $color = match (true) {
                $rate >= 90 => 'success',
                $rate >= 70 => 'warning',
                default => 'danger',
            };

            return Stat::make('Success Rate', $rate.'%')
                ->description($failed.' failed (30 days)')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($color);
        } catch (\Exception $e) {
            return Stat::make('Success Rate', 'Error')
                ->description('Unable to calculate rate')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }
}
