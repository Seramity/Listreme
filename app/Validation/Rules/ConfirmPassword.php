<?php

namespace App\Validation\Rules;


use App\Models\User;
use Respect\Validation\Rules\AbstractRule;


class ConfirmPassword extends AbstractRule
{
    /**
     * Input to confirm the new password provided.
     *
     * @var string $confirm_password
     */
    protected $confirm_password;

    public function __construct($confirm_password)
    {
        $this->confirm_password = $confirm_password;
    }

    /**
     * Checks if the 'confirm password' input equals the 'new password' input.
     *
     * @param string $input
     *
     * @return bool
     */
    public function validate($input)
    {
        if($this->confirm_password === $input) {
            return true;
        }

        return false;
    }
}