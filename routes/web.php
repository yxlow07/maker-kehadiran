<?php

use app\Controllers\AuthController;

$routes = new \core\Router\RoutesCollector();

$routes::GET("/", [\app\Controllers\SiteController::class, 'render']);
$routes::POST("/users", 'users');
$routes::GET("/admin/all", 'admin');
$routes::GET('/register',[AuthController::class, 'renderRegisterPage']);
$routes::GET('/login',[AuthController::class, 'renderLoginPage']);
$routes::POST('/login', [AuthController::class, 'login']);

return $routes;