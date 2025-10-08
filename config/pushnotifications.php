<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pusher Beams Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Pusher Beams push notifications
    |
    */

    'instance_id' => env('PUSHER_BEAMS_INSTANCE_ID', 'a2819254-58af-4d1f-a99b-72bfa4d2c0c1'),
    'secret_key' => env('PUSHER_BEAMS_SECRET_KEY', '35BE9D129473C9436642AFDF3CC60B309E11BFAA4ABF2DECA0840C72F4DD1D62'),

    /*
    |--------------------------------------------------------------------------
    | Default Interest
    |--------------------------------------------------------------------------
    |
    | The default interest that users are subscribed to
    |
    */

    'default_interest' => env('PUSHER_BEAMS_DEFAULT_INTEREST', 'hello'),

    /*
    |--------------------------------------------------------------------------
    | Enable Notifications
    |--------------------------------------------------------------------------
    |
    | Globally enable or disable push notifications
    |
    */

    'enabled' => env('PUSHER_BEAMS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Notification Icon
    |--------------------------------------------------------------------------
    |
    | Default icon for push notifications
    |
    */

    'icon' => env('PUSHER_BEAMS_ICON', '/img/logos/pin.webp'),

    /*
    |--------------------------------------------------------------------------
    | Interest Groups
    |--------------------------------------------------------------------------
    |
    | Define different interest groups for targeted notifications
    |
    */

    'interests' => [
        'all' => 'hello',
        'admins' => 'admin-notifications',
        'breeders' => 'breeder-notifications',
        'twg' => 'twg-notifications',
        'pbmap' => 'pbmap-notifications',
    ],
];

