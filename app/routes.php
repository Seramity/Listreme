<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

// GENERAL PAGES
$app->get('/', 'HomeController:index')->setName('home');


// PROFILE ROUTES
$app->get('/{user}','ProfileController:index')->setName('userProfile');
$app->get('/{user}/category/{category}','ProfileController:category')->setName('userProfile.category');
$app->get('/{user}/favorites[/{page}]', 'ProfileController:favorites')->setName('userProfile.favorites'); // [{ARG}] = optional

$app->get('/{user}/list/{id}', 'ListController:index')->setName('list');


// ABOUT PAGES
$app->get('/a/about', 'AboutController:index')->setName('page.about');
$app->get('/a/terms', 'TermsController:index')->setName('page.terms');
$app->get('/a/privacy', 'PrivacyController:index')->setName('page.privacy');
$app->get('/a/guidelines', 'GuidelinesController:index')->setName('page.guidelines');



// AUTH REQUIRED PAGES
$app->group('', function () use ($app) {

    $app->get('/auth/signout', 'SignOutController:getSignOut')->setName('auth.signout');

    // ACCOUNT ROUTES
    $app->get('/account/profile', 'ProfileSettingsController:getProfileSettings')->setName('account.profile');
    $app->post('/account/profile', 'ProfileSettingsController:postProfileSettings');
    $app->get('/account/picture', 'PictureSettingsController:getPictureSettings')->setName('account.picture');
    $app->post('/account/picture', 'PictureSettingsController:postPictureSettings');
    $app->post('/account/picture/delete', 'PictureSettingsController:deletePicture')->setName('account.picture.delete');
    $app->post('/account/picture/change', 'PictureSettingsController:changePicture')->setName('account.picture.change');
    $app->get('/account/password', 'ChangePasswordController:getChangePassword')->setName('account.password');
    $app->post('/account/password', 'ChangePasswordController:postChangePassword');
    $app->get('/account/delete', 'DeleteAccountController:getDeleteAccount')->setName('account.delete');
    $app->post('/account/delete', 'DeleteAccountController:postDeleteAccount');

    // LIST ROUTES
    $app->get('/list/new', 'NewListController:getNewList')->setName('list.new');
    $app->post('/list/new', 'NewListController:postNewList');
    $app->get('/list/edit/{id}', 'EditListController:getEditList')->setName('list.edit');
    $app->post('/list/edit/{id}', 'EditListController:postEditList');
    $app->get('/list/delete/{id}', 'DeleteListController:getDeleteList')->setName('list.delete');
    $app->get('/list/favorite/{id}', 'FavoriteListController:createFavorite')->setName('list.favorite');
    $app->get('/list/unfavorite/{id}', 'FavoriteListController:deleteFavorite')->setName('list.unfavorite');

    // COMMENT ROUTES
    $app->post('/comment/list/{id}', 'CommentController:createListComment')->setName('comment.list');
    //$app->post('/comment/profile/{id}', 'CommentController:createProfileComment')->setName('comment.profile');
    //$app->post('/comment/reply/{id}', 'CommentController:createReplyComment')->setName('comment.reply');
    $app->get('/comment/delete/{id}', 'CommentController:deleteComment')->setName('comment.delete');


})->add(new AuthMiddleware($container));



// GUEST REQUIRED PAGES
$app->group('', function () use ($app) {

    // AUTH ROUTES
    $app->get('/auth/signup', 'SignUpController:getSignUp')->setName('auth.signup');
    $app->post('/auth/signup', 'SignUpController:postSignUp');

    $app->get('/auth/signin', 'SignInController:getSignIn')->setName('auth.signin');
    $app->post('/auth/signin', 'SignInController:postSignIn');

    $app->get('/auth/recover', 'RecoverController:getRecover')->setName('auth.recover');
    $app->post('/auth/recover', 'RecoverController:postRecover');
    $app->get('/auth/reset/{identifier}', 'RecoverController:getReset')->setName('auth.reset');
    $app->post('/auth/reset/{identifier}', 'RecoverController:postReset');


    $app->get('/auth/activate', 'ActivateController:getActivate')->setName('auth.activate');

})->add(new GuestMiddleware($container));
