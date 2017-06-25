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
$container['PasswordController'] = function ($container) {
    return new \App\Controllers\Auth\PasswordController($container);
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

// GENERAL PAGES CONTROLLERS
