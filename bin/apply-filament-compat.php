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

    $content = preg_replace(
        '/public static function form\(Form \$form\): Form/',
        'public static function form($schema): mixed',
        $content
    );
    $content = preg_replace(
        '/public function form\(Form \$form\): Form/',
        'public function form($schema): mixed',
        $content
    );
    $content = preg_replace(
        '/return \$form\s*\R\s*->schema\(\[/',
        'return \\HkDevs\\CodeForgeStudio\\Support\\FilamentSchema::configure($schema, [',
        $content
    );
    $content = str_replace('Forms\\Components\\Section', 'HkDevs\\CodeForgeStudio\\Support\\Section', $content);
    $content = str_replace('Forms\\Components\\Grid', 'HkDevs\\CodeForgeStudio\\Support\\Grid', $content);

    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated: {$path}\n";
    }
}
