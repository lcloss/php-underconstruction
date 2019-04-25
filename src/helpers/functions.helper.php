<?php

function get_var($var) {
    ob_start();
    var_dump($var);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}