<?php

namespace App\Auth;


use App\Models\User;


class Auth
{

    public function user()
    {
        if(isset($_SESSION['user'])) return User::find($_SESSION['user']);
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }


    public function attempt($identifier, $password)
    {
        $user = User::where('username', '=', $identifier)->orWhere('email', '=', $identifier)->first();

        if(!$user) return false;

        if(password_verify($password, $user->password)) {
            if(!$user->active) return $user; // IF ACCOUNT IS NOT ACTIVATED

            $_SESSION['user'] = $user->id;
            return true;
        }

        return false;
    }

    public function signout()
    {
        unset($_SESSION['user']);
    }
}