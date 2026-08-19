<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RecentMigrationsWidget extends Widget
{
    protected string $view = 'codeforge-studio::widgets.recent-migrations';

    public function getViewData(): array
    {
        return [
            'recentMigrations' => $this->getRecentMigrations(),
        ];
    }

    protected function getRecentMigrations(): array
    {
        try {
            if (!Schema::hasTable('migrations')) {
                return [];
            }

            return DB::table('migrations')
                ->orderBy('batch', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($migration) {
                    return [
                        'migration' => $migration->migration,
                        'batch' => $migration->batch,
                        'status' => 'completed',
                        'executed_at' => 'Batch ' . $migration->batch
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
