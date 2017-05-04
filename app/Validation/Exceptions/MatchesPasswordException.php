<?php

namespace App\Validation\Exceptions;


use Respect\Validation\Exceptions\ValidationException;


class MatchesPasswordException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "The password you entered does not match your current password"
        ]
    ];
}