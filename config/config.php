<?php

$dir = dirname(__DIR__) . '\\';

require_once $dir.'/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createMutable($dir); // If any issue arises, use back createImmutable, refer discord /errors
$dotenv->load();

//echo password_hash('123', PASSWORD_BCRYPT);

return [
    'dir' => $dir,
    'dotenv' => $dotenv,
    'view_path' => $dir . '/views/',
    'cache_path' => $dir . '/views/cache/',
    'layout_path' => $dir . '/views/layouts/',
    'resources_path' => $dir . '/resources/',
    'db' => [
        'DSN' => $_ENV['DSN'],
        'USERNAME' => $_ENV['USERNAME'],
        'PASSWORD' => $_ENV['PASSWORD'],
    ],
    'middlewares' => include_once "middlewares.php",
];