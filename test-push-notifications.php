<?php

/**
 * Test script for Push Notifications
 *
 * Run this with: php test-push-notifications.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\PushNotificationService;

echo "=== Testing Push Notifications ===\n\n";

try {
    $pushService = new PushNotificationService();

    // Test 1: Basic notification
    echo "Test 1: Sending basic notification...\n";
    $result = $pushService->send(
        'Test Notification',
        'This is a test notification from PHP script',
        ['hello']
    );
    echo $result ? "✓ Success\n" : "✗ Failed\n";
    echo "\n";

    // Test 2: Created notification
    echo "Test 2: Sending 'Created' notification...\n";
    $result = $pushService->notifyCreated('Test Record', 'Test User', ['hello']);
    echo $result ? "✓ Success\n" : "✗ Failed\n";
    echo "\n";

    // Test 3: Updated notification
    echo "Test 3: Sending 'Updated' notification...\n";
    $result = $pushService->notifyUpdated('Test Record', 'Test User', ['hello']);
    echo $result ? "✓ Success\n" : "✗ Failed\n";
    echo "\n";

    // Test 4: Deleted notification
    echo "Test 4: Sending 'Deleted' notification...\n";
    $result = $pushService->notifyDeleted('Test Record', 'Test User', ['hello']);
    echo $result ? "✓ Success\n" : "✗ Failed\n";
    echo "\n";

    // Test 5: Custom notification
    echo "Test 5: Sending custom notification...\n";
    $result = $pushService->notifyCustom(
        'Custom Title',
        'This is a custom notification message',
        ['hello']
    );
    echo $result ? "✓ Success\n" : "✗ Failed\n";
    echo "\n";

    echo "=== All tests completed ===\n";
    echo "Check your browser to see if notifications appeared!\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

