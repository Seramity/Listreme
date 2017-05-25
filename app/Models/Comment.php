<?php

namespace App\Models;

use App\Models\User;
use App\Models\Lists;
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
    public function user()
    {
        return User::where('id', $this->user_id)->first();
    }


    public function replies()
    {
        return Comment::where('reply_to', $this->id)->get();
    }

    /**
     * Takes timestamp and converts it to a user friendly timestamp.
     * Ex: "2 hours ago"
     *
     * @return string
     */
    public function readableTime()
    {
        return Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
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

    /**
     * Takes comment content and throws it through a markdown parser.
     *
     * @return string
     */
    public function markdownContent()
    {
        return Markdown::defaultTransform($this->content);
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