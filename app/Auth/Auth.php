<?php

namespace App\Auth;


use App\Models\User;

/**
 * Class Auth
 *
 * This class is the basis for user authentication.
 * Sets user sessions and provides data from User model.
 *
 * @package App\Auth
 */
class Auth
{
    /**
     * Checks if user session exist and return the User to be accessed through Auth.
     *
     * @return User
     */
    public static function user()
    {
        if(isset($_SESSION['user'])) return User::where('auth_id', $_SESSION['user'])->first();
    }

    /**
     * Checks if user session exists.
     *
     * @return bool
     */
    public function check()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Attempts user login with a given identifier (username or email) and password.
     *
     * @param string $identifier
     * @param string $password
     *
     * @return bool
     */
    public function attempt($identifier, $password)
    {
        $user = User::where('username', '=', $identifier)->orWhere('email', '=', $identifier)->first();

        if(!$user) return false;

        if(password_verify($password, $user->password)) {
            if(!$user->active) return $user;

            $_SESSION['user'] = $user->auth_id;

            return true;
        }

        return false;
    }


    public function signout()
    {
        unset($_SESSION['user']);
    }
}