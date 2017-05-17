<?php

namespace App\Controllers\Auth;


use App\Models\User;
use App\Models\UserPermission;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;


class AuthController extends Controller
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

    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response)
    {
        $user = new User;

        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->length(NULL, $user->MAX_EMAIL_CHAR)->emailAvailable(NULL), // NULL for no check on 'current' email (This is only used for registered users)
            'username' => v::noWhitespace()->notEmpty()->length(3, $user->MAX_USERNAME_CHAR)->usernameAvailable(NULL),
            'password' => v::noWhitespace()->notEmpty()->length(6, null)
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $generator = $this->randomlib->getGenerator($this->securitylib);
        $identifier = $generator->generateString(128, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        $user = $user->create([
            'email' => $request->getParam('email'),
            'username' => $request->getParam('username'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
            'active' => false,
            'active_hash' => $this->hash->hash($identifier),
            'gravatar' => 1
        ]);

        $user->permissions()->create(UserPermission::$defaults);

        $this->mailer->send($response, 'mail/signedup.twig', ['user' => $user, 'identifier' => $identifier], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Welcome to Lists!');
        });

        $this->flash->addMessage('global_success', 'Your account has been created. An email was sent to you with a link to activate your account.');
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignOut($request, $response)
    {
        $this->auth->signout();
        return $response->withRedirect($this->router->pathFor('home'));
    }

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

        $_SESSION['user'] = $user->id; // LOGIN

        $this->flash->addMessage('global_success', 'Your account has been activated. Welcome to Lists!');
        return $response->WithRedirect($this->router->pathFor('home'));

    }

}