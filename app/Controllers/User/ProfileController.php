<?php

namespace App\Controllers\User;

use Illuminate\Pagination\Paginator;
use App\Controllers\Controller;
use App\Models\User;
use App\Models\Lists;

class ProfileController extends Controller
{
    public function index($request, $response, $args)
    {
        $user = User::where('username', $args['user'])->first();

        if(!$user) return $this->view->render($response, 'errors/404.twig')->withStatus(404);

        $lists = Lists::where('user_id', $user->id)->orderBy('position', 'asc')->get();

        $data = ['user' => $user, 'lists' => $lists];
        return $this->view->render($response, 'user/profile.twig', $data);
    }

    public function category($request, $response, $args)
    {
        $user = User::where('username', $args['user'])->first();

        if(!$user) return $this->view->render($response, 'errors/404.twig')->withStatus(404);

        $lists = Lists::where(['user_id' => $user->id, 'category' => $args['category']])->orderBy('title', 'asc')->get();

        $data = ['user' => $user, 'lists' => $lists, 'category' => $args['category']];
        return $this->view->render($response, 'user/category.twig', $data)->withStatus(404);
    }

    public function favorites($request, $response, $args)
    {
        $user = User::where('username', $args['user'])->first();

        if(!$user) return $this->view->render($response, 'errors/404.twig')->withStatus(404);

        $favoriteLists = new Lists;
        $lists = $favoriteLists->favoriteLists($user->id, 6);

        $data = ['user' => $user, 'lists' => $lists];
        return $this->view->render($response, 'user/favorites.twig', $data);
    }
}