<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Dflydev\FigCookies\Cookie;
use Dflydev\FigCookies\SetCookie;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;

use Slim\Http\Cookies as SlimCookie;

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

//            setcookie("user_r", "{$identifier}___{$token}", Carbon::parse('+1 week')->timestamp);
//            $cookie = SetCookie::create('user_r')
//                ->withValue("{$identifier}___{$token}")
//                ->withExpires(Carbon::parse('+1 week')->timestamp)
//                ->withHttpOnly(true);
//
//            var_dump(FigResponseCookies::get($response, 'user_r'));
//            die();

//            $response = FigResponseCookies::set(
//                $response,
//                SetCookie::create('user_r')
//                    ->withValue("{$identifier}___{$token}")
//                    ->withExpires(Carbon::parse('+1 week')->timestamp)
//                    ->withHttpOnly(true)
//                    ->withPath($request->getUri()->getBaseUrl())
//            );

//            $request = FigRequestCookies::set($request, Cookie::create('user_r', "{$identifier}___{$token}"));
//
//            var_dump(FigRequestCookies::get($request, 'user_r'));
//            die();

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