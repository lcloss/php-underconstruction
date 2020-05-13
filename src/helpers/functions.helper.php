<?php

function get_var($var) {
    ob_start();
    var_dump($var);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function get_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }    
    return $ip;
}