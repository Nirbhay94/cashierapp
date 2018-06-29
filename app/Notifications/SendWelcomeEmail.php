<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendWelcomeEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user's model object
     *
     * @var $user
     */
    protected $user;

    /**
     * The plain users password, should be
     * sent if included
     *
     * @var $password
     */
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param string $password
     * @return void
     */
    public function __construct($user, $password = null)
    {
        $this->user = $user;
        $this->password = $password;
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
            ->subject(trans('mail.auth.welcome.subject'))
            ->markdown('mail.auth.welcome', ['user' => $this->user, 'password' => $this->password]);
    }
}
