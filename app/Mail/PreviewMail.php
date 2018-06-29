<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PreviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $content = [];

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        if($config = $user->email_configuration()->first()) {
            $this->content['header'] = $config->header;
            $this->content['header_url'] = $config->header_url;
            $this->content['footer'] = $config->footer;
            $this->content['subcopy'] = $config->subcopy;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.preview')
            ->with([
                'content'        => $this->content,
            ]);
    }
}
