<?php

namespace App\Mail;

use App\Mail\Mailer\Mailable;
use App\Models\User;


class SignedUp extends Mailable
{
    protected $user;
    protected $identifier;
    protected $container;

    public function __construct(User $user, $identifier, $container)
    {
        $this->user = $user;
        $this->identifier = $identifier;
        $this->container = $container;
    }

    public function build()
    {
        return $this->subject("Welcome to {$this->container->get('settings')['app']['name']}!")
            ->view('mail/signedup.twig')
            ->with([
                'user' => $this->user,
                'identifier' => $this->identifier
            ]);
    }
}