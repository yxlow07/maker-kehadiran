<?php

global $dir;
define('START', microtime(true));

use core\App;
use core\Router\RoutesCollector;

$config = require_once __DIR__.'/../config/config.php';

/** @var \core\Router\RoutesCollector $routes */
$routes = require_once $dir."/routes/web.php";
$app = new App($routes, $config);

$app->run('web');