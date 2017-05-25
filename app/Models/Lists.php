<?php

namespace App\Models;

use App\Models\ListFavorite;
use Illuminate\Database\Eloquent\Model;
use Michelf\Markdown;
use Carbon\Carbon;

/**
 * Class Lists
 *
 * List model that extends off of the Eloquent package.
 * Deals with SQL tasks and other various functions for Lists.
 *
 * @package App\Models
 */
class Lists extends Model
{
    /**
     * Usable Lists database columns.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'size'
    ];

    /**
     * Variables used for validation.
     *
     * @var int $MAX_TITLE_CHAR
     * @var int $MAX_CONTENT_CHAR
     * @var int $MAX_CATEGORY_CHAR
     */
    public $MAX_TITLE_CHAR = 48;
    public $MAX_CONTENT_CHAR = 10000;
    public $MAX_CATEGORY_CHAR = 24;

    /**
     * Default display size for Lists in HTML.
     *
     * @var int $DEFAULT_SIZE
     */
    public $DEFAULT_SIZE = 1; // 0: SMALL, 1: MEDIUM, 2: LARGE


    /**
     * Takes list content and throws it through a markdown parser.
     *
     * @return string
     */
    public function markdownContent()
    {
        return Markdown::defaultTransform($this->content);
    }

    /**
     * Formats list title for URL use.
     *
     * @return string
     */
    public function urlTitle()
    {
        return urlencode($this->title);
    }

    /**
     * Finds ListFavorite with List ID and then allows access through the Lists model.
     *
     * @return ListFavorite
     */
    public function favorite()
    {
        return ListFavorite::where('list_id', $this->id)->first();
    }

    public function favorited($user_id)
    {
        $favorite = ListFavorite::where(['list_id' => $this->id, 'user_id' => $user_id])->first();
        return (bool) $favorite;
    }

    /**
     * Finds comments associated with a list and then allows access through the Lists model.
     *
     * @return Comment
     */
    public function comments($order = NULL)
    {
        if(!$order) $order = 'asc';
        return Comment::where('list_id', $this->id)->orderBy('created_at', $order)->get();
    }

    /**
     * Checks if the list has comments.
     *
     * @return bool
     */
    public function hasComments()
    {
        return (bool) Comment::where('list_id', $this->id)->get()->count();
    }

    /**
     * Deletes all list favorites and then deletes list.
     */
    public function deleteList()
    {
        $favorites = ListFavorite::where('list_id', $this->id)->get();
        foreach ($favorites as $favorite) {
            $favorite->delete();
        }

        $this->delete();
    }


    /**
     * Takes timestamp and converts it to a user friendly timestamp.
     * Ex: "Wednesday 25 May 2017"
     *
     * @return string
     */
    public function timeStamp()
    {
        return Carbon::createFromTimeStamp(strtotime($this->created_at))->formatLocalized('%A %d %B %Y');
    }
}