<?php

if (! function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        static $items = null;
        if ($items === null) {
            $items = [];
            foreach (glob(BASE_PATH . '/config/*.php') as $file) {
                $items[basename($file, '.php')] = require $file;
            }
        }
        $segments = explode('.', $key);
        $value = $items;
        foreach ($segments as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }
}
