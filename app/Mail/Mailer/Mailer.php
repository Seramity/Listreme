<?php

namespace App\Mail\Mailer;

use App\Mail\Mailer\Interfaces\MailableInterface;
use Swift_Mailer;
use Swift_Message;
use Slim\Views\Twig;

/**
 * Class Mailer
 *
 * Responsible for sending emails.
 * Built around the Swiftmailer package.
 *
 * @package App\Mail\Mailer
 */

class Mailer
{
    /**
     * Swift_Mailer instance.
     * @var Swift_Mailer
     */
    protected $swift;

    /**
     * Twig view instance.
     * @var Twig
     */
    protected $twig;

    /**
     * Array containing "from" address and "from" name.
     * @var array
     */
    protected $from = [];

    public function __construct(Swift_Mailer $swift, Twig $twig)
    {
        $this->swift = $swift;
        $this->twig = $twig;
    }

    /**
     * Creates a new PendingMailable instance with the "to" variables, and returns it.
     *
     * @param string $address
     * @param string|NULL $name
     *
     * @return PendingMailable
     */
    public function to($address, $name = NULL)
    {
        return (new PendingMailable($this))->to($address, $name);
    }

    /**
     * Grabs the "from" address and the "from" name, adds it to an array, and returns the Mailer.
     *
     * @param string $address
     * @param string|NULL $name
     *
     * @return $this
     */
    public function from($address, $name = NULL)
    {
        $this->from = compact('address', 'name');

        return $this;
    }

    /**
     * Grabs the view, data, and a callback.
     * Builds a message, adds the callback, adds the body from the view and data, and uses Swiftmailer to send the email.
     *
     * @param string $view
     * @param array $data
     * @param callable|NULL $callback
     *
     * @return Swift_Mailer
     */
    public function send($view, $data = [], Callable $callback = NULL)
    {
        if ($view instanceof MailableInterface) {
            return $this->sendMailable($view);
        }

        $message = $this->buildMessage();

        call_user_func($callback, $message);

        $message->body($this->parseView($view, $data));

        return $this->swift->send($message->getSwiftMessage());
    }

    /**
     * Returns a Mailable instance with an instance of the Mailer class.
     *
     * @param Mailable $mailable
     */
    protected function sendMailable(Mailable $mailable)
    {
        return $mailable->send($this);
    }

    /**
     * Returns a new Message using a provided "from" address and "from" name.
     *
     * @return Message
     */
    protected function buildMessage()
    {
        return (new Message(new Swift_Message))
            ->from($this->from['address'], $this->from['name']);
    }

    /**
     * Parses the provided view and data and turns it into HTML by using Twig's fetch function.
     *
     * @param string $view
     * @param array $data
     *
     * @return Twig
     */
    protected function parseView($view, $data)
    {
        return $this->twig->fetch($view, $data);
    }
}