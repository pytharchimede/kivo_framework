<?php

define('BASE_PATH', dirname(__DIR__));

$autoload = BASE_PATH . '/vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
} else {
    spl_autoload_register(function (string $class): void {
        $prefixes = [
            'App\\' => BASE_PATH . '/app/',
            'Core\\' => BASE_PATH . '/core/',
            'Modules\\' => BASE_PATH . '/modules/',
        ];
        foreach ($prefixes as $prefix => $baseDir) {
            if (str_starts_with($class, $prefix)) {
                $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
                $file = $baseDir . $relative . '.php';
                if (is_file($file)) {
                    require $file;
                }
            }
        }
    });
}

$app = require BASE_PATH . '/bootstrap.php';
$app->run();
