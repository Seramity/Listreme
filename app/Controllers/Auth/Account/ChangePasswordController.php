<?php

namespace App\Controllers\Auth\Account;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use App\Mail\PasswordChanged;


class ChangePasswordController extends Controller
{
    public function getChangePassword($request, $response)
    {
        return $this->view->render($response, 'account/password.twig');
    }

    public function postChangePassword($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'password_current' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
            'password_new' => v::noWhitespace()->notEmpty()->length($this->auth->user()->MIN_PASSWORD_CHAR, NULL),
            'confirm_password' => v::notEmpty()->confirmPassword($request->getParam('password_new'))
        ]);


        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('account.password'));
        }

        $this->auth->user()->setPassword($request->getParam('password_new'));

        $user = $this->container->auth->user();

        $this->container->mail->to($user->email, $user->username)->send(new PasswordChanged($user));

        $this->flash->addMessage('global_success', 'Your password has been changed');
        return $response->withRedirect($this->router->pathFor('account.password'));
    }
}