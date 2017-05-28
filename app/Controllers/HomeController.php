<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserPermission;


class HomeController extends Controller
{
    public function index($request, $response)
    {
        if(!$this->auth->user()) {
            return $this->view->render($response, 'welcome.twig');
        } else {
            return $this->view->render($response, 'home.twig');
        }
    }
}