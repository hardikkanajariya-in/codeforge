<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root . '/src', RecursiveDirectoryIterator::SKIP_DOTS)
);

$uses = [
    'use HkDevs\\CodeForgeStudio\\Support\\FilamentSchema;',
    'use HkDevs\\CodeForgeStudio\\Support\\Section;',
    'use HkDevs\\CodeForgeStudio\\Support\\Grid;',
];

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $path = $file->getPathname();
    if (str_contains($path, DIRECTORY_SEPARATOR . 'Support' . DIRECTORY_SEPARATOR)) {
        continue;
    }

    $content = file_get_contents($path);
    if (! str_contains($content, 'FilamentSchema::configure')) {
        continue;
    }

    $content = str_replace('HkDevs\\CodeForgeStudio\\Support\\Section', 'Section', $content);
    $content = str_replace('HkDevs\\CodeForgeStudio\\Support\\Grid', 'Grid', $content);
    $content = str_replace('\\HkDevs\\CodeForgeStudio\\Support\\FilamentSchema', 'FilamentSchema', $content);

    foreach ($uses as $use) {
        if (! str_contains($content, $use)) {
            $content = preg_replace(
                '/(namespace HkDevs\\\\CodeForgeStudio[^;]+;\R)/',
                '$1' . $use . "\n",
                $content,
                1
            );
        }
    }

    file_put_contents($path, $content);
    echo "Fixed imports: {$path}\n";
}
