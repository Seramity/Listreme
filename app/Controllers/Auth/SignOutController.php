<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;

class SignOutController extends Controller
{
    public function getSignOut($request, $response)
    {
        // REMOVE 'REMEMBER ME' COOKIE IF EXISTS
        if(isset($_COOKIE[$this->container->get('settings')['auth']['remember']])) {
            // Delete cookie
            setcookie($this->container->get('settings')['auth']['remember'], "", time() - 3600, $request->getUri()->getBasePath(), null, false, true);
            $this->auth->user()->removeRemember();
        }

        $this->auth->signout();
        $this->flash->addMessage('global_success', 'You have been logged out');
        return $response->withRedirect($this->router->pathFor('home'));
    }
}