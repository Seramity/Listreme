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

        $lists = Lists::where('user_id', $user->id)->orderBy('size', 'desc')->get();

        $data = ['user' => $user, 'lists' => $lists];
        return $this->view->render($response, 'user/profile.twig', $data);
    }

    public function category($request, $response, $args)
    {
        $user = User::where('username', $args['user'])->first();

        if(!$user) return $this->view->render($response, 'errors/404.twig')->withStatus(404);

        $lists = Lists::where(['user_id' => $user->id, 'category' => $args['category']])->orderBy('size', 'desc')->get();

        $data = ['user' => $user, 'lists' => $lists, 'category' => $args['category']];
        return $this->view->render($response, 'user/category.twig', $data);
    }
}