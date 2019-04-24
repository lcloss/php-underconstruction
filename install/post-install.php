<?php

// Include helpers
include_once('src/helpers/system.helper.php');

/* Remove cache files */
delTree('temp/cache');

/* Remove all Bootstrap files */
delTree('public/dist/bootstrap');
/* Remove all jQuery files */
delTree('public/dist/jquery');

/* Create dist structure */
$dist_folders = array(
    'bootstrap',
    'bootstrap/css',
    'bootstrap/js',
    'jquery',
);
foreach( $dist_folders as $folder ) {
    if ( !mkdir('public/dist/' . $folder, 0755, true) ) {
        die('Unable to create ' . $folder . ' folder...');
    }
}

/* Copy bootstrap files with structure */
$bootstrap_files = array(
    'css/bootstrap.min.css',
    'js/bootstrap.bundle.min.js'
);
foreach( $bootstrap_files as $file ) {
    copy('vendor/twbs/bootstrap/dist/' . $file, 'public/dist/bootstrap/' . $file);
    chmod('public/dist/bootstrap/' . $file, 0644);
}
/* Copy jquery files with structure */
$jquery_files = array(
    'jquery.min.js',
);
foreach( $jquery_files as $file ) {
    copy('vendor/components/jquery/' . $file, 'public/dist/jquery/' . $file);
    chmod('public/dist/jquery/' . $file, 0644);
}