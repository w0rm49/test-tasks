<?php

return [
    'db' => [
        'host' => 'localhost',
        'port' => '3306',
        'username' => 'username',
        'password' => 'password',
        'database' => 'wmf',
    ],

    'routes' => [
        '/' => 'Auth::index',
        '/register' => 'User::register',
        '/login' => 'Auth::login',
        '/logout' => 'Auth::logout',
        '/profile' => 'User::profile'
    ],
    'defaultRoute' => '/',

    'i18n' => [
        'default' => 'ru',
        'languages' => ['en', 'ru'],
    ],

    'services' => [
        'auth' => '\\Util\\Auth',
        'db' => '\\Util\\Db',
        'i18n' => '\\Util\\I18n'
    ],

    'uploadDir' => BASE_DIR . DIRECTORY_SEPARATOR . 'Public' . DIRECTORY_SEPARATOR . 'upload',
];