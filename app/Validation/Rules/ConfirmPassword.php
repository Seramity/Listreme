<?php

namespace App\Validation\Rules;


use App\Models\User;
use Respect\Validation\Rules\AbstractRule;


class ConfirmPassword extends AbstractRule
{
    protected $confirm_password;

    public function __construct($confirm_password)
    {
        $this->confirm_password = $confirm_password;
    }

    public function validate($input)
    {
        if($this->confirm_password === $input) {
            return true;
        }

        return false;
    }
}