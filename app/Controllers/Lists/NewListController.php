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
            'content' => v::notEmpty()->length(NULL, $list->MAX_CONTENT_CHAR)
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('list.new'));
        }

        $list = $list->create([
            'user_id' => $this->auth->user()->id,
            'title' => $request->getParam('title'),
            'category' => $request->getParam('category'),
            'content' => $request->getParam('content')
        ]);

        $list->createPosition();


        $this->flash->addMessage('global_success', 'Your list has been created');
        return $response->withRedirect($this->router->pathFor('list', ['user' => $this->auth->user()->username, 'id' => $list->id]));
    }
}