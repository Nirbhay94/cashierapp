<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InsufficientBalance extends Notification
{
    use Queueable;

    protected $user;
    protected $link;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param string $link
     * @param array $extras
     * @return void
     */
    public function __construct($user, $link)
    {
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->error()
            ->greeting(trans('mail.subscription.expired.greeting', [
                'name' => $this->user->name
            ]))
            ->line(trans('mail.subscription.expired.body'))
            ->action(trans('mail.subscription.expired.button'), $this->link)
            ->line(trans('mail.subscription.expired.footnote'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
