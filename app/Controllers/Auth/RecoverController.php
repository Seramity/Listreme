<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use App\Mail\Recover;
use App\Mail\PasswordChanged;


class RecoverController extends Controller
{
    public function getRecover($request, $response)
    {
        return $this->view->render($response, 'auth/recover.twig');
    }

    public function postRecover($request, $response)
    {
        $identifier = $request->getParam('identifier');
        $user = User::where('username', '=', $identifier)->orWhere('email', '=', $identifier)->first();

        if(!$user) {
            $this->flash->addMessage('global_error', 'Incorrect username or email');
            return $response->withRedirect($this->router->pathFor('auth.recover'));
        }

        $generator = $this->randomlib->getGenerator($this->securitylib);
        $identifier = $generator->generateString(128, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        $user->update([
            'recover_hash' => $this->hash->hash($identifier)
        ]);

        $this->container->mail->to($user->email, $user->username)->send(new Recover($user, $identifier));

        $this->flash->addMessage('global_success', 'An email with instructions has been sent to the email address associated with your account.');
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getReset($request, $response, $args)
    {
        $identifier = $args['identifier'];
        $hashedIdentifier = $this->hash->hash($identifier);

        $user = User::where('recover_hash', $hashedIdentifier)->first();

        // Redirect if user is not found, user has not recover hash, or the hashes don't match
        if(!$user || !$user->recover_hash || !$this->hash->hashCheck($user->recover_hash, $hashedIdentifier)) {
            return $response->withRedirect($this->router->pathFor('home'));
        }

        return $this->view->render($response, 'auth/reset.twig', ['identifier' => $args['identifier']]);
    }

    public function postReset($request, $response, $args)
    {
        $identifier = $args['identifier'];
        $hashedIdentifier = $this->hash->hash($identifier);

        $user = User::where('recover_hash', $hashedIdentifier)->first();

        // Redirect if user is not found, user has not recover hash, or the hashes don't match
        if(!$user || !$user->recover_hash || !$this->hash->hashCheck($user->recover_hash, $hashedIdentifier)) {
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $password = $request->getParam('password');
        $confirm_password = $request->getParam('confirm_password');

        $validation = $this->validator->validate($request, [
            'password' => v::noWhitespace()->notEmpty()->length($user->MIN_PASSWORD_CHAR, NULL),
            'confirm_password' => v::notEmpty()->confirmPassword($password)
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.reset', ['identifier' => $args['identifier']]));
        }

        $user->setPassword($password, true);

        $this->container->mail->to($user->email, $user->username)->send(new PasswordChanged($user));

        $this->flash->addMessage('global_success', 'Your password has been reset and you can now sign in.');
        return $response->withRedirect($this->router->pathFor('auth.signin'));
    }
}