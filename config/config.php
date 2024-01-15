<?php

$dir = dirname(__DIR__);

require_once $dir.'/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable($dir);
$dotenv->load();

return [
    'dir' => $dir,
    'dotenv' => $dotenv,
    'view_path' => $dir . '/views/',
    'cache_path' => $dir . '/views/cache/',
    'layout_path' => $dir . '/views/layouts/',
];

