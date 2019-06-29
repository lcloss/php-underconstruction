<?php
    $container['HomeController'] = function($c) {
        return new \App\Http\Controllers\HomeController($c);
    };

    $container['ManagerController'] = function($c) {
        return new \App\Http\Controllers\ManagerController($c);
    };    

