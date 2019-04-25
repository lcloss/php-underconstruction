<?php

use Slim\App;

return function (App $app) {
    // Csrf protection
    $app->add(new \Slim\Csrf\Guard);
};
