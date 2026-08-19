<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root.'/src', RecursiveDirectoryIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $path = $file->getPathname();
    $content = file_get_contents($path);
    $original = $content;

    $content = preg_replace('/^use Filament\\\\Section;\R/m', '', $content);
    $content = str_replace(
        'use HkDevs\CodeForgeStudio\Support\Section;use Filament\Forms\Form;',
        "use HkDevs\CodeForgeStudio\Support\Section;\nuse Filament\Forms\Form;",
        $content
    );

    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Cleaned: {$path}\n";
    }
}
