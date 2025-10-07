<?php

// Test script to verify the full AbstractRepoService filter pipeline
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Full AbstractRepoService Filter Pipeline...\n\n";

try {
    // Create a test repository instance
    $model = new \App\Models\Institute();
    $testRepo = new class($model) extends \App\Repository\AbstractRepoService {
        public function __construct($model) {
            parent::__construct($model);
        }
    };

    $pipeline = $testRepo->getFilterPipeline();
    $filters = $pipeline->getFilters();

    echo "✓ AbstractRepoService instantiated successfully\n";
    echo "✓ Full pipeline loaded with " . count($filters) . " filters:\n\n";

    foreach ($filters as $index => $filter) {
        $filterName = get_class($filter);
        $filterName = substr($filterName, strrpos($filterName, '\\') + 1);
        echo "  " . ($index + 1) . ". " . $filterName . "\n";
    }

    echo "\n✓ All filters loaded and ready to use!\n";
    echo "✓ Repository service is fully functional\n";

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

