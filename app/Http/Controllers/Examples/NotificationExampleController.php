<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use Illuminate\Http\JsonResponse;

/**
 * Example controller demonstrating custom push notification usage
 *
 * This controller shows various ways to send push notifications
 * beyond the automatic CRUD notifications
 */
class NotificationExampleController extends Controller
{
    protected PushNotificationService $pushNotification;

    public function __construct(PushNotificationService $pushNotification)
    {
        $this->pushNotification = $pushNotification;
    }

    /**
     * Send a test notification
     */
    public function sendTestNotification(): JsonResponse
    {
        $success = $this->pushNotification->send(
            'Test Notification',
            'This is a test notification from the system',
            ['hello']
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notification sent successfully' : 'Failed to send notification'
        ]);
    }

    /**
     * Send notification to specific user group
     */
    public function sendToAdmins(): JsonResponse
    {
        $adminInterest = $this->pushNotification->getInterest('admins');

        $success = $this->pushNotification->notifyCustom(
            'Admin Notification',
            'Important system update for administrators',
            [$adminInterest]
        );

        return response()->json([
            'success' => $success,
            'interest' => $adminInterest
        ]);
    }

    /**
     * Send notification to breeders
     */
    public function sendToBreeders(): JsonResponse
    {
        $breederInterest = $this->pushNotification->getInterest('breeders');

        $success = $this->pushNotification->notifyCustom(
            'Breeder Update',
            'New breeding information is available',
            [$breederInterest]
        );

        return response()->json([
            'success' => $success,
            'interest' => $breederInterest
        ]);
    }

    /**
     * Send notification to multiple groups
     */
    public function sendToMultipleGroups(): JsonResponse
    {
        $interests = [
            $this->pushNotification->getInterest('admins'),
            $this->pushNotification->getInterest('breeders'),
        ];

        $success = $this->pushNotification->notifyCustom(
            'System Announcement',
            'Important announcement for all users',
            $interests
        );

        return response()->json([
            'success' => $success,
            'interests' => $interests
        ]);
    }

    /**
     * Example of sending notification after a complex operation
     */
    public function complexOperation(): JsonResponse
    {
        // Perform your complex business logic here
        $operationResult = true; // Simulated result

        if ($operationResult) {
            $userName = auth()->user()->name ?? 'System';
            $this->pushNotification->notifyCustom(
                'Operation Complete',
                "{$userName} has completed a complex operation successfully",
                ['hello']
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Operation completed and notification sent'
        ]);
    }
}

