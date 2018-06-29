<?php

namespace App\Notifications\Customer;

use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\User;
use App\Notifications\Customer\Traits\BladeProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewInvoice extends Notification
{
    use Queueable, BladeProperties;

    /**
     * Invoice Model
     *
     * @var CustomerInvoice $invoice
     */
    protected $invoice;

    /**
     * Create a new notification instance.
     *
     * @param Customer $customer
     * @param CustomerInvoice $invoice
     * @return void
     */
    public function __construct($customer, $invoice)
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

        $this->invoice = $invoice;
        $this->user = $user;
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
        $discount = ($this->invoice->discount)? $this->invoice->discount: 0;

        $message = (new MailMessage)->markdown('mail.customer.invoice.new', [
            'content'        => $this->content,
            'url'            => $this->getInvoiceAddress(),
            'name'           => $this->customer->name,
            'items'          => $this->invoice->items,
            'note'           => $this->invoice->note,
            'tax'            => money($this->invoice->tax, $this->user),
            'total'          => money($this->invoice->total, $this->user),
            'discount'       => money($discount, $this->user),
        ]);

        $message->subject(trans('mail.customer.invoice.new.subject'));

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

    /**
     * Get Invoice address for download
     */
    private function getInvoiceAddress()
    {
        return route('invoice.download', ['token' => $this->invoice->token]);
    }
}
