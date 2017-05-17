<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class UserPermission extends Model
{
    protected $fillable = [
        'is_admin',
        'is_subscriber'
    ];

    public static $defaults = [
        'is_admin' => false,
        'is_subscriber' => false
    ];

}