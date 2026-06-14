#!/usr/bin/env php
<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/core/Support/Autoloader.php';

$loader = new Core\Support\Autoloader([
    'Core\\' => BASE_PATH . '/core',
    'App\\' => BASE_PATH . '/app',
    'Modules\\' => BASE_PATH . '/modules',
]);
$loader->register();

exit((new Core\Console\Application(BASE_PATH))->run($argv));
