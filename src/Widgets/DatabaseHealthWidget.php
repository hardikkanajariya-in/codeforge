<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class DatabaseHealthWidget extends Widget
{
    protected static string $view = 'codeforge-studio::widgets.database-health';
    protected int | string | array $columnSpan = 'full';

    protected function getHealthService(): DatabaseHealthService
    {
        return app(DatabaseHealthService::class);
    }

    public function getViewData(): array
    {
        $connection = config('database.default');

        return [
            'healthSummary' => $this->getHealthService()->getHealthSummary($connection),
            'connectionStatus' => $this->getConnectionStatus(),
            'performanceMetrics' => $this->getPerformanceMetrics(),
            'recentActivity' => $this->getRecentActivity(),
        ];
    }
    protected function getConnectionStatus(): array
    {
        return $this->getHealthService()->getConnectionStatus();
    }

    protected function getPerformanceMetrics(): array
    {
        $connection = config('database.default');
        return $this->getHealthService()->getPerformanceMetrics($connection);
    }

    protected function getRecentActivity(): array
    {
        try {
            // Check if our logs table exists
            if (!DB::getSchemaBuilder()->hasTable('database_manager_logs')) {
                return [];
            }

            return DB::table('database_manager_logs')
                ->orderBy('executed_at', 'desc')
                ->limit(5)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
