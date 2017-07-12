<?php

namespace App\Controllers\Pages\About;

use App\Controllers\Controller;

class TermsController extends Controller
{
    public function index($request, $response)
    {
        return $this->view->render($response, 'pages/about/terms.twig');
    }
}