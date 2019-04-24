
<?php

/**
 * Removes directory recursivelly and it's contents
 */
function delTree($dir) {
    if ( is_dir($dir) ) {
        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir); 
    } else {
        return true;
    }
}