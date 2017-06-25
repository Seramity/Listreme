<?php

namespace App\Mail;

use App\Mail\Mailer\Mailable;
use App\Models\User;

class Recover extends Mailable
{
    protected $user;
    protected $identifier;

    public function __construct(User $user, $identifier)
    {
        $this->user = $user;
        $this->identifier = $identifier;
    }

    public function build()
    {
        return $this->subject('Reset Password')
            ->view('mail/recover.twig')
            ->with([
                'user' => $this->user,
                'identifier' => $this->identifier
            ]);
    }
}