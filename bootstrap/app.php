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
      'database' => 'authentication',
      'username' => 'root',
      'password' => '',
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ],

  ],

]);

$container = $app->getContainer();

// Setup Eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Initialize The Containers
$container['db'] = function($container) use ($capsule){
  return $capsule;
};

$container['auth'] = function($container) {
  return new \App\Auth\Auth;
};

$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$container['view'] = function($container) {

  $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
    'cache' => false,
  ]);

  $view->addExtension(new \Slim\Views\TwigExtension(
    $container->router,
    $container->request->getUri()
  ));

  $view->getEnvironment()->addGlobal('auth', [
      'check' => $container->auth->check(),
      'user' =>  $container->auth->user(),
    ]);

  $view->getEnvironment()->addGlobal('flash', $container->flash);

  return $view;

};

$container['validator'] = function($container) {
      return new \App\Validation\Validator;
};

$container['HomeController'] = function($container) {
  return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container) {
  return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container) {
  return new \App\Controllers\Auth\PasswordController($container);
};

$container['csrf'] = function($container) {
  return new \Slim\Csrf\Guard;
};

// Add Middlewares
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

//
v::with('App\\Validation\\Rules\\');

// 
require __DIR__ . '/../app/routes.php';

 ?>