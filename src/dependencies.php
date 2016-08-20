<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

//database eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) {
    return $capsule;
};
$container['validator'] = function($container){
    return new \App\Validation\validator;
};
$container['hash_password'] = function($container){
    return new \App\Controllers\hash_password;
};
$container['user_controller'] = function($container){
	return new \App\Controllers\user_controller($container);
};
$container['auth_controller'] = function($container){
	return new \App\Controllers\auth_controller($container);
};
