<?php

namespace Core\Support;

final class Autoloader
{
    /** @var array<string,string> */
    private array $prefixes;

    public function __construct(array $prefixes)
    {
        $this->prefixes = $prefixes;
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'load']);
    }

    public function load(string $class): void
    {
        foreach ($this->prefixes as $prefix => $baseDir) {
            if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
                continue;
            }

            $relative = substr($class, strlen($prefix));
            $file = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';

            if (is_file($file)) {
                require_once $file;
            }
        }
    }
}
