<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserPermission;



class HomeController extends Controller
{
    public function index($request, $response)
    {
        return $this->view->render($response, 'home.twig');
    }
}