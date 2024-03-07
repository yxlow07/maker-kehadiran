<?php

use core\Router\Request;

require_once "../vendor/autoload.php";

$request = new Request();
$resourcePath = dirname(__DIR__) . $request->path();

$headers = $request->getRequestHeaders();

//if (isset($headers['Content-Type'])) {
//    $contentType = $headers['Content-Type'];
//
//    match ($contentType) {
//        'application/json' => header("Content-Type: application/json"),
//        'text/css' => ,
//        'text/xml' => header('Content-Type: text/xml'),
//    };
//}

$ext = pathinfo($resourcePath, PATHINFO_EXTENSION);

match ($ext) {
    'js' => header('Content-Type: application/javascript'),
    'css' => header('Content-Type: text/css'),
    'ico' => header('Content-Type: image/x-icon'),
    'gif' => header('Content-Type: image/gif'),
    'jpg' => header('Content-Type: image/jpeg'),
    'png' => header('Content-Type: image/png'),
    default => null,
};

if (file_exists($resourcePath)) {
    readfile($resourcePath);
} else {
    header('HTTP/1.1 404 Not Found');
    echo "<pre>";
    var_dump($resourcePath);
    echo "</pre>";
    exit();
}