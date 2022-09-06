<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $user)
    {
        $this->token = $token;
        $this->user = $user;
        $this->subject("Forget Password");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->url = 'http://localhost:8000/reset-password' . "?token=$this->token." . $this->user->id;
        return $this->markdown('emails.forget_password',['url' => $this->url, 'user' => $this->user]);
    }
}
