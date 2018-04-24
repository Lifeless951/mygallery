<?php

return [
    'PROJECT_PATHS' => [
        'PROJECT_ROOT' => $_SERVER['DOCUMENT_ROOT'] . '/../',
        'WWW_ROOT' => $_SERVER['DOCUMENT_ROOT'],
        'TEMPLATES_DIRECTORY' => $_SERVER['DOCUMENT_ROOT'] . '/../src/views/'
    ],
    'DB_CONFIG' => [
        'db_class' => 'PDOConnection',
        'host' => 'localhost',
        'database' => 'my_gallery',
        'login' => 'root',
        'password' => ''
    ]
];