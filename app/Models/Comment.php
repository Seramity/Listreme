<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Michelf\Markdown;
use Carbon\Carbon;

/**
 * Class Comment
 *
 * Comment model that extends off of the Eloquent package.
 * Deals with SQL tasks and other various functions for Comments.
 *
 * @package App\Models
 */
class Comment extends Model
{
    /**
     * Usable Comment database columns.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'list_id',
        'profile_id',
        'reply_to',
        'content'
    ];

    /**
     * Variables used for validation.
     *
     * @var int $MAX_CONTENT_CHAR
     */
    public $MAX_CONTENT_CHAR = 1000;

    /**
     * Finds user associated with the comment and then allows access through the Comment model.
     *
     * @return User
     */
    public function owner()
    {
        return User::where('id', $this->user_id)->first();
    }

    /**
     * Find replies of a comment and return them through the Comment model.
     * Order (asc, desc) can be set manually. Default: desc
     *
     * @param string $order
     * @return Comment
     */
    public function replies($order = 'desc')
    {
        return Comment::where('reply_to', $this->id)->orderBy('created_at', $order)->get();
    }

    /**
     * Takes a timestamp and converts it to a user friendly timestamp.
     * Ex: "2 hours ago"
     *
     * @return string
     */
    public function readableTime()
    {
        return Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
    }

    /**
     * Takes a timestamp and converts it to a organized timestamp.
     * Ex: "25 May 2017 08:00 PM UTC"
     *
     * @return string
     */
    public function timestamp()
    {
        return Carbon::createFromTimeStamp(strtotime($this->created_at))->format('j F Y h:i A T');
    }

    /**
     * Takes comment content and throws it through a markdown parser.
     * Also checks if the user is an admin in order to use HTML tags.
     *
     * @return string
     */
    public function markdownContent()
    {
        $markdown = new Markdown;

        if(!$this->owner()->isAdmin()) {
            $markdown->no_markup = true;
        }

        return $markdown->transform($this->content);
    }

    /**
     * Checks if the comment is a reply.
     * This prevents from large comment chains being created.
     *
     * @return bool
     */
    public function isReply()
    {
        return (bool) $this->reply_to;
    }
}