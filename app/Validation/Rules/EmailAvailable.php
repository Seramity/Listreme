<?php

namespace App\Validation\Rules;


use App\Models\User;
use Respect\Validation\Rules\AbstractRule;


class EmailAvailable extends AbstractRule
{
    /**
     * Email used by user (Their current email).
     *
     * @var string $current_email
     */
    protected $current_email;

    public function __construct($current_email)
    {
        $this->current_email = $current_email;
    }

    /**
     * Checks if provided email is taken by another user.
     * Also checks if the provided email does not equal their current email.
     *
     * @param string $input
     *
     * @return bool
     */
    public function validate($input)
    {
        if($this->current_email && $this->current_email === $input) {
            return true;
        } else {
            return User::where('email', $input)->count() == 0;
        }

    }
}