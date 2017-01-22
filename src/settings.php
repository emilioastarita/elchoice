<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        'deployJs' => 'prod',

        'test_endpoint' => 'http://residencia.mylocal.com',

        'doctrine' => [
            'entities' => __DIR__ . '/../src/entities/',
            'dev' => true,
            'params' => [
                'driver' => 'pdo_mysql',
                'dbname' => 'elchoice',
                'user' => 'elchoice',
                'password' => 'elchoice',
                'host' => '127.0.0.1',
            ],
            'test_params' => [
                'driver' => 'pdo_sqlite',
                'memory' => true,
                // 'path'   => __DIR__ . '/../database/db.sqlite3',
                'dbname' => 'residencia_test',
            ]
        ]
    ],
];
