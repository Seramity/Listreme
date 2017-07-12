<?php

namespace App\Controllers\Pages\About;

use App\Controllers\Controller;

class GuidelinesController extends Controller
{
    public function index($request, $response)
    {
        return $this->view->render($response, 'pages/about/guidelines.twig');
    }
}