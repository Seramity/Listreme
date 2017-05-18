<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ListFavorite
 *
 * ListFavorite model that extends off of the Eloquent package.
 * Favorites model for lists. Lets users collect their favorite lists.
 *
 * @package App\Models
 */
class ListFavorite extends Model
{

    /**
     * Table name for Eloquent to search the database.
     *
     * @var string $table
     */
    protected $table = "list_favorites";

    /**
     * Usable ListFavorite database columns.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'list_id'
    ];

    /**
     * Checks if user already has the list in their favorites.
     *
     * @param int $list_id
     * @param int $user_id
     *
     * @return bool
     */
    public function exists($list_id, $user_id)
    {
        $favorite = $this->where(['user_id' => $user_id, 'list_id' => $list_id])->first();
        return (bool) $favorite;
    }
}