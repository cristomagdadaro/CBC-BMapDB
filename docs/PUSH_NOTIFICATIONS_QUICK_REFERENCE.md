# Push Notifications - Quick Reference

## 🎯 Automatic CRUD Notifications (Already Working!)

All CRUD operations automatically send push notifications. No code changes needed in controllers!

### What's Automated:
- ✅ **CREATE**: "New {Resource} Created" by {User}
- ✅ **UPDATE**: "{Resource} Updated" by {User}
- ✅ **DELETE**: "{Resource} Deleted" by {User}
- ✅ **MULTI-DELETE**: "Multiple {Resource} Deleted" - {count} records by {User}

## 🔧 Quick Setup Checklist

1. ✅ PushNotificationService created
2. ✅ AbstractRepoService updated with notifications
3. ✅ Configuration file created (`config/pushnotifications.php`)
4. ✅ Environment variables added to `.env.example`
5. ✅ Frontend already configured in `app.blade.php`

## 📝 Add to Your .env File

```env
PUSHER_BEAMS_INSTANCE_ID=a2819254-58af-4d1f-a99b-72bfa4d2c0c1
PUSHER_BEAMS_SECRET_KEY=35BE9D129473C9436642AFDF3CC60B309E11BFAA4ABF2DECA0840C72F4DD1D62
PUSHER_BEAMS_DEFAULT_INTEREST=hello
PUSHER_BEAMS_ENABLED=true
PUSHER_BEAMS_ICON=/img/logos/pin.webp
```

## 🚀 Usage Examples

### Custom Notification in Controller

```php
use App\Services\PushNotificationService;

class YourController extends Controller
{
    protected PushNotificationService $pushNotification;

    public function __construct(PushNotificationService $pushNotification)
    {
        $this->pushNotification = $pushNotification;
    }

    public function yourMethod()
    {
        // Send custom notification
        $this->pushNotification->notifyCustom(
            'Title Here',
            'Message body here',
            ['hello'] // or use specific interests
        );
    }
}
```

### Disable Notifications for Specific Repository

```php
class YourRepository extends AbstractRepoService
{
    protected bool $enablePushNotifications = false;
}
```

### Custom Resource Name

```php
class YourRepository extends AbstractRepoService
{
    protected function getResourceName(): string
    {
        return 'Plant Breeder Profile';
    }
}
```

## 🎨 Interest Groups (Topics)

```php
// Send to all users
['hello']

// Send to admins
[config('pushnotifications.interests.admins')]

// Send to breeders
[config('pushnotifications.interests.breeders')]

// Send to TWG members
[config('pushnotifications.interests.twg')]

// Send to multiple groups
[
    config('pushnotifications.interests.admins'),
    config('pushnotifications.interests.breeders')
]
```

## 🧪 Testing

### Command Line Test (PowerShell)
```powershell
php test-push-notifications.php
```

### API Test via Controller
Add to `routes/api.php`:
```php
Route::get('/test-notification', [NotificationExampleController::class, 'sendTestNotification']);
```

Then visit: `/api/test-notification`

## 🔍 Monitoring

Check logs at: `storage/logs/laravel.log`

```
[INFO] Push notification sent {"title":"...","body":"..."}
[ERROR] Push notification failed {"status":...,"body":"..."}
```

## ⚡ Quick Tips

1. Notifications only send when user is authenticated
2. Users must grant browser notification permission
3. Users must be subscribed to the interest/topic
4. Set `PUSHER_BEAMS_ENABLED=false` to disable globally
5. Icon appears in notifications (customizable in config)

## 📚 Full Documentation

See: `docs/PUSH_NOTIFICATIONS.md` for complete details

