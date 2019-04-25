<?php
session_start();
$s = DIRECTORY_SEPARATOR;

require __DIR__ . $s . '..' . $s . 'helpers' . $s . 'functions.helper.php';

// Set up settings and Instantiate the app
$settings = require __DIR__ . $s . 'settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . $s . 'dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . $s . 'middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . $s . 'routes.php';
$routes($app);
