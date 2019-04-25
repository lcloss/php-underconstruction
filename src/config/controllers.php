<?php
    $container['HomeController'] = function($c) {
        $view = $c->get("view");  // retrieve the 'view' from the container
        return new \App\Http\Controllers\HomeController($view);
    };

