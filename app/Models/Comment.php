<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Markdown;
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
     * Mass deletes all of the user's comments.
     * Deletes any replies associated with the comments too.
     *
     * @param int $user_id
     */
    public function deleteUserComments($user_id)
    {
        $comments = $this->where('user_id', $user_id)->get();
        foreach ($comments as $comment) {

            if($comment->hasReplies()) {
                $replies = $this->where('reply_to', $comment->id)->get();

                foreach ($replies as $reply) {
                    $reply->delete();
                }
            }

            $comment->delete();

        }
    }

    /**
     * Checks if a comment has replies by counting the number of replies that exists.
     *
     * @return int
     */
    public function hasReplies()
    {
        return Comment::where('reply_to', $this->id)->count();
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
        $allow_html = false;
        if($this->owner()->isAdmin()) $allow_html = true;

        return Markdown::convert($this->content, $allow_html);
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