<?php

use Respect\Validation\Validator as v;
use Dotenv\Dotenv as dotenv;


session_start();

require __DIR__ . '/../vendor/autoload.php';

//GET ENV VARIABLES
$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load();

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,

        'app' => [
            'name' => $_ENV['APP_NAME'],
            'version' => $_ENV['APP_VERSION'],
            'baseUrl' => $_ENV['APP_BASEURL']
        ],

        'db' => [
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
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
    $view->getEnvironment()->addGlobal('app', $container['settings']['app']);


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


// 404 ERROR HANDLING
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container->view->render($response, 'errors/404.twig')
            ->withStatus(404);
    };
};
// 500 ERROR HANDLING
/*$container['phpErrorHandler'] = function ($container) {
return function ($request, $response) use ($container) {
        return $container->view->render($response, 'errors/500.twig')
            ->withStatus(500);
    };
};
// APP ERROR HANDLING
$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        $data = ['error' => $exception->getMessage()];
        return $container->view->render($response, 'errors/app_error.twig', $data)
            ->withStatus(500);
    };
};*/// REMOVED TEMPORARILY FOR IMPROVED ERROR REPORTING
    // REMOVE THIS FOR PRODUCTION


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

    $mailer->Host = $_ENV['MAIL_HOST'];
    $mailer->SMTPAuth = true;
    $mailer->SMTPSecure = $_ENV['MAIL_SMTPSECURE'];
    $mailer->Port = $_ENV['MAIL_PORT'];
    $mailer->Username = $_ENV['MAIL_USERNAME'];
    $mailer->Password = $_ENV['MAIL_PASSWORD'];
    $mailer->From = $_ENV['MAIL_FROM'];
    $mailer->FromName = $_ENV['MAIL_FROMNAME'];
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