<?php

define('BASE_DIR', realpath(__DIR__ . '/../'));

spl_autoload_register(function ($class) {
    $file = BASE_DIR . '/' . $class . '.php';
    $file = str_replace('/', DIRECTORY_SEPARATOR, $file); //windows compatibility

    if (file_exists($file) && is_readable($file)) {
        require $file;
    }
});

App::obj()->run();