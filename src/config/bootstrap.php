<?php
session_start();

require '../src/helpers/functions.helper.php';

// Set up settings and Instantiate the app
$settings = require 'settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require 'dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require 'middleware.php';
$middleware($app);

// Register routes
$routes = require 'routes.php';
$routes($app);
