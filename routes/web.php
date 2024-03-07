<?php

use app\Controllers\AdminController;
use app\Controllers\AuthController;
use app\Controllers\UserController;
use core\Router\RoutesCollector;

$routes = new RoutesCollector();

$guest = 'guest';
$user = 'auth-user';
$admin = 'auth-admin';

$routes->GET("/", [UserController::class, 'renderHome']);

// UserModel profiles
$routes->GETPOST('/register',[AuthController::class, 'register'])->only($guest);
$routes->GETPOST('/login', [AuthController::class, 'login'])->only($guest);
$routes->GETPOST('/logout', [AuthController::class, 'logout'])->only('auth');
$routes->GETPOST('/profile', [UserController::class, 'profilePage'])->only($user);

// UserModel static pages
$routes->GET('/check_attendance', [UserController::class, 'check_attendance'])->only($user);
$routes->GET('/forgot_password', 'forgot_password')->only($guest);
$routes->GET('/announcements', [UserController::class, 'announcements']);

//Admin pages
$routes->GET('/crud_users', [AdminController::class, 'list_users'])->only($admin);
$routes->GET('/analysis_attendance', [AdminController::class, 'analysis_kehadiran'])->only($admin);
$routes->GETPOST('/crud_announcements', [AdminController::class, 'crud_announcements'])->only($admin);
$routes->GETPOST('/users/create', [AdminController::class, 'createUsers'])->only($admin);
$routes->GETPOST('/users/{idMurid}/kehadiran', [AdminController::class, 'kehadiran'])->only($admin);
$routes->GETPOST('/users/{idMurid}/{action}', [AdminController::class, 'crud_users'])->only($admin);
$routes->GETPOST('/kehadiran/upload', [AdminController::class, 'upload_kehadiran'])->only($admin);

return $routes;