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

    public $PASS_STRENGTH = ['cost' => 12];


    public function setPassword($password, $reset)
    {
        $query = ['password' => password_hash($password, PASSWORD_BCRYPT, $this->PASS_STRENGTH)];
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
        $adminClass = "";

        if($this->gravatar) {
            $email = md5(strtolower($this->email));
            $src = 'https://www.gravatar.com/avatar/'.$email.'?s='.$size.'&amp;d=identicon';
        }
        if($this->isAdmin()) $adminClass = 'class="admin_avatar"';

        return '<img id="user-avatar" '.$adminClass.' src="'.$src.'" alt="'.$this->username.'" style="width:'.$size.'px;">';
    }


    public function hasPermission($permission)
    {
        return (bool) $this->permissions->{$permission};
    }
    public function isAdmin()
    {
        return $this->hasPermission('is_admin');
    }

    public function permissions()
    {
        return $this->hasOne('App\Models\UserPermission', 'user_id');
    }
}