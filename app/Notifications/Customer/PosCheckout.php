<?php

namespace App\Notifications\Customer;

use App\Models\Customer;
use App\Models\PosTransaction;
use App\Notifications\Customer\Traits\BladeProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PosCheckout extends Notification
{
    use Queueable, BladeProperties;

    /**
     * Pos Transactions instance
     *
     * @var PosTransaction $transaction
     */
    protected $transaction;

    /**
     * Create a new notification instance.
     *
     * @param Customer $customer
     * @param PosTransaction $transaction
     * @return void
     */
    public function __construct($customer, $transaction)
    {
        $user = $customer->user;

        if($config = $user->email_configuration()->first()) {
            $this->content['header'] = $config->header;
            $this->content['header_url'] = $config->header_url;
            $this->content['footer'] = $config->footer;
            $this->content['subcopy'] = $config->subcopy;

            $this->from_address = $config->from_address;
            $this->from_name = $config->from_name;

            $this->reply_to_address = $config->reply_to_address;
            $this->reply_to_name = $config->reply_to_name;
        }

        $this->user = $user;
        $this->transaction = $transaction;
        $this->customer = $customer;
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
        $discount = ($this->transaction->discount)? $this->transaction->discount: 0;

        $message = (new MailMessage)->markdown('mail.customer.pos.checkout', [
            'content'        => $this->content,
            'items'          => $this->transaction->items,
            'name'           => $this->customer->name,
            'details'        => $this->transaction->details,
            'note'           => $this->transaction->note,
            'tax'            => money($this->transaction->tax, $this->user),
            'total'          => money($this->transaction->total, $this->user),
            'discount'       => money($discount, $this->user),
        ]);

        $message->subject(trans('mail.customer.pos.checkout.subject'));

        if($this->from_address != null){
            $message->from($this->from_address, $this->from_name);
        }

        if($this->reply_to_address != null){
            $message->from($this->reply_to_address, $this->reply_to_name);
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
