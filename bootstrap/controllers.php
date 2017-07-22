<?php

$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};

// AUTH CONTROLLERS
$container['SignInController'] = function ($container) {
    return new \App\Controllers\Auth\SignInController($container);
};
$container['SignUpController'] = function ($container) {
    return new \App\Controllers\Auth\SignUpController($container);
};
$container['SignOutController'] = function ($container) {
    return new \App\Controllers\Auth\SignOutController($container);
};
$container['ActivateController'] = function ($container) {
    return new \App\Controllers\Auth\ActivateController($container);
};
$container['ChangePasswordController'] = function ($container) {
    return new \App\Controllers\Auth\Account\ChangePasswordController($container);
};
$container['RecoverController'] = function ($container) {
    return new \App\Controllers\Auth\RecoverController($container);
};
$container['ProfileSettingsController'] = function ($container) {
    return new \App\Controllers\Auth\Account\ProfileSettingsController($container);
};
$container['PictureSettingsController'] = function ($container) {
    return new \App\Controllers\Auth\Account\PictureSettingsController($container);
};
$container['DeleteAccountController'] = function ($container) {
    return new \App\Controllers\Auth\Account\DeleteAccountController($container);
};


// USER CONTROLLERS
$container['ProfileController'] = function ($container) {
    return new \App\Controllers\User\ProfileController($container);
};

// LIST CONTROLLERS
$container['ListController'] = function ($container) {
    return new \App\Controllers\Lists\ListController($container);
};
$container['NewListController'] = function ($container) {
    return new \App\Controllers\Lists\NewListController($container);
};
$container['EditListController'] = function ($container) {
    return new \App\Controllers\Lists\EditListController($container);
};
$container['DeleteListController'] = function ($container) {
    return new \App\Controllers\Lists\DeleteListController($container);
};
$container['FavoriteListController'] = function ($container) {
    return new \App\Controllers\Lists\FavoriteListController($container);
};

$container['CommentController'] = function ($container) {
    return new \App\Controllers\Comment\CommentController($container);
};

// EXPLORE CONTROLLERS
$container['ExploreController'] = function($container) {
    return new \App\Controllers\Explore\ExploreController($container);
};

// GENERAL PAGES CONTROLLERS
$container['AboutController'] = function ($container) {
    return new \App\Controllers\Pages\About\AboutController($container);
};
$container['TermsController'] = function ($container) {
    return new \App\Controllers\Pages\About\TermsController($container);
};
$container['PrivacyController'] = function ($container) {
    return new \App\Controllers\Pages\About\PrivacyController($container);
};
$container['GuidelinesController'] = function ($container) {
    return new \App\Controllers\Pages\About\GuidelinesController($container);
};