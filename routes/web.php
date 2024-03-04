<?php

use app\Controllers\AdminController;
use app\Controllers\AuthController;
use app\Controllers\UserController;
use core\Router\RoutesCollector;

$routes = new RoutesCollector();

$routes::GET("/", [UserController::class, 'renderHome']);

// UserModel profiles
$routes::GETPOST('/register',[AuthController::class, 'register']);
$routes::GETPOST('/login', [AuthController::class, 'login']);
$routes::GETPOST('/logout', [AuthController::class, 'logout']);
$routes::GETPOST('/profile', [UserController::class, 'profilePage']);

// UserModel static pages
$routes::GET('/check_attendance', [UserController::class, 'check_attendance']);
$routes::GET('/forgot_password', 'forgot_password');
$routes::GET('/announcements', [UserController::class, 'announcements']);

//Admin pages
$routes::GET('/crud_users', [AdminController::class, 'list_users']);
$routes::GETPOST('/crud_announcements', [AdminController::class, 'crud_announcements']);
$routes::GETPOST('/users/create', [AdminController::class, 'createUsers']);
$routes::GETPOST('/users/{idMurid}/{action}', [AdminController::class, 'crud_users']);

return $routes;