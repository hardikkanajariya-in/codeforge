<?php

declare(strict_types=1);

$files = [
    'src/Pages/DocumentationGenerator.php',
    'src/Pages/FactoryGeneratorPage.php',
    'src/Pages/MigrationGeneratorPage.php',
    'src/Pages/ModelGeneratorPage.php',
    'src/Pages/SeederGeneratorPage.php',
    'src/Pages/SmartDataSeeder.php',
    'src/Resources/DatabaseHealthMetricResource.php',
    'src/Resources/DataGenerationTemplateResource.php',
    'src/Resources/DataSeederResource.php',
    'src/Resources/DocumentationGenerationResource.php',
    'src/Resources/MigrationHistoryResource.php',
    'src/Resources/QueryPerformanceLogResource.php',
    'src/Resources/SchemaSnapshotResource/Pages/CompareSchemaSnapshots.php',
    'src/Resources/SchemaSnapshotResource.php',
    'src/Resources/SeederExecutionLogResource.php',
];

$root = dirname(__DIR__);

$requiredUses = [
    'HkDevs\\CodeForgeStudio\\Support\\FilamentSchema',
    'HkDevs\\CodeForgeStudio\\Support\\Section',
    'HkDevs\\CodeForgeStudio\\Support\\Grid',
];

foreach ($files as $relative) {
    $path = $root . '/' . $relative;
    if (! is_file($path)) {
        continue;
    }

    $content = file_get_contents($path);

    // Remove broken short imports
    $content = preg_replace('/^use (FilamentSchema|Section|Grid);\R/m', '', $content);
    $content = preg_replace('/^use HkDevs\\\\CodeForgeStudio\\\\Support\\\\(FilamentSchema|Section|Grid);\R/m', '', $content);

    // Ensure Section/Grid references are unqualified class names
    $content = str_replace('HkDevs\\CodeForgeStudio\\Support\\Section', 'Section', $content);
    $content = str_replace('HkDevs\\CodeForgeStudio\\Support\\Grid', 'Grid', $content);
    $content = str_replace('\\HkDevs\\CodeForgeStudio\\Support\\FilamentSchema', 'FilamentSchema', $content);

    foreach ($requiredUses as $fqcn) {
        $useLine = "use {$fqcn};";
        if (! str_contains($content, $useLine)) {
            $content = preg_replace(
                '/(namespace [^;]+;\R)/',
                '$1' . $useLine . "\n",
                $content,
                1
            );
        }
    }

    file_put_contents($path, $content);
    echo "Normalized: {$relative}\n";
}
