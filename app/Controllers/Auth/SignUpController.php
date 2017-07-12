<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use App\Models\UserPermission;
use Respect\Validation\Validator as v;
use App\Mail\SignedUp;

class SignUpController extends Controller
{
    public function getSignUp($request, $response)
    {
        $user = new User();
        return $this->view->render($response, 'auth/signup.twig', ['user' => $user]);
    }

    public function postSignUp($request, $response)
    {
        // Check if registration is closed
        if(!$this->container->get('settings')['app']['registration_enabled']) {
            $this->flash->addMessage('global_notice', 'Sorry, we are not accepting any more new accounts at this time.');
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $user = new User;

        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->length(NULL, $user->MAX_EMAIL_CHAR)->emailAvailable(NULL), // NULL for no check on 'current' email (This is only used for registered users)
            'username' => v::noWhitespace()->notEmpty()->regex('/^[A-Za-z0-9_-]+$/')->length(3, $user->MAX_USERNAME_CHAR)->usernameAvailable(NULL),
            'password' => v::noWhitespace()->notEmpty()->length(6, null)
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $generator = $this->randomlib->getGenerator($this->securitylib);
        $identifier = $generator->generateString(128, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        $authId = $user->generateAuthId();

        $user = $user->create([
            'auth_id' => $authId,
            'email' => $request->getParam('email'),
            'username' => $request->getParam('username'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
            'active' => false,
            'active_hash' => $this->hash->hash($identifier),
            'gravatar' => 1
        ]);

        $user->permissions()->create(UserPermission::$defaults);


        $this->mail->to($user->email, $user->username)->send(new SignedUp($user, $identifier, $this->container));

        $this->flash->addMessage('global_success', 'Your account has been created. An email was sent to you with a link to activate your account.');
        return $response->withRedirect($this->router->pathFor('home'));
    }
}