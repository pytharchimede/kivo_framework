<?php
use App\Controllers\UserApiController;

$router->get('/api/health', fn () => json_encode([
    'framework' => 'KIVO Framework',
    'version' => config('app.version'),
    'status' => 'ok',
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

$router->apiResource('users', UserApiController::class);
