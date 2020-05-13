<?php

use Slim\App;
use App\Database;

return function (App $app) {
    $container = $app->getContainer();

    // Twig Viewer
    $container['view'] = function ($c) {
        $view = new \Slim\Views\Twig('../app/Views', [
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
        $config = $c['settings']['db'];
        try {
            $pdo = new \PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['pass']);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $db = new Database($pdo);

        return $db;
    };

    // CSRF 
    $container['csrf'] = function ($c) {
        return new \Slim\Csrf\Guard();
    };

    // Flash Messages
    $container['flash'] = function ($c) {
        return new \Slim\Flash\Messages();
    };
    
    // Controllers
    require 'controllers.php';
};