<?php

use Core\Config\Env;
use Core\Http\Application;
use Core\Routing\Router;
use Core\Support\Autoloader;

if (! defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

require_once BASE_PATH . '/core/Support/Autoloader.php';

$loader = new Autoloader([
    'Core\\' => BASE_PATH . '/core',
    'App\\' => BASE_PATH . '/app',
    'Modules\\' => BASE_PATH . '/modules',
]);
$loader->register();

require_once BASE_PATH . '/core/Support/helpers.php';

Env::load(BASE_PATH . '/.env');

$router = new Router();
require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/api.php';

return new Application($router);
