<?php

namespace App\Mail\Customer;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Broadcast extends Mailable
{
    use Queueable, SerializesModels;

    public $content = [];

    /**
     * Email body
     * 
     * @var array $mail
     */
    protected $mail;

    /**
     * User object
     *
     * @var User $user
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param array $content
     * @return void
     */
    public function __construct(User $user, $mail)
    {
        if($config = $user->email_configuration()->first()) {
            $this->content['header'] = $config->header;
            $this->content['header_url'] = $config->header_url;
            $this->content['footer'] = $config->footer;
            $this->content['subcopy'] = $config->subcopy;
        }

        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.customer.broadcast')
            ->with([
                'body'          => $this->mail['body'],
                'content'       => $this->content,
            ])
            ->subject($this->mail['subject']);

    }
}
