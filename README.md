Neptune is an hosted event driven out going communication system along with event analytics.

You can fire events to Neptune and react to them for Emails, SMS, Push and App/Dashboard Notifications.

## Step 1:

- Create a Team at Neptune
- Create an API Token
- Create a `dev` Environment and mark it as Mock

## Step 2:

Install PHP SDK using Composer

`composer require teurons/neptune-php`

## Step 3:

Configure following in your .ENV

```
NEPTUNE_ENV=dev
NEPTUNE_TOKEN=<paste_your_api_token_here>
```

That's it. You have configured Neptune.

# Usage

## Send Event

```
    $neptune = new Neptune();

    $eventType = "login_with_otp";
    $eventData = [
            'otp' => 123456
    ];
    $neptuneData = [
        'user_id' => '343-4324-23432',
        'contact_infos => [
            ['type' => 'email', 'value' => 'rajiv@example.com'],
            ['type' => 'mobile_number', 'value' => '+1XXXXXXXX']
        ]
    ];

    $neptune->fire(
        $eventType,
        $eventData,
        $neptuneData
    );
```

- $eventType - It can be any string, you can following any convention. In above example we used underscore separated lower cases alphanumeric characters.
- $eventData - Data is optional, and can be just empty array. This data is actually sent to your templates, so that the communication sent to your user can be dynamic. The keys from this array would be used to prefill templates data, if you used variables like `{{ otp }}` in your email/sms template.
- $neptuneData = You can use Neptune Data to sent user_id, contact information and more. You can check available contactTypes here: https://teurons.com/docs/neptune/usage/contact-types

## Read App Notifications

You can read app notification which you can show to user under notifications section of your app.

```php
    $neptune = new Neptune();

    $userId = 1;

    $notifications = $neptune->appNotificationsGet($userId, [
        "type" => "unread"
    ]);
```

There are three types possible: all, unread, read

Default is "unread"

## Mark Notification as Read

You can mark one particular notification as read

```php
    $neptune = new Neptune();

    $type = "notification";
    $identifier = "191df352-5eb6-4e92-a0a2-912a485eb7c7";

    $notifications = $neptune->appNotificationMarkAsRead($type,$identifier);
```

## Mark All Notification as Read for an User

```php
    $neptune = new Neptune();

    $type = "user";
    $identifier = "1";

    $notifications = $neptune->appNotificationMarkAsRead($type,$identifier);
```

## Laravel

Step 1:

```php
use Teurons\Neptune\NeptuneChannel;

class ResetPasswordNotification extends Notification
{
    public function via($notifiable)
    {
        return [NeptuneChannel::class];
    }


    public function toNeptune($notifiable)
    {
        return [
            'event_type' => 'user_requested_to_reset_password',
            'data' => [
                'token' => 123456
            ],
            'user_id' => $notifiable->id,
            "contact_infos" => [
                [
                    "type" => "email",
                    "value" => $notifiable->email
                ]
            ],
        ];
    }

}
```
