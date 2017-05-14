<?php

namespace App\Validation\Exceptions;


use Respect\Validation\Exceptions\ValidationException;


class ConfirmPasswordException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "Both passwords fields must match each other"
        ]
    ];
}