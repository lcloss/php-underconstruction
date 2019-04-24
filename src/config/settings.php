<?php

// See: https://github.com/vlucas/phpdotenv
$dotenv = Dotenv\Dotenv::create(__DIR__ . '\\..\\..\\');
$dotenv->load();

$e_displayErrors = getenv('DISPLAY_ERRORS', false);

/* --- Configuration -- */

return [
    'settings' => [
        'displayErrorDetails'       => getenv('DISPLAY_ERRORS', false),     // Turn false in production
        'addContentLengthHeader'    => false,                               // Allow the web server to send the content-length header

        // View settings
        'view'                      => [
            'views_path'            => __DIR__ . '/../../app/Views/',
        ],

        // Logger settings
        'logger'                    => [
            'name'      => 'underconstruction',
            'path'      => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../../temp/log',
            'level'     => \Monolog\Logger::DEBUG,
        ],

        // Database settings
        'db'                        => [
            'host'      => getenv('DB_HOST', 'localhost'),
            'user'      => getenv('DB_USER', 'user'),
            'pass'      => getenv('DB_PASS', 'pass'),
            'dbname'    => getenv('DB_NAME', 'dbname'),
        ],

        // Mail settings
        'mail'                      => [
            'host'          => getenv('MAIL_HOST', 'localhost'),
            'smtp_port'     => getenv('MAIL_PORT', '25'),
            'smtp_secure'   => getenv('MAIL_SMTP_SEC', 'tls'),
            'require_auth'  => getenv('MAIL_REQ_AUTH', true),
            'name_from'     => getenv('MAIL_FROM_NAME', 'Webmaster'),
            'email_from'    => getenv('MAIL_FROM_ADDR', 'info@example.com'),
            'reply_to'      => getenv('MAIL_REPLY_TO', 'no-reply@example.com'),
            'username'      => getenv('MAIL_USERNAME', 'info@example.com'),
            'password'      => getenv('MAIL_PASSWORD', 'pass'),
        ],
    
    ]
];
