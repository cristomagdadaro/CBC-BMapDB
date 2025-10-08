<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class PushNotificationService
{
    protected string $instanceId;
    protected string $secretKey;
    protected string $apiUrl;
    protected bool $enabled;
    protected string $defaultIcon;

    public function __construct()
    {
        $this->instanceId = config('pushnotifications.instance_id');
        $this->secretKey = config('pushnotifications.secret_key');
        $this->enabled = config('pushnotifications.enabled', true);
        $this->defaultIcon = asset(config('pushnotifications.icon', '/img/logos/pin.webp'));
        $this->apiUrl = "https://{$this->instanceId}.pushnotifications.pusher.com/publish_api/v1/instances/{$this->instanceId}/publishes";
    }

    /**
     * Send a push notification to specified interests
     *
     * @param string $title
     * @param string $body
     * @param array $interests
     * @param string|null $icon
     * @return bool
     */
    public function send(string $title, string $body, array $interests = ['hello'], ?string $icon = null): bool
    {
        if (!$this->enabled) {
            Log::info('Push notifications are disabled');
            return false;
        }

        try {
            $payload = [
                'interests' => $interests,
                'web' => [
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'icon' => $icon ?? $this->defaultIcon,
                    ],
                ],
            ];

            $response = Http::withToken($this->secretKey)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->apiUrl, $payload);

            if ($response->successful()) {
                Log::info('Push notification sent', ['title' => $title, 'body' => $body, 'interests' => $interests]);
                return true;
            }

            Log::error('Push notification failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Push notification exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send notification for create action
     */
    public function notifyCreated(string $resource, string $userName, array $interests = ['hello']): bool
    {
        $title = "New {$resource} Created";
        $body = "{$userName} has created a new {$resource}";
        return $this->send($title, $body, $interests);
    }

    /**
     * Send notification for update action
     */
    public function notifyUpdated(string $resource, string $userName, array $interests = ['hello']): bool
    {
        $title = "{$resource} Updated";
        $body = "{$userName} has updated a {$resource}";
        return $this->send($title, $body, $interests);
    }

    /**
     * Send notification for delete action
     */
    public function notifyDeleted(string $resource, string $userName, array $interests = ['hello']): bool
    {
        $title = "{$resource} Deleted";
        $body = "{$userName} has deleted a {$resource}";
        return $this->send($title, $body, $interests);
    }

    /**
     * Send custom notification
     */
    public function notifyCustom(string $title, string $body, array $interests = ['hello']): bool
    {
        return $this->send($title, $body, $interests);
    }

    /**
     * Get interest by key from config
     */
    public function getInterest(string $key): string
    {
        return config("pushnotifications.interests.{$key}", config('pushnotifications.default_interest'));
    }
}
