<?php

namespace App\Hash;


class Hash
{
    public function hash($identifier)
    {
        return hash('sha256', $identifier);
    }

    public function hashCheck($known, $given)
    {
        return hash_equals($known, $given);
    }
}