<?php
/**
 * Created by PhpStorm.
 * User: HolluwaTosin
 * Date: 6/9/2018
 * Time: 12:47 PM
 */

namespace App\Channels;


use App\Channels\Traits\NexmoHelper;
use Illuminate\Support\Facades\Notification;

class SmsChannel
{
    use NexmoHelper;

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);

        if (!$to = $notifiable->routeNotificationFor('sms') ||
            !$message instanceof SmsChannel) {
            return;
        }

        $action = $message->client . 'SendMessage';

        if(property_exists($this, $action)){
            $this->$action([
                'type'      => $message->type,
                'text'      => trim($message->content),
                'from'      => $message->from,
                'to'        => $to,
            ]);
        }
    }
}