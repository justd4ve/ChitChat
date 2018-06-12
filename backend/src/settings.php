<?php

$dotenv = new Dotenv\Dotenv(__DIR__. '/..');
$dotenv->load();

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        
        'db' => [
           'db_host' => getenv('DB_HOST'),
           'db_name' => getenv('DB_NAME'),
           'db_pass' => getenv('DB_PASS'),
           'db_user' => getenv('DB_USER')
        ],
        
        
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
