<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    // Front Controller
    $app->get('/', \HomeController::class . ':index')->setname('home');
    $app->post('/', \HomeController::class . ':sentemail');
    $app->get('/contacto', \HomeController::class . ':contact')->setname('contacto');
    $app->post('/contacto', \HomeController::class . ':sentcontact');

    // Admin Controller
    $app->get('/admin', \AdminController::class . ':index');

    // Misc
    $app->get('/hello/{name}', function ($request, $response, $args) {
        return $response->write("Hello " . $args['name']);
    });
};