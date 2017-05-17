<?php

namespace App\Controllers\Lists;

use App\Models\Lists;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class NewListController extends Controller
{
    public function getNewList($request, $response)
    {
        return $this->view->render($response, 'list/new.twig');
    }

    public function postNewList($request, $response)
    {
        $list = new Lists;
        $validation = $this->validator->validate($request, [
            'title' => v::notEmpty()->length(3, $list->MAX_TITLE_CHAR),
            'category' => v::notEmpty()->noWhiteSpace()->alNum()->length(3, $list->MAX_CATEGORY_CHAR),
            'size' => v::min(0)->max(2),
            'content' => v::notEmpty()->length(NULL, $list->MAX_CONTENT_CHAR)
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('list.new'));
        }

        $list = $list->create([
            'uid' => $this->auth->user()->id,
            'title' => $request->getParam('title'),
            'category' => $request->getParam('category'),
            'size' => $request->getParam('size'),
            'content' => $request->getParam('content'),
        ]);

        $this->flash->addMessage('global_success', 'Your list has been created');
        return $response->withRedirect($this->router->pathFor('userProfile', ['user' => $this->auth->user()->username]));
    }
}