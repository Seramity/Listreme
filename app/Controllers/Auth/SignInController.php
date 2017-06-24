<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class SignInController extends Controller
{

    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

    public function postSignIn($request, $response)
    {
        $auth = $this->auth->attempt(
            $request->getParam('identifier'),
            $request->getParam('password')
        );

        // CHECK FOR 'REMEMBER ME'
        if($auth && $request->getParam('remember') == "on") {
            $generator = $this->randomlib->getGenerator($this->securitylib);
            $identifier = $generator->generateString(128, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $token = $generator->generateString(128, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

            $user = User::where('username', '=', $request->getParam('identifier'))->orWhere('email', '=', $request->getParam('identifier'))->first();

            $user->updateRemember($identifier, $this->hash->hash($token));

            setcookie($this->container->get('settings')['auth']['remember'], "{$identifier}___{$token}", Carbon::parse('+1 week')->timestamp, $request->getUri()->getBasePath(), null, false, true);
        }


        if(!$auth) {
            $this->flash->addMessage('global_error', 'Incorrect user or password');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        //Check if account is not activated
        if($auth && !$auth->active) {
            $this->flash->addMessage('global_error', 'You need to activate your account before you can log in');
            return $response->WithRedirect($this->router->pathFor('auth.signin'));
        }

        return $response->WithRedirect($this->router->pathFor('home'));
    }

}