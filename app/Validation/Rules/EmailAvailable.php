<?php

namespace App\Validation\Rules;


use App\Models\User;
use Respect\Validation\Rules\AbstractRule;


class EmailAvailable extends AbstractRule
{
    protected $current_email;

    public function __construct($current_email)
    {
        $this->current_email = $current_email;
    }
    
    public function validate($input)
    {
        if($this->current_email && $this->current_email === $input) {
            return true;
        } else {
            return User::where('email', $input)->count() == 0;
        }

    }
}