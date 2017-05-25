<?php

namespace App\Controllers\Lists;

use App\Models\Lists;
use App\Models\User;
use App\Models\ListFavorite;
use App\Controllers\Controller;


class FavoriteListController extends Controller
{
    public function createFavorite($request, $response, $args)
    {
        $list = Lists::where('id', $args['id'])->first();

        if(!$list) {
            $this->flash->addMessage('global_error', 'That list does not exist');
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $list_owner = User::where('id', $list->user_id)->first();
        $list_favorite = new ListFavorite();


        if($list_favorite->exists($args['id'], $this->auth->user()->id)) {
            $this->flash->addMessage('global_notice', 'That list is already in your favorites');
            return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $list->id]));
        }

        $list_favorite->create([
            'user_id' => $this->auth->user()->id,
            'list_id' => $list->id
        ]);

        $this->flash->addMessage('global_success', 'List added to favorites');
        return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $list->id]));;
    }

    public function deleteFavorite($request, $response, $args)
    {
        $list = Lists::where('id', $args['id'])->first();

        if(!$list) {
            $this->flash->addMessage('global_error', 'That list does not exist');
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $list_owner = User::where('id', $list->user_id)->first();
        $list_favorite = ListFavorite::where(['list_id' => $args['id'], 'user_id' => $this->auth->user()->id])->first();

        if(!$list_favorite) {
            $this->flash->addMessage('global_notice', 'That list is not in your favorites');
            return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $list->id]));
        }

        $list_favorite->delete();

        $this->flash->addMessage('global_success', 'List was removed from your favorites');
        return $response->withRedirect($this->router->pathFor('list', ['user' => $list_owner->username, 'id' => $list->id]));
    }
}