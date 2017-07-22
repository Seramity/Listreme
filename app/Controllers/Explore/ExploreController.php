<?php

namespace App\Controllers\Explore;

use App\Controllers\Controller;
use App\Models\Lists;
use App\Models\User;
use Carbon\Carbon;

class ExploreController extends Controller
{
    public function index($request, $response)
    {
        $new_lists = Lists::where('created_at', '>=', Carbon::now()->subDay())
            ->where('created_at', '<=', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $new_users = User::where('created_at', '>=', Carbon::now()->subDay())
            ->where('created_at', '<=', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $data = ['new_lists' => $new_lists, 'new_users' => $new_users];

        return $this->view->render($response, 'explore/explore.twig', $data);
    }

    public function random($request, $response)
    {
        $random_lists = Lists::inRandomOrder()
            ->take(5)
            ->get();

        $random_users = User::inRandomOrder()
            ->take(5)
            ->get();

        $data = ['random_lists' => $random_lists, 'random_users' => $random_users];

        return $this->view->render($response, 'explore/random.twig', $data);
    }

    public function popular($request, $response, $args)
    {

        if(!isset($args['time'])) {
            $time = 'week';
        } else {
            $time = $args['time'];
        }

        $lists = new Lists;
        $lists = $lists->popularLists($time, 12);

        $data = ['lists' => $lists, 'time' => $time];

        return $this->view->render($response, 'explore/popular.twig', $data);
    }
}