<?php

namespace HkDevs\CodeForgeStudio\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use HkDevs\CodeForgeStudio\Services\CodeGenerationService;

class CodeGenerationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            $this->getTotalGenerationsCount(),
            $this->getRecentGenerationsCount(),
            $this->getGeneratedFilesCount(),
        ];
    }

    protected function getTotalGenerationsCount(): Stat
    {
        try {
            $service = app(CodeGenerationService::class);
            $history = $service->getGenerationHistory(1000);
            $total = count($history);

            return Stat::make('Total Generations', $total)
                ->description('All time code generations')
                ->descriptionIcon('heroicon-m-code-bracket')
                ->color('primary');
        } catch (\Exception $e) {
            return Stat::make('Total Generations', 'Error')
                ->description('Unable to count generations')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

    protected function getRecentGenerationsCount(): Stat
    {
        try {
            $service = app(CodeGenerationService::class);
            $history = $service->getGenerationHistory(1000);

            $recent = collect($history)->filter(function ($item) {
                $createdAt = Carbon::parse($item['created_at']);

                return $createdAt->isAfter(now()->subDays(7));
            })->count();

            return Stat::make('Recent (7 days)', $recent)
                ->description('Generations this week')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($recent > 0 ? 'success' : 'gray');
        } catch (\Exception $e) {
            return Stat::make('Recent Generations', 'Error')
                ->description('Unable to count recent')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }

    protected function getGeneratedFilesCount(): Stat
    {
        try {
            $service = app(CodeGenerationService::class);
            $history = $service->getGenerationHistory(1000);

            $totalFiles = collect($history)->sum(function ($item) {
                return count($item['files_created'] ?? []);
            });

            return Stat::make('Generated Files', $totalFiles)
                ->description('Total files created')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info');
        } catch (\Exception $e) {
            return Stat::make('Generated Files', 'Error')
                ->description('Unable to count files')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger');
        }
    }
}
