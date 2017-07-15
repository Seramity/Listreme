<?php

namespace App\Controllers\Auth\Account;

use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class DeleteAccountController extends Controller
{
    public function getDeleteAccount($request, $response)
    {
        return $this->view->render($response, 'account/delete.twig');
    }

    public function postDeleteAccount($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'password' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password)
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('account.delete'));
        }


        if($this->auth->user()->deleteAccount()) {
            $this->auth->signout();

            $this->flash->addMessage('global_success', 'Your account has been deleted. We hope to see you return soon.');
            return $response->withRedirect($this->router->pathFor('home'));
        }
    }
}