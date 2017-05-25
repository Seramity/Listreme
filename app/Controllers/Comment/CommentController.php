<?php

namespace App\Controllers\Comment;

use App\Controllers\Controller;
use App\Models\Lists;
use App\Models\User;
use App\Models\Comment;
use Respect\Validation\Validator as v;

class CommentController extends Controller
{
    public function createListComment($request, $response, $args)
    {
        $list = Lists::where('id', $args['id'])->first();
        $list_owner = User::where('id', $list->user_id)->first();
        $new_comment = new Comment;

        if(!$list) {
            $this->flash->addMessage('global_error', 'That list does not exist');
            return $response->withRedirect($this->router->pathFor('home'));
        }

        // CHECK IF USER IF REPLYING
        if($request->getParam('reply_to')) {
            $comment_replyingTo = Comment::where('id', $request->getParam('reply_to'))->first();

            // CHECK IF THE COMMENT IS A REPLY
            if($comment_replyingTo->isReply() ) {
                $this->flash->addMessage('global_error', 'You cannot reply to that comment');
                return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $args['id']]));
            }
        }

        $validation = $this->validator->validate($request, [
            'content' => v::notEmpty()->length(NULL, $new_comment->MAX_CONTENT_CHAR),
            'reply_to' =>v::optional(v::numeric())
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $args['id']]));
        }


        $list_id = $list->id;
        $reply_to = $request->getParam('reply_to');
        if(!$request->getParam('reply_to')) {
            $reply_to = NULL; // MAKE REPLY_TO NULL IF USER IS COMMENTING
        }

        $new_comment = $new_comment->create([
            'user_id' => $this->auth->user()->id,
            'list_id' => $list_id,
            'content' => $request->getParam('content'),
            'reply_to' => $reply_to
        ]);

        $this->flash->addMessage('global_success', 'Your comment has been posted');
        return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $args['id']]));

    }


    public function deleteComment($request, $response, $args)
    {
        $comment = Comment::where('id', $args['id'])->first();

        if($comment->list_id) {
            $list = Lists::where('id', $comment->list_id)->first();
            $list_owner = User::where('id', $list->user_id)->first();
        }
        $list_id = $comment->list_id;
        $profile_id = $comment->profile_id;

        if(!$comment) {
            $this->flash->addMessage('global_error', 'That comment does not exist');
            return $response->withRedirect($this->router->pathFor('home'));
        }

        if($comment->user_id !== $this->auth->user()->id && !$this->auth->user()->isAdmin()) {
            $this->flash->addMessage('global_error', 'You do not own that comment');
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $comment->delete();

        if($list_id) {
            $this->flash->addMessage('global_success', 'Comment successfully deleted');
            return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $list_id]));
        } else {
            $user = User::where('id', $profile_id)->first();
            $this->flash->addMessage('global_success', 'Comment successfully deleted');
            return $response->withRedirect($this->router->pathFor('userProfile', ['user' => $user->username]));
        }
    }
}