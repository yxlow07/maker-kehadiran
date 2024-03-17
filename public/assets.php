<?php

use core\Router\Request;
use core\Router\Response;

$config = require_once __DIR__.'/../config/config.php';

require_once __DIR__ . '/../config/functions.php';

// Handle resource requests
$request = new Request();
$resourcePath = dirname(__DIR__) . $request->path();
$extension = $request->getExtension($resourcePath);

if ($request->isResource($extension)) {
    $request->setHeader($extension);
    Response::loadResource($resourcePath);
}