<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // Twig Viewer
    $container['view'] = function ($c) {
        $view = new \Slim\Views\Twig('../app/views', [
            // 'cache' => '../temp/cache'
            'cache' => false,
            'debug' => true,
        ]);
        
        // Instantiate and add Slim specific extension
        $router = $c->get('router');
        $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
        $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    
        return $view;
    };

    // Monolog logger
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // PDO Database handler
    $container['db'] = function ($c) {
        $db = $c['settings']['db'];
        $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'], $db['user'], $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    };

    // Controllers
    $container['HomeController'] = function($c) {
        $view = $c->get("view");  // retrieve the 'view' from the container
        return new \App\Http\Controllers\HomeController($view);
    };

    $container['AdminController'] = function($c) {
        $view = $c->get("view");  // retrieve the 'view' from the container
        return new \App\Http\Controllers\AdminController($view);
    };    
};