<?php

use Core\Http\Application;
use Core\Routing\Router;

require_once BASE_PATH . '/core/Support/helpers.php';

$router = new Router();
require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/api.php';
return new Application($router);
