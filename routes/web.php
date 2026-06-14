<?php
use App\Controllers\AuthController;
use App\Controllers\PortalController;
use App\Controllers\SiteController;
use App\Controllers\UserController;

$router->get('/', [SiteController::class, 'home']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'attempt']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/portal', [PortalController::class, 'index']);
$router->get('/users', [UserController::class, 'index']);
