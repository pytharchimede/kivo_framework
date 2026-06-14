<?php
use App\Controllers\PortalController;
use App\Controllers\SiteController;
use App\Controllers\UserController;

$router->get('/', [SiteController::class, 'home']);
$router->get('/portal', [PortalController::class, 'index']);
$router->get('/users', [UserController::class, 'index']);
