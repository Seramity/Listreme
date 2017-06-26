<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Auth\Auth;
use App\Helpers\Markdown;
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
        'position',
        'edited'
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
     * Prevents Eloquent setting 'updated_at' during List creation.
     * This will help signify whether the list was actually edited.
     *
     * @param mixed $value
     */
    public function setUpdatedAt($value)
    {
        if($this->updating()) {
            $this->updated_at = Carbon::now();
        }
    }


    /**
     * When creating a new list, this helps find any missing positions and fills the new list into it.
     * If not, place the list after all the existing one.
     */
    public function createPosition()
    {
        $auth = new Auth;

        $listsPositions = $this->where('user_id', $auth->user()->id)->get()->pluck('position')->toArray();
        $positionNums = range(0, max($listsPositions));
        $missingNums = array_diff($positionNums, $listsPositions);

        if($missingNums) {
            $this->update(['position' => $missingNums[0]]); // Grabs the first missing number
        } else {
            $this->update(['position' => (max($listsPositions) + 1)]); // Inserts a number higher than the highest existing position
        }
    }

    /**
     * Helps arrange the positions of other lists when a user changes the position of a list.
     *
     * @param $user_id
     * @param $list_id
     * @param $position
     */
    public function changePositions($user_id, $list_id, $position)
    {
        $changingList = $this->where('id', $list_id)->first();

        if ($position < $changingList->position) {    // IF POSITION IS DECREASING

            // Find lists with positions, greater than or equal to the new position and less than the old position,
            // and increase their position.
            $this->where('position', '>=', $position)
                    ->where('position', '<', $changingList->position)
                    ->where('user_id', $user_id)
                    ->whereNotIn('id', [$list_id])
                    ->increment('position');

        } elseif ($position > $changingList->position) {    // IF POSITION IS INCREASING

            // Find lists with positions, lower than or equal to the new position and greater than the old position,
            // and decrease their position.
            $this->where('position', '<=', $position)
                    ->where('position', '>', $changingList->position)
                    ->whereNotIn('position', [0])
                    ->where('user_id', $user_id)
                    ->whereNotIn('id', [$list_id])
                    ->decrement('position');

        } else {

            if ($position == 0) {    // IF POSITION IS SET TO 0

                // increase the positions equal to or above 0
                $this->where('position', '>=', $position)
                        ->where('user_id', $user_id)
                        ->whereNotIn('id', [$list_id])
                        ->increment('position');
            }

        }

    }

    /**
     * When deleting a list, it finds all of the user's lists whose positions are higher than the one that
     * is being deleted, and decreases their positions to fill in the lost positions.
     *
     * @param List $list
     */
    public function deletePosition($list)
    {
        $auth = new Auth;
        $this->where('position', ">", $list->position)
            ->where('user_id', $auth->user()->id)
            ->whereNotIn('id', [$list->id])
            ->decrement('position');
    }

    /**
     * Takes list content and throws it through a markdown parser.
     * Also checks if the user is an admin in order to use HTML tags.
     *
     * @return string
     */
    public function markdownContent()
    {
        $allow_html = false;
        if($this->owner()->isAdmin()) $allow_html = true;

        return Markdown::convert($this->content, $allow_html);
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
     * Finds the list owner and then allows access through the Lists model.
     *
     * @return User
     */
    public function owner()
    {
        return User::where('id', $this->user_id)->first();
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

    /**
     * Checks whether the user has a list in their favorites.
     *
     * @param $user_id
     * @return bool
     */
    public function favorited($user_id)
    {
        $favorite = ListFavorite::where(['list_id' => $this->id, 'user_id' => $user_id])->first();
        return (bool) $favorite;
    }

    /**
     * Finds lists that a user has in their favorites.
     *
     * @param $user_id
     * @return Lists
     */
    public function favoriteLists($user_id, $paginate)
    {
        $favorites = ListFavorite::where('user_id', $user_id)->get();
        $favoriteIds = array();
        foreach($favorites as $favorite) {
            $favoriteIds[] = $favorite->list_id;
        }

        $lists = $this->whereIn('id', $favoriteIds)->orderBy('title', 'asc')->simplePaginate($paginate);

        return $lists;
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

        $this->deletePosition($this);
        $this->delete();
    }

    /**
     * Takes a timestamp and converts it to a user friendly timestamp.
     * Ex: "2 hours ago"
     *
     * @return string
     */
    public function readableTime($field)
    {
        return Carbon::createFromTimeStamp(strtotime($this->{$field}))->diffForHumans();
    }

    /**
     * Takes a timestamp and converts it to a organized timestamp.
     * Ex: "25 May 2017 08:00 PM UTC"
     *
     * @return string
     */
    public function timestamp($field)
    {
        return Carbon::createFromTimeStamp(strtotime($this->{$field}))->format('j F Y h:i A T');
    }

    /**
     * Count the number of lists a user owns and returns the number.
     *
     * @param int $user_id
     * @return int
     */
    public function countUserLists($user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }
}