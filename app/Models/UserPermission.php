<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPermission
 *
 * UserPermission model that extends off of the Eloquent package.
 * Permissions in connection to the User model which can determine
 * functions a user are allowed to perform.
 * Example: Admin privileges or paid subscriptions.
 *
 * @package App\Models
 */
class UserPermission extends Model
{
    /**
     * Usable UserPermission database columns.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'is_admin',
        'is_subscriber'
    ];

    /**
     * Default values when permissions are created during user registration.
     *
     * @var array $defaults
     */
    public static $defaults = [
        'is_admin' => false,
        'is_subscriber' => false
    ];

}