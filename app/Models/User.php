<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * User model that extends off of the Eloquent package.
 * Deals with SQL tasks and other various functions for Users.
 *
 * @package App\Models
 */
class User extends Model
{
    /**
     * Table name for Eloquent to search the database.
     *
     * @var string $table
     */
    protected $table = 'users';

    /**
     * Usable User database columns.
     *
     * @var array $fillable
     */
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

    /**
     * Variables used for validation.
     *
     * @var int $MAX_USERNAME_CHAR
     * @var int $MAX_EMAIL_CHAR
     * @var int $MAX_NAME_CHAR
     * @var int $MAX_BIO_CHAR
     * @var int $MIN_PASSWORD_CHAR
     */
    public $MAX_USERNAME_CHAR = 24;
    public $MAX_EMAIL_CHAR = 128;
    public $MAX_NAME_CHAR = 64;
    public $MAX_BIO_CHAR = 1000;
    public $MIN_PASSWORD_CHAR = 6;

    /**
     * Password strength for password_hash().
     *
     * @var array $PASS_STRENGTH
     */
    public $PASS_STRENGTH = ['cost' => 12];

    /**
     * Sets/changes a user's password.
     *
     * @param string $password
     * @param bool   $reset
     */
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

    /**
     * Returns HTML string for user avatar.
     *
     * @param array $options
     *
     * @return string
     */
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

    /**
     * Checks if user has certain permission.
     * Uses the UserPermission model.
     *
     * @param string $permission
     *
     * @return bool
     */
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