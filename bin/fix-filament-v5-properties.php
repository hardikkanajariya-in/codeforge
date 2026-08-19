<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root . '/src', RecursiveDirectoryIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $path = $file->getPathname();
    $content = file_get_contents($path);
    $original = $content;

    $content = preg_replace(
        '/protected static \?string \$navigationIcon/',
        'protected static string | \BackedEnum | null $navigationIcon',
        $content
    );

    $content = preg_replace(
        '/protected static string \$view/',
        'protected string $view',
        $content
    );

    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated: {$path}\n";
    }
}
