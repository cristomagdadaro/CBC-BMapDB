# Push Notifications Implementation

## Overview
Real-time push notifications have been implemented for all CRUD operations using Pusher Beams. Notifications are automatically sent when users create, update, or delete records in the system.

## Architecture

### 1. PushNotificationService
Location: `app/Services/PushNotificationService.php`

This service handles all push notification logic:
- Sending notifications via Pusher Beams API
- Managing notification interests (topics)
- Configurable enable/disable functionality
- Custom notification templates for CRUD operations

### 2. AbstractRepoService Integration
Location: `app/Repository/AbstractRepoService.php`

Push notifications are automatically integrated into the base repository service:
- **Create**: Notifies when a new record is created
- **Update**: Notifies when a record is updated
- **Delete**: Notifies when a record is deleted
- **Multi-Delete**: Notifies with count when multiple records are deleted

All repositories that extend `AbstractRepoService` automatically inherit this functionality.

## Configuration

### Config File
Location: `config/pushnotifications.php`

```php
return [
    'instance_id' => env('PUSHER_BEAMS_INSTANCE_ID'),
    'secret_key' => env('PUSHER_BEAMS_SECRET_KEY'),
    'default_interest' => env('PUSHER_BEAMS_DEFAULT_INTEREST', 'hello'),
    'enabled' => env('PUSHER_BEAMS_ENABLED', true),
    'icon' => env('PUSHER_BEAMS_ICON', '/img/logos/pin.webp'),
    'interests' => [
        'all' => 'hello',
        'admins' => 'admin-notifications',
        'breeders' => 'breeder-notifications',
        'twg' => 'twg-notifications',
        'pbmap' => 'pbmap-notifications',
    ],
];
```

### Environment Variables
Add these to your `.env` file:

```env
PUSHER_BEAMS_INSTANCE_ID=a2819254-58af-4d1f-a99b-72bfa4d2c0c1
PUSHER_BEAMS_SECRET_KEY=35BE9D129473C9436642AFDF3CC60B309E11BFAA4ABF2DECA0840C72F4DD1D62
PUSHER_BEAMS_DEFAULT_INTEREST=hello
PUSHER_BEAMS_ENABLED=true
PUSHER_BEAMS_ICON=/img/logos/pin.webp
```

## Frontend Setup

The frontend is already configured in `resources/views/app.blade.php`:

```javascript
const beamsClient = new PusherPushNotifications.Client({
    instanceId: 'a2819254-58af-4d1f-a99b-72bfa4d2c0c1',
});

beamsClient.start()
    .then(() => beamsClient.addDeviceInterest('hello'))
    .then(() => console.log('Successfully registered and subscribed!'))
    .catch(console.error);
```

## Usage

### Automatic Notifications (Already Enabled)
All CRUD operations automatically trigger notifications. No additional code is needed in your controllers.

**Example:** When a user creates an Application:
```php
// In ApplicationController
public function store(CreateApplicationRequest $request)
{
    return parent::_store($request); // Notification sent automatically
}
```

### Custom Notifications
For custom notifications in controllers:

```php
use App\Services\PushNotificationService;

class YourController extends Controller
{
    protected PushNotificationService $pushNotification;

    public function __construct()
    {
        $this->pushNotification = new PushNotificationService();
    }

    public function yourMethod()
    {
        // Send custom notification
        $this->pushNotification->notifyCustom(
            'Custom Title',
            'Custom message body',
            ['hello'] // interests array
        );
    }
}
```

### Disabling Notifications for Specific Repositories
In a specific repository class:

```php
class YourRepository extends AbstractRepoService
{
    protected bool $enablePushNotifications = false; // Disable notifications
}
```

### Custom Resource Names
Override the resource name for better notification messages:

```php
class YourRepository extends AbstractRepoService
{
    protected function getResourceName(): string
    {
        return 'Custom Resource Name';
    }
}
```

### Targeting Specific User Groups
Use different interests to target specific user groups:

```php
// Send to admins only
$interests = [config('pushnotifications.interests.admins')];
$this->pushNotification->notifyCustom('Admin Alert', 'Important message', $interests);

// Send to breeders
$interests = [config('pushnotifications.interests.breeders')];
$this->pushNotification->notifyCreated('Breeder Record', auth()->user()->name, $interests);
```

## Notification Types

### 1. Created Notification
```
Title: "New {Resource} Created"
Body: "{UserName} has created a new {Resource}"
```

### 2. Updated Notification
```
Title: "{Resource} Updated"
Body: "{UserName} has updated a {Resource}"
```

### 3. Deleted Notification
```
Title: "{Resource} Deleted"
Body: "{UserName} has deleted a {Resource}"
```

### 4. Multi-Delete Notification
```
Title: "Multiple {Resource} Deleted"
Body: "{UserName} has deleted {count} {Resource} records"
```

## Testing

### Test from Command Line (PowerShell)
```powershell
Invoke-RestMethod `
-Uri "https://a2819254-58af-4d1f-a99b-72bfa4d2c0c1.pushnotifications.pusher.com/publish_api/v1/instances/a2819254-58af-4d1f-a99b-72bfa4d2c0c1/publishes" `
-Method POST `
-Headers @{
"Content-Type" = "application/json"
"Authorization" = "Bearer 35BE9D129473C9436642AFDF3CC60B309E11BFAA4ABF2DECA0840C72F4DD1D62"
} `
-Body '{"interests":["hello"],"web":{"notification":{"title":"Test","body":"Test notification from command line"}}}'
```

### Test from PHP
```php
$pushService = new PushNotificationService();
$pushService->send('Test Title', 'Test message body', ['hello']);
```

## Monitoring

### Logs
All push notification activities are logged in `storage/logs/laravel.log`:
- Successful sends
- Failed sends with error details
- Exceptions

### Log Examples
```
[2025-01-08 12:00:00] local.INFO: Push notification sent {"title":"New Application Created","body":"John Doe has created a new Application","interests":["hello"]}

[2025-01-08 12:00:00] local.ERROR: Push notification failed {"status":401,"body":"Unauthorized"}
```

## Troubleshooting

### Notifications Not Appearing
1. Check if notifications are enabled in `.env`: `PUSHER_BEAMS_ENABLED=true`
2. Verify the user is authenticated when performing CRUD operations
3. Check browser console for subscription errors
4. Ensure the user has granted notification permissions in the browser
5. Check application logs for API errors

### Browser Permission Issues
Users must grant notification permission when prompted by the browser. If denied, they need to:
1. Click the lock icon in the browser address bar
2. Reset notification permissions
3. Refresh the page

### Different Interests Not Working
Ensure users subscribe to the correct interest on the frontend:
```javascript
beamsClient.addDeviceInterest('admin-notifications');
```

## Security Notes

1. **Secret Key**: The secret key is server-side only and never exposed to the frontend
2. **Instance ID**: The instance ID is public and safe to include in frontend code
3. **Interests**: Interests act as topics - users must subscribe to receive notifications
4. **Authentication**: Notifications only send when a user is authenticated (`auth()->check()`)

## Future Enhancements

1. **User-Specific Notifications**: Target individual users instead of interests
2. **Notification History**: Store notification history in database
3. **User Preferences**: Allow users to configure which notifications they receive
4. **Rich Notifications**: Add actions, images, and sounds to notifications
5. **Mobile App Integration**: Extend to mobile apps using Pusher Beams mobile SDKs

