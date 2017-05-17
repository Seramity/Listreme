<?php

namespace App\Controllers\User;


use App\Controllers\Controller;
use App\Models\User;
use App\Models\Lists;


class ProfileController extends Controller
{
    public function index($request, $response, $args)
    {
        $user = User::where('username', $args['user'])->first();

        if(!$user) return $this->view->render($response, 'errors/404.twig')->withStatus(404);

        $lists = Lists::where('uid', $user->id)->get();

        $data = ['user' => $user, 'lists' => $lists];
        return $this->view->render($response, 'user/profile.twig', $data);
    }
}