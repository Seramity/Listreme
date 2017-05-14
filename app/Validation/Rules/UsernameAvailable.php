<?php

namespace App\Validation\Rules;


use App\Models\User;
use Respect\Validation\Rules\AbstractRule;


class UsernameAvailable extends AbstractRule
{
    protected $current_username;

    public function __construct($current_username)
    {
        $this->current_username = $current_username;
    }

    public function validate($input)
    {
        if($this->current_username && $this->current_username === $input) {
            return true;
        } else {
            return User::where('username', $input)->count() == 0;
        }
    }
}