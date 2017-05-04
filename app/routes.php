<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', 'HomeController:index')->setName('home');


// AUTH REQUIRED PAGES
$app->group('', function () {

    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

    // ACCOUNT ROUTES
    $this->get('/account/password', 'PasswordController:getChangePassword')->setName('account.password');
    $this->post('/account/password', 'PasswordController:postChangePassword');

})->add(new AuthMiddleware($container));


$app->group('', function () {

    // AUTH ROUTES
    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');

    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');

})->add(new GuestMiddleware($container));
