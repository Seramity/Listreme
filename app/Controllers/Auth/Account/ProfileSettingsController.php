<?php

namespace App\Controllers\Auth\Account;

use App\Controllers\Controller;
use Respect\Validation\Validator as v;


class ProfileSettingsController extends Controller
{
    public function getProfileSettings($request, $response)
    {
        return $this->view->render($response, 'account/profile.twig');
    }

    public function postProfileSettings($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'username' => v::noWhitespace()->notEmpty()->regex('/^[A-Za-z0-9_-]+$/')->length(3, $this->auth->user()->MAX_USERNAME_CHAR)->usernameAvailable($this->auth->user()->username),
            'email' => v::noWhitespace()->notEmpty()->email()->length(NULL, $this->auth->user()->MAX_EMAIL_CHAR)->emailAvailable($this->auth->user()->email),
            'name' => v::optional(v::alpha())->length(NULL, $this->auth->user()->MAX_NAME_CHAR),
            'bio' => v::length(NULL, $this->auth->user()->MAX_BIO_CHAR),
        ]);


        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('account.profile'));
        }


        $this->auth->user()->update([
            'username' => $request->getParam('username'),
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'bio' => $request->getParam('bio')
        ]);

        $this->flash->addMessage('global_success', 'Your profile settings have been updated');
        return $response->withRedirect($this->router->pathFor('account.profile'));
    }
}