<?php

namespace HkDevs\CodeForgeStudio\Commands;

use HkDevs\CodeForgeStudio\Services\AssetService;
use Illuminate\Console\Command;

/**
 * Asset Debug Command for CodeForge Studio
 *
 * Provides debugging information about asset locations and status
 *
 * @author hardikkanajariya.in
 */
class AssetDebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'codeforge:asset-debug';

    /**
     * The console command description.
     */
    protected $description = 'Debug CodeForge Studio asset locations and status';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 CodeForge Studio Asset Debug Information');
        $this->line('');

        $paths = AssetService::getAssetPaths();

        $this->info('📂 Asset Locations:');
        $this->table(['Type', 'Path', 'Exists'], [
            ['Published CSS', $paths['published_css'], $this->formatExists(file_exists($paths['published_css']))],
            ['Published JS', $paths['published_js'], $this->formatExists(file_exists($paths['published_js']))],
            ['Package CSS', $paths['package_css'], $this->formatExists(file_exists($paths['package_css']))],
            ['Package JS', $paths['package_js'], $this->formatExists(file_exists($paths['package_js']))],
        ]);

        $this->line('');
        $this->info('📊 Status:');
        $this->line('✅ Published Assets: '.($paths['published_exists'] ? 'Available' : 'Not Available'));
        $this->line('✅ Package Assets: '.($paths['package_exists'] ? 'Available' : 'Not Available'));

        $this->line('');
        $this->info('🌐 Asset URLs:');
        $this->line('CSS: '.AssetService::asset('css/schema-designer-v2.css'));
        $this->line('JS: '.AssetService::asset('js/schema-designer-v2.js'));

        if (! $paths['published_exists'] && $paths['package_exists']) {
            $this->line('');
            $this->warn('⚠️  Assets are not published but available in package directory.');
            $this->info('💡 Assets will be served directly from package via route.');
            $this->info('🚀 To publish assets, run: php artisan vendor:publish --tag=codeforge-studio-assets');
        } elseif ($paths['published_exists']) {
            $this->line('');
            $this->info('✅ Assets are published and will be served from public directory.');
        } else {
            $this->line('');
            $this->error('❌ Assets not found in either published or package directories!');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Format exists status for display
     */
    private function formatExists(bool $exists): string
    {
        return $exists ? '✅ Yes' : '❌ No';
    }
}
