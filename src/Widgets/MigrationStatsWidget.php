<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use HkDevs\CodeForgeStudio\Models\Migration;
use HkDevs\CodeForgeStudio\Models\MigrationHistory;
use Illuminate\Support\Facades\Schema;

class MigrationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            $this->getTotalMigrationsCount(),
            $this->getPendingMigrationsCount(),
            $this->getRecentActivityCount(),
        ];
    }

    protected function getTotalMigrationsCount(): Stat
    {
        try {
            $allMigrations = Migration::getAllMigrations();
            $total = $allMigrations->count();
            $executed = $allMigrations->where('status', 'executed')->count();
            
            return Stat::make('Total Migrations', $total)
                ->description($executed . ' executed')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary');
        } catch (\Exception $e) {
            return Stat::make('Total Migrations', 'Error')
                ->description('Unable to count migrations')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

    protected function getPendingMigrationsCount(): Stat
    {
        try {
            $allMigrations = Migration::getAllMigrations();
            $pending = $allMigrations->where('status', 'pending')->count();
            
            $color = $pending > 0 ? 'warning' : 'success';
            $description = $pending > 0 ? 'Need to be executed' : 'All up to date';
            
            return Stat::make('Pending Migrations', $pending)
                ->description($description)
                ->descriptionIcon('heroicon-m-clock')
                ->color($color);
        } catch (\Exception $e) {
            return Stat::make('Pending Migrations', 'Error')
                ->description('Unable to check status')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

    protected function getRecentActivityCount(): Stat
    {
        try {
            $recentCount = 0;
            $description = 'No recent activity';
            
            if (Schema::hasTable('migration_histories')) {
                $recentCount = MigrationHistory::where('executed_at', '>=', now()->subDays(7))->count();
                $description = $recentCount > 0 ? 'In the last 7 days' : 'No activity this week';
            }
            
            return Stat::make('Recent Activity', $recentCount)
                ->description($description)
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success');
        } catch (\Exception $e) {
            return Stat::make('Recent Activity', 'Error')
                ->description('Unable to fetch activity')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }
}