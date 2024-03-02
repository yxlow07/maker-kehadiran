<?php

use app\Controllers\AuthController;
use app\Controllers\UserController;

$routes = new \core\Router\RoutesCollector();

$routes::GET("/", [UserController::class, 'renderHome']);
$routes::GET("/admin/all", 'admin');
$routes::POST("/users", 'users');

// UserModel profiles
$routes::GETPOST('/register',[AuthController::class, 'register']);
$routes::GETPOST('/login', [AuthController::class, 'login']);
$routes::GETPOST('/logout', [AuthController::class, 'logout']);
$routes::GETPOST('/profile', [UserController::class, 'profilePage']);

// UserModel static pages
$routes::GET('/check_attendance', [UserController::class, 'check_attendance']);
$routes::GET('/forgot_password', 'forgot_password');
$routes::GET('/announcements', [UserController::class, 'announcements']);

return $routes;