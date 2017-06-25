<?php

namespace App\Mail\Mailer;

/**
 * Class PendingMailable
 *
 * Helps the Mailer class send a Mailable.
 *
 * @package App\Mail\Mailer
 */
class PendingMailable
{
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Grabs the "to" address and the "to" name and adds it to an array.
     *
     * @param string $address
     * @param string|NULL $name
     *
     * @return PendingMailable
     */
    public function to($address, $name = NULL)
    {
        $this->to = compact('address', 'name');

        return $this;
    }

    /**
     * Adds the "to" address and rgw "to" name to the mailable and returns it to the send function in Mailer.
     *
     * @param Mailable $mailable
     *
     * @return Mailer
     */
    public function send(Mailable $mailable)
    {
        $mailable->to($this->to['address'], $this->to['name']);

        return $this->mailer->send($mailable);
    }
}