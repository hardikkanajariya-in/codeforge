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

    if (! str_contains($content, 'FilamentSchema::configure')) {
        continue;
    }

    if (! preg_match('/namespace ([^;]+);/', $content, $namespaceMatch)) {
        continue;
    }

    $namespace = $namespaceMatch[1];

    if (! str_contains($content, 'use Filament\Schemas\Schema;')) {
        $content = preg_replace(
            '/(namespace '.preg_quote($namespace, '/').';\R)/',
            '$1use Filament\Schemas\Schema;'."\n",
            $content,
            1
        );
    }

    $content = preg_replace(
        '/public static function form\(\$schema\): mixed/',
        'public static function form(Schema $schema): Schema',
        $content
    );

    $content = preg_replace(
        '/public function form\(\$schema\): mixed/',
        'public function form(Schema $schema): Schema',
        $content
    );

    $content = str_replace(
        'return FilamentSchema::configure($schema, [',
        'return $schema->components([',
        $content
    );

    // Remove FilamentSchema use if unused
    if (! str_contains($content, 'FilamentSchema::')) {
        $content = preg_replace('/^use HkDevs\\\\CodeForgeStudio\\\\Support\\\\FilamentSchema;\R/m', '', $content);
    }

    file_put_contents($path, $content);
    echo "Updated form signature: {$path}\n";
}
