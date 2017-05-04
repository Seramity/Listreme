<?php

use Respect\Validation\Validator as v;


session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,

        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'lists',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]
    ]
]);

$container = $app->getContainer();

// CONNECT DB WITH ELOQUENT
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};


$container['auth'] = function ($container) {
    return new \App\Auth\Auth;
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};


// SET VIEWS TO TWIG
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    // SEND AUTH INTO VIEWS
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    // SEND FLASH MESSAGES INTO VIEWS
    $view->getEnvironment()->addGlobal('flash', $container->flash);


    return $view;
};

$container['validator'] = function ($container) {
    return new App\Validation\Validator;
};

// CONTROLLERS

$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function ($container) {
    return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function ($container) {
    return new \App\Controllers\Auth\PasswordController($container);
};



$container['csrf'] = function ($container) {
  return new \Slim\Csrf\Guard;
};



// MIDDLEWARE
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\FormInputMiddleware($container));
$app->add(new \App\Middleware\CsrfMiddleware($container));

$app->add($container->csrf);


// SET CUSTOM RULES INTO RESPECT VALIDATOR
v::with('App\\Validation\\Rules\\');


require __DIR__ . '/../app/routes.php';