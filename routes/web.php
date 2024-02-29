<?php

use app\Controllers\AuthController;
use app\Controllers\UserController;

$routes = new \core\Router\RoutesCollector();

$routes::GET("/", [\app\Controllers\SiteController::class, 'render']);
$routes::GET("/admin/all", 'admin');
$routes::POST("/users", 'users');

$routes::GET('/register',[AuthController::class, 'renderRegisterPage']);
$routes::POST('/register',[AuthController::class, 'register']);

$routes::GET('/login',[AuthController::class, 'renderLoginPage']);
$routes::POST('/login', [AuthController::class, 'login']);

$routes::GET('/logout', [AuthController::class, 'logout']);

$routes::GETPOST('/profile', [UserController::class, 'profile']);

return $routes;