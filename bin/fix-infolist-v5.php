<?php

declare(strict_types=1);

$root = dirname(__DIR__) . '/src';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

foreach ($iterator as $file) {
    if (! $file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $path = $file->getPathname();
    $content = file_get_contents($path);

    if (! str_contains($content, 'Infolist')) {
        continue;
    }

    $original = $content;

    $content = str_replace('use Filament\Infolists\Infolist;', '', $content);
    $content = str_replace(
        'public static function infolist(Infolist $infolist): Infolist',
        'public static function infolist(Schema $schema): Schema',
        $content
    );
    $content = str_replace(
        'public function infolist(Infolist $infolist): Infolist',
        'public function infolist(Schema $schema): Schema',
        $content
    );

    $content = str_replace('return $infolist', 'return $schema', $content);
    $content = preg_replace('/\$infolist\s*->\s*schema\(/', '$schema->components(', $content);

    $content = str_replace('Infolists\Components\Section::', 'Section::', $content);
    $content = str_replace('Infolists\Components\Grid::', 'Grid::', $content);
    $content = str_replace('use Filament\Infolists\Components\Section;', '', $content);
    $content = str_replace('use Filament\Infolists\Components\Grid;', '', $content);

    if ($content !== $original) {
        if (! str_contains($content, 'use Filament\Schemas\Schema;')) {
            $content = preg_replace(
                '/(namespace [^;]+;\R)/',
                '$1use Filament\Schemas\Schema;' . PHP_EOL,
                $content,
                1
            );
        }

        if (
            str_contains($content, 'Section::')
            && ! str_contains($content, 'HkDevs\CodeForgeStudio\Support\Section')
        ) {
            $content = preg_replace(
                '/(namespace [^;]+;\R)/',
                '$1use HkDevs\CodeForgeStudio\Support\Section;' . PHP_EOL,
                $content,
                1
            );
        }

        if (
            str_contains($content, 'Grid::')
            && ! str_contains($content, 'HkDevs\CodeForgeStudio\Support\Grid')
        ) {
            $content = preg_replace(
                '/(namespace [^;]+;\R)/',
                '$1use HkDevs\CodeForgeStudio\Support\Grid;' . PHP_EOL,
                $content,
                1
            );
        }

        file_put_contents($path, $content);
        echo "Updated: {$path}\n";
    }
}
