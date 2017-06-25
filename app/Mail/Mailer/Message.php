<?php

namespace App\Mail\Mailer;

use Swift_Message;
use Swift_Attachment;

/**
 * Class Message
 *
 * Helps the Mailer class build a new message for an email.
 *
 * @package App\Mail\Mailer
 */
class Message
{
    /**
     * Swift_Message instance.
     * @var Swift_Message
     */
    protected $swiftMessage;

    public function __construct(Swift_Message $swiftMessage)
    {
        $this->swiftMessage = $swiftMessage;
    }

    /**
     * Sets the "to" address and the "to" name.
     *
     * @param string $address
     * @param string|NULL $name
     *
     * @return Message
     */
    public function to($address, $name = NULL)
    {
        $this->swiftMessage->setTo($address, $name);

        return $this;
    }

    /**
     * Sets the "from" address and the "from" name.
     *
     * @param string $address
     * @param string|NULL $name
     *
     * @return Message
     */
    public function from($address, $name = NULL)
    {
        $this->swiftMessage->setFrom($address, $name);

        return $this;
    }

    /**
     * Sets the subject.
     *
     * @param string $subject
     *
     * @return Message
     */
    public function subject($subject)
    {
        $this->swiftMessage->setSubject($subject);

        return $this;
    }

    /**
     * Sets the body.
     *
     * @param string $body
     *
     * @return Message
     */
    public function body($body)
    {
        $this->swiftMessage->setBody($body, 'text/html');

        return $this;
    }

    /**
     * Attaches a file to the message.
     *
     * @param $file
     *
     * @return Message
     */
    public function attach($file)
    {
        $this->swiftMessage->attach(Swift_Attachment::fromPath($file));

        return $this;
    }

    /**
     * Returns an instance of Swift_Message.
     *
     * @return Swift_Message
     */
    public function getSwiftMessage()
    {
        return $this->swiftMessage;
    }
}