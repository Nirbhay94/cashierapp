<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LowProductQuantity extends Notification
{
    use Queueable;

    /**
     * @var User
     */
    public $user;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var string
     */
    public $message;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param Product $product
     * @param string $message
     * @return void
     */
    public function __construct($user, $product, $message = '')
    {
        $this->user = $user;
        $this->product = $product;
        $this->message = $message;
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
        $message = (new MailMessage)
            ->subject(__('Low Product Quantity Alert!'))
            ->greeting(__('Hi :name', ['name'  => $this->user->name]))
            ->line(__('We encountered an error due to a low product quantity. More details below...'))
            ->line(__('Product Name: :name', ['name' => $this->product->name]))
            ->action(__('View Products'), route('products.all'));

        if($this->message){
            $message->line($this->message);
        }

        return $message;
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
