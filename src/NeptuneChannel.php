<?php

namespace Teurons\Neptune;

use Illuminate\Notifications\Notification;
use Log;
use Teurons\Neptune\Neptune;

class NeptuneChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $payload = $notification->toNeptune($notifiable);
        $eventType = $payload['event_type'];
        $data = $payload['data'];
        $remaining = $payload;
        unset($remaining['data']);
        unset($remaining['event_type']);

        $neptune = new Neptune();
        $neptune->fire($eventType, $data, $remaining);

    }


}
