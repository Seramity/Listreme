<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;


class PasswordController extends Controller
{
    public function getChangePassword($request, $response)
    {
        return $this->view->render($response, 'account/password.twig');
    }

    public function postChangePassword($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'password_current' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
            'password_new' => v::noWhitespace()->notEmpty()
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('account.password'));
        }

        $this->auth->user()->setPassword($request->getParam('password_new'));

        $this->flash->addMessage('global_success', 'Your password has been changed');
        return $response->withRedirect($this->router->pathFor('account.password'));
    }
}