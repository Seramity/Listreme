<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'active',
        'active_hash',
        'recover_hash',
        'name',
        'bio',
        'gravatar'
    ];

    public $MAX_USERNAME_CHAR = 24;
    public $MAX_EMAIL_CHAR = 128;
    public $MAX_NAME_CHAR = 64;
    public $MAX_BIO_CHAR = 1000;

    public $MIN_PASSWORD_CHAR = 6;


    public function setPassword($password, $reset)
    {
        $query = ['password' => password_hash($password, PASSWORD_DEFAULT)];
        if ($reset) $query += array('recover_hash' => NULL); // For password resets

        $this->update($query);
    }

    public function activateAccount()
    {
        $this->update([
            'active' => true,
            'active_hash' => NULL
        ]);
    }


    public function getAvatar($options = [])
    {
        $size = isset($options['size']) ? $options['size'] : 45;

        if($this->gravatar) {
            $email = md5(strtolower($this->email));
            $src = 'https://www.gravatar.com/avatar/'.$email.'?s='.$size.'&amp;d=identicon';
        }

        return '<img id="user-avatar" src="'.$src.'" alt="'.$this->username.'" style="width:'.$size.'px;">';
    }
}