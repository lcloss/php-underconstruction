<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    // Front Controller
    /*
    $app->get('/', 'HomeController::index')->setname('home');
    $app->post('/', 'HomeController::sentemail');
    */
    $app->get('/', \HomeController::class . ':index')->setname('home');
    $app->post('/', \HomeController::class . ':sentemail');
    $app->get('/contacto', \HomeController::class . ':contact')->setname('contacto');
    $app->post('/contacto', \HomeController::class . ':sentcontact');

    // Admin Controller
    // $admin_controller_path = PATH_SEPARATOR . "app" . PATH_SEPARATOR . "Http" . PATH_SEPARATOR . "Controllers" . PATH_SEPARATOR;
    $controllers_path = "app/Http/Controllers/";
    if (file_exists($controllers_path . "AdminController.php")) {
        $app->get('/admin', \AdminController::class . ':index');
    }

    // Misc
    $app->get('/hello/{name}', function ($request, $response, $args) {
        return $response->write("Hello " . $args['name']);
    });
};