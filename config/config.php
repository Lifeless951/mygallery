<?php

return [
    'PROJECT_PATHS' => [
        'project_root' => $_SERVER['DOCUMENT_ROOT'] . '/../',
        'site_root' => $_SERVER['DOCUMENT_ROOT'],
        'templates_directory' => $_SERVER['DOCUMENT_ROOT'] . '/../src/views/'
    ],
    'DB_CONFIG' => [
        'db_prefix' => 'mysql',
        'db_name' => 'my_gallery',
        'host' => 'localhost',
        'login' => 'root',
        'password' => ''
    ]
];