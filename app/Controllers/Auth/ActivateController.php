<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;

class ActivateController extends Controller
{
    public function getActivate($request, $response)
    {
        $email = $request->getParam('email');
        $identifier = $request->getParam('id');
        $hashedIdentifier = $this->hash->hash($identifier);

        $user = User::where('email', $email)->where('active', false)->first();

        if(!$user || !$this->hash->hashCheck($user->active_hash, $hashedIdentifier)) {
            $this->flash->addMessage('global_error', 'There was a problem while attempting to activate your account');
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $user->activateAccount();

        $_SESSION['user'] = $user->auth_id; // LOGIN

        $this->flash->addMessage('global_success', "Your account has been activated. Welcome to {$this->container->get('settings')['app']['name']}!");
        return $response->WithRedirect($this->router->pathFor('home'));

    }
}