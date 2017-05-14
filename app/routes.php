<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

// GENERAL PAGES
$app->get('/', 'HomeController:index')->setName('home');

$app->get('/{user}','ProfileController:index')->setName('userProfile');



// AUTH REQUIRED PAGES
$app->group('', function () {

    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

    // ACCOUNT ROUTES
    $this->get('/account/password', 'PasswordController:getChangePassword')->setName('account.password');
    $this->post('/account/password', 'PasswordController:postChangePassword');
    $this->get('/account/profile', 'ProfileSettingsController:getProfileSettings')->setName('account.profile');
    $this->post('/account/profile', 'ProfileSettingsController:postProfileSettings');

})->add(new AuthMiddleware($container));



// GUEST REQUIRED PAGES
$app->group('', function () {

    // AUTH ROUTES
    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');

    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');

    $this->get('/auth/recover', 'RecoverController:getRecover')->setName('auth.recover');
    $this->post('/auth/recover', 'RecoverController:postRecover');
    $this->get('/auth/reset/{identifier}', 'RecoverController:getReset')->setName('auth.reset');
    $this->post('/auth/reset/{identifier}', 'RecoverController:postReset');


    $this->get('/auth/activate', 'AuthController:getActivate')->setName('auth.activate');

})->add(new GuestMiddleware($container));
