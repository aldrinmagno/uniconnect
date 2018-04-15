<?php
return [
    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
        // comment this line when deploy to production environment
        'displayErrorDetails' => getenv('displayErrorDetails'),
        // View settings
        'view' => [
            'template_path' => '../src/view',
            'twig' => [
            //    'cache' => __DIR__ . getenv('cache'),
                'debug' => getenv('debug'),
                'auto_reload' => getenv('auto_reload'),
            ],
        ],
        // db settings
        'db' => [
            'host'     => getenv('host'),
            'port'     => getenv('port'),
            'dbname'   => getenv('dbname'),
            'user'     => getenv('user'),
            'password' => getenv('password'),
        ],
        // monolog settings
        'logger' => [
            'name' => getenv('name'),
            'path' => getenv('path'),
        ],
        // Burst SMS 
        'burstSMS' => [
            'user' => getenv('burst_sms_user'),
            'password' => getenv('burst_sms_password'),
        ],
        // Swift Mailer
        'SwiftMailer' => [
            'server' => getenv('swift_mailer_server'),
            'username' => getenv('swift_mailer_user'),
            'password' => getenv('swift_mailer_password'),
        ],
    ],
];
