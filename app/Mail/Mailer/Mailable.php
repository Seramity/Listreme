<?php

namespace App\Mail\Mailer;

use App\Mail\Mailer\{
    Mailer,
    Interfaces\MailableInterface
};

/**
 * Class Mailable
 *
 * Mailable class that all Mail classes will extend off of.
 * Helps add all the message data from Mailer to a Mailable class.
 *
 * @package App\Mail\Mailer
 */
abstract class Mailable implements MailableInterface
{
    /**
     * "To" address and "to" name in an array.
     *
     * @var array
     */
    protected $to = [];

    /**
     * "From" address and "from" name in an array.
     *
     * @var array
     */
    protected $from = [];

    /**
     * Subject of the message.
     *
     * @var string
     */
    protected $subject;

    /**
     * View of the message.
     *
     * @var string
     */
    protected $view;

    /**
     * Any data used in the view.
     *
     * @var array
     */
    protected $data = [];

    /**
     * File attachments in the message.
     *
     * @var array
     */
    protected $attachments = [];

    /**
     * Builds a new message and sends it to the Mailer Class.
     * Adds the "to" variables to the message, checks for "from" variables and adds them if they exist,
     * and attaches files to the message.
     *
     * @param Mailer $mailer
     */
    public function send(Mailer $mailer)
    {
        $this->build();

        $mailer->send($this->view, $this->data, function ($message) {
            $message->to($this->to['address'], $this->to['name'])
                ->subject($this->subject);

            if ($this->from) {
                $message->from($this->from['address'], $this->from['name']);
            }

            $this->buildAttachments($message);
        });
    }

    /**
     * Adds the "to" address and the "to" name to the mailable.
     *
     * @param string $address
     * @param string|NULL $name
     *
     * @return Mailable
     */
    public function to($address, $name = NULL)
    {
        $this->to = compact('address', 'name');

        return $this;
    }

    /**
     * Adds the "from" address and the "from" name to the mailable.
     *
     * @param string $address
     * @param string|NULL $name
     *
     * @return Mailable
     */
    public function from($address, $name = NULL)
    {
        $this->from = compact('address', 'name');

        return $this;
    }

    /**
     * Adds the subject to the mailable.
     *
     * @param string $subject
     *
     * @return Mailable
     */
    public function subject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Adds the view to the mailable.
     *
     * @param $view
     *
     * @return Mailable
     */
    public function view($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Adds the data to the mailable.
     *
     * @param array $data
     *
     * @return Mailable
     */
    public function with($data = [])
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Adds the files to the mailable.
     *
     * @param $file
     *
     * @return Mailable
     */
    public function attach($file)
    {
        $this->attachments[] = $file;

        return $this;
    }

    /**
     * Helps attach one or multiple files to the mailable.
     *
     * @param Message $message
     */
    protected function buildAttachments(Message $message)
    {
        foreach ($this->attachments as $file) {
            $message->attach($file);
        }
    }
}