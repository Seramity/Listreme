<?php

use Respect\Validation\Validator as v;


session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,

        'app' => [
            'name' => 'Lists',
            'name_dev' => 'Lists v0.0.1-alpha',
            'baseUrl' => 'http://localhost'
        ],

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

    // APP GLOBALS
    $view->getEnvironment()->addGlobal('app_title', $container['settings']['app']['name']);
    $view->getEnvironment()->addGlobal('app_title_dev', $container['settings']['app']['name_dev']);
    $view->getEnvironment()->addGlobal('baseUrl', $container['settings']['app']['baseUrl']);


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


// CONTROLLERS //

$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};

// AUTH CONTROLLERS
$container['AuthController'] = function ($container) {
    return new \App\Controllers\Auth\AuthController($container);
};
$container['PasswordController'] = function ($container) {
    return new \App\Controllers\Auth\PasswordController($container);
};
$container['RecoverController'] = function ($container) {
    return new \App\Controllers\Auth\RecoverController($container);
};
$container['ProfileSettingsController'] = function ($container) {
    return new \App\Controllers\Auth\Account\PRofileSettingsController($container);
};

// USER CONTROLLERS
$container['ProfileController'] = function ($container) {
    return new \App\Controllers\User\ProfileController($container);
};


// 404 ERROR HANDLING
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container->view->render($response, 'errors/404.twig')->withStatus(404);
    };
};
// 500 ERROR HANDLING
$container['phpErrorHandler'] = function ($container) {
  return function ($request, $response) use ($container) {
    return $container->view->render($response, 'errors/500.twig')->withStatus(500);
  };
};


$container['csrf'] = function ($container) {
  return new \Slim\Csrf\Guard;
};

// RANDOMLIB FOR AUTH HASHING
$container['hash'] = function ($container) {
  return new \App\Hash\Hash;
};
$container['randomlib'] = function ($container) {
  return new \RandomLib\Factory;
};
$container['securitylib'] = function ($container) {
    return new SecurityLib\Strength(SecurityLib\Strength::MEDIUM);
};


// MAILER
$container['mailer'] = function ($container) {
    $mailer = new PHPMailer(true);
    $mailer->IsSMTP();

    $mailer->Host = '';
    $mailer->SMTPAuth = true;
    $mailer->SMTPSecure = 'tls';
    $mailer->Port = 80;
    $mailer->Username = '';
    $mailer->Password = '';
    $mailer->From = '';
    $mailer->FromName = '';
    $mailer->isHTML(true);

    //  PHP 5.6+ SSL fix
    $mailer->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        )
    );

    return new \App\Mail\Mailer($container->view, $mailer);
};


// MIDDLEWARE
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\FormInputMiddleware($container));
$app->add(new \App\Middleware\CsrfMiddleware($container));

$app->add($container->csrf);



// SET CUSTOM RULES INTO RESPECT VALIDATOR
v::with('App\\Validation\\Rules\\');


require __DIR__ . '/../app/routes.php';