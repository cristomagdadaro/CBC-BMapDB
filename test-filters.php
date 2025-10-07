<?php

// Test script to verify filter pipeline loads correctly
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Filter Pipeline...\n";

try {
    // Test FilterPipeline creation
    $pipeline = \App\Repository\Filters\FilterPipeline::createDefault();
    $filters = $pipeline->getFilters();

    echo "✓ Filter pipeline created successfully\n";
    echo "✓ Loaded " . count($filters) . " filters:\n";

    foreach ($filters as $filter) {
        echo "  - " . get_class($filter) . "\n";
    }

    echo "\nAll filters loaded successfully!\n";

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

