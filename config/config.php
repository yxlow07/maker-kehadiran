<?php

$dir = dirname(__DIR__);

require_once $dir.'/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable($dir);
$dotenv->load();

//echo password_hash('123', PASSWORD_BCRYPT);

return [
    'dir' => $dir,
    'dotenv' => $dotenv,
    'view_path' => $dir . '/views/',
    'cache_path' => $dir . '/views/cache/',
    'layout_path' => $dir . '/views/layouts/',
    'db' => [
        'DSN' => $_ENV['DSN'],
        'USERNAME' => $_ENV['USERNAME'],
        'PASSWORD' => $_ENV['PASSWORD'],
    ],
    'middlewares' => include_once "middlewares.php",
];


