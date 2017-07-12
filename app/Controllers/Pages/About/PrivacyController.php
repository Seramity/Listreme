<?php

namespace App\Controllers\Pages\About;

use App\Controllers\Controller;

class PrivacyController extends Controller
{
    public function index($request, $response)
    {
        return $this->view->render($response, 'pages/about/privacy.twig');
    }
}