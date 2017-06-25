<?php

namespace App\Mail\Mailer\Interfaces;

use App\Mail\Mailer\Mailer;

interface MailableInterface
{
    public function send(Mailer $mailer);
}