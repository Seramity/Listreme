<?php

namespace App\Mail;

use App\Mail\Mailer\Mailable;
use App\Models\User;

class PasswordChanged extends Mailable
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Password Changed')
            ->view('mail/passwordchanged.twig')
            ->with([
                'user' => $this->user
            ]);
    }
}