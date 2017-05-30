<?php

namespace App\Controllers\Lists;

use App\Models\Lists;
use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class EditListController extends Controller
{
    public function getEditList($request, $response, $args)
    {
        $list = Lists::where('id', $args['id'])->first();
        $list_owner = User::where('id', $list->user_id)->first();

        if(!$list) {
            $this->flash->addMessage('global_error', 'That list does not exist');
            return $response->withRedirect($this->router->pathFor('home'));
        }
        if($list->user_id !== $this->auth->user()->id && !$this->auth->user()->isAdmin()) {
            $this->flash->addMessage('global_error', 'You do not own that list');
            return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $list->id]));
        }

        $user_lists = Lists::where('user_id', $list_owner->id)->get();

        return $this->view->render($response, 'list/edit.twig', ['list' => $list, 'user_lists' => $user_lists]);
    }

    public function postEditList($request, $response, $args)
    {
        $list = Lists::where('id', $args['id'])->first();
        $list_owner = User::where('id', $list->user_id)->first();

        if(!$list) {
            $this->flash->addMessage('global_error', 'That list does not exist');
            return $response->withRedirect($this->router->pathFor('home'));
        }
        if($list->user_id !== $this->auth->user()->id && !$this->auth->user()->isAdmin()) {
            $this->flash->addMessage('global_error', 'You do not own that list');
            return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $list->id]));
        }

        $validation = $this->validator->validate($request, [
            'title' => v::notEmpty()->length(3, $list->MAX_TITLE_CHAR),
            'category' => v::notEmpty()->noWhiteSpace()->alNum()->length(3, $list->MAX_CATEGORY_CHAR),
            'content' => v::notEmpty()->length(NULL, $list->MAX_CONTENT_CHAR),
            'position' => v::min(0)->numeric()
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('list.edit', ['id' => $args['id']]));
        }

        if($request->getParam('position') != $list->position) {
           $list->changePositions($list_owner->id, $list->id, $request->getParam('position'));
        }

        $list->update([
            'title' => $request->getParam('title'),
            'category' => $request->getParam('category'),
            'content' => $request->getParam('content'),
            'position' => $request->getParam('position')
        ]);

        $this->flash->addMessage('global_success', 'Your list has been updated');
        return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $list->id]));
    }
}