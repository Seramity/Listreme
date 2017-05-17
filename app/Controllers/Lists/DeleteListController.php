<?php

namespace App\Controllers\Lists;

use App\Models\Lists;
use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class DeleteListController extends Controller
{
    public function getDeleteList($request, $response, $args)
    {
        $list = Lists::where('id', $args['id'])->first();
        $list_owner = User::where('id', $list->uid)->first();

        if(!$list) {
            $this->flash->addMessage('global_error', 'That list does not exist');
            return $response->withRedirect($this->router->pathFor('home'));
        }
        if($list->uid !== $this->auth->user()->id && !$this->auth->user()->isAdmin()) {
            $this->flash->addMessage('global_error', 'You do not own that list');
            return $response->withRedirect($this->router->pathFor('userProfile', ['user' => $list_owner->username]));
        }

        $list->delete();

        $this->flash->addMessage('global_success', 'List successfully deleted');
        return $response->withRedirect($this->router->pathFor('userProfile', ['user' => $list_owner->username]));
    }

}