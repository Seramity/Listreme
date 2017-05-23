<?php

namespace App\Controllers\Lists;

use App\Controllers\Controller;
use App\Models\Lists;
use App\Models\User;

class ListController extends Controller
{
    public function index($request, $response, $args)
    {
        $user = User::where('username', $args['user'])->first();

        if(!$user) return $this->view->render($response, 'errors/404.twig')->withStatus(404);

        $list = Lists::where(['id' => $args['id'], 'user_id' => $user->id])->first();

        $data = ['user' => $user, 'list' => $list];
        return $this->view->render($response, 'list/list.twig', $data);
    }
}