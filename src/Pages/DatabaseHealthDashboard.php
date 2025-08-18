<?php

namespace HkDevs\CodeForgeStudio\Pages;

use HkDevs\CodeForgeStudio\Services\DatabaseHealthService;
use HkDevs\CodeForgeStudio\Widgets\DatabaseHealthMetricsWidget;
use HkDevs\CodeForgeStudio\Widgets\QueryPerformanceChart;
use HkDevs\CodeForgeStudio\Widgets\DatabaseHealthWidget;
use Filament\Pages\Page;
use Filament\Actions\Action;

/**
 * DatabaseHealthDashboard
 * 
 * Comprehensive database health monitoring dashboard providing real-time
 * metrics, performance analytics, and system health status visualization.
 * 
 * Key Features:
 * - Real-time database health metrics monitoring
 * - Interactive performance charts and analytics
 * - Multi-connection health status tracking
 * - Manual metric refresh and connection testing
 * - Widget-based dashboard layout with responsive design
 * - Integration with DatabaseHealthService for data collection
 * - Alert management and health status notifications
 * 
 * Dashboard Components:
 * - DatabaseHealthMetricsWidget: Core health metrics display
 * - QueryPerformanceChart: Performance trend visualization
 * - DatabaseHealthWidget: Overall health status summary
 * - Connection status indicators with real-time updates
 * 
 * Monitoring Capabilities:
 * - Response time tracking and alerting
 * - Memory usage monitoring and optimization recommendations
 * - Connection pool status and utilization metrics
 * - Query performance analysis with slow query detection
 * - System resource monitoring and capacity planning
 * 
 * User Actions:
 * - Manual metrics refresh for real-time updates
 * - Connection testing and validation
 * - Health alert acknowledgment and management
 * - Export capabilities for reporting and analysis
 * 
 * Navigation:
 * - Positioned in 'Database Health' group for logical organization
 * - Heart icon for intuitive health monitoring identification
 * - Priority sort order for dashboard prominence
 * 
 * @package HkDevs\CodeForgeStudio\Pages
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 */
class DatabaseHealthDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Health Monitor';
    protected static ?string $navigationGroup = 'Database Health';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'codeforge-studio::pages.database-health-dashboard';

    protected function getHealthService(): DatabaseHealthService
    {
        return app(DatabaseHealthService::class);
    }

    public function getTitle(): string
    {
        return 'Database Health Monitor';
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('refreshMetrics')
                ->label('Refresh Metrics')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $this->getHealthService()->getHealthSummary();
                    $this->dispatch('$refresh');
                }),
            Action::make('testConnections')
                ->label('Test All Connections')
                ->icon('heroicon-o-signal')
                ->action(function () {
                    $connections = array_keys(config('database.connections', []));
                    foreach ($connections as $connection) {
                        $this->getHealthService()->testConnection($connection);
                    }
                    $this->dispatch('$refresh');
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DatabaseHealthMetricsWidget::class,
        ];
    }

    protected function getWidgets(): array
    {
        return [
            DatabaseHealthWidget::class,
            QueryPerformanceChart::class,
        ];
    }

    public function getViewData(): array
    {
        $connection = config('database.default');
        $healthService = $this->getHealthService();

        return [
            'healthSummary' => $healthService->getHealthSummary($connection),
            'connectionStatus' => $healthService->getConnectionStatus(),
            'performanceMetrics' => $healthService->getPerformanceMetrics($connection),
        ];
    }
}
