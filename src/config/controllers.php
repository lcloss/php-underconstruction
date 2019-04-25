<?php
    $container['HomeController'] = function($c) {
        return new \App\Http\Controllers\HomeController($c);
    };

