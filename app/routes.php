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


    // LIST ROUTES
    $this->get('/list/new', 'NewListController:getNewList')->setName('list.new');
    $this->post('/list/new', 'NewListController:postNewList');
    $this->get('/list/edit/{id}', 'EditListController:getEditList')->setName('list.edit');
    $this->post('/list/edit/{id}', 'EditListController:postEditList');
    $this->get('/list/delete/{id}', 'DeleteListController:getDeleteList')->setName('list.delete');
    $this->get('/list/favorite/{id}', 'FavoriteListController:createFavorite')->setName('list.favorite');
    $this->get('/list/unfavorite/{id}', 'FavoriteListController:deleteFavorite')->setName('list.unfavorite');


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
