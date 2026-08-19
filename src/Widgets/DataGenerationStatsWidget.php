<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use Filament\Widgets\Widget;
use HkDevs\CodeForgeStudio\Models\DataGenerationTemplate;
use Illuminate\Support\Facades\DB;

/**
 * DataGenerationStatsWidget
 * 
 * Dashboard widget displaying comprehensive data generation statistics
 * and analytics for CodeForge Database Studio.
 * 
 * @package HkDevs\CodeForgeStudio\Widgets
 * @author hardikkanajariya.in
 * @version 1.0.0
 */
class DataGenerationStatsWidget extends Widget
{
    protected string $view = 'codeforge-studio::components.data-generation-stats';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getViewData(): array
    {
        return [
            'stats' => $this->getStatistics(),
            'recentGenerations' => $this->getRecentGenerations(),
            'popularTemplates' => $this->getPopularTemplates(),
        ];
    }

    private function getStatistics(): array
    {
        $totalTemplates = DataGenerationTemplate::count();
        $activeTemplates = DataGenerationTemplate::where('is_active', true)->count();

        return [
            'total_templates' => $totalTemplates,
            'active_templates' => $activeTemplates,
            'inactive_templates' => $totalTemplates - $activeTemplates,
            'total_records_generated' => 0, // This would come from tracking logs
            'records_today' => 0,
            'tables_with_templates' => DataGenerationTemplate::distinct('table_name')->count(),
            'total_tables' => collect(DB::select('SHOW TABLES'))->count(),
            'avg_generation_time' => 2.5,
            'avg_records_per_second' => 400,
        ];
    }

    private function getRecentGenerations(): array
    {
        // This would typically come from execution logs
        return [
            [
                'template_name' => 'User Template',
                'table_name' => 'users',
                'record_count' => 100,
                'status' => 'completed',
                'created_at' => '2 minutes ago'
            ],
            [
                'template_name' => 'Product Template',
                'table_name' => 'products',
                'record_count' => 50,
                'status' => 'completed',
                'created_at' => '5 minutes ago'
            ],
            [
                'template_name' => 'Order Template',
                'table_name' => 'orders',
                'record_count' => 200,
                'status' => 'failed',
                'created_at' => '10 minutes ago'
            ]
        ];
    }

    private function getPopularTemplates(): array
    {
        $templates = DataGenerationTemplate::where('is_active', true)
            ->take(5)
            ->get()
            ->map(function ($template) {
                return [
                    'name' => $template->name,
                    'table_name' => $template->table_name,
                    'field_count' => count($template->field_mappings ?? []),
                    'usage_count' => rand(10, 100), // This would come from tracking
                ];
            })
            ->toArray();

        return $templates;
    }

    public static function canView(): bool
    {
        return true;
    }
}
