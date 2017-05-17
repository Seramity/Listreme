<?php

namespace App\Validation\Rules;


use App\Models\User;
use Respect\Validation\Rules\AbstractRule;


class UsernameAvailable extends AbstractRule
{
    /**
     * Username used by user (Their current username).
     *
     * @var string $current_username
     */
    protected $current_username;

    public function __construct($current_username)
    {
        $this->current_username = $current_username;
    }

    /**
     * Checks if provided username is taken by another user.
     * Also checks if the provided username does not equal their current username.
     *
     * @param string $input
     *
     * @return bool
     */
    public function validate($input)
    {
        if($this->current_username && $this->current_username === $input) {
            return true;
        } else {
            return User::where('username', $input)->count() == 0;
        }
    }
}