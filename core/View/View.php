<?php
namespace Core\View;

final class View
{
    public static function render(string $view, array $data = []): string
    {
        $src = BASE_PATH . '/resources/views/' . str_replace('.', '/', $view) . '.ublade.php';
        if (! is_file($src)) {
            throw new \RuntimeException('Vue introuvable: ' . $view);
        }
        $compiled = self::compiledPath($src);
        if (! is_file($compiled) || filemtime($compiled) < filemtime($src)) {
            file_put_contents($compiled, Compiler::compile(file_get_contents($src)));
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $compiled;
        return (string) ob_get_clean();
    }

    public static function renderLayout(string $layout, array $vars): string
    {
        unset($vars['__layout']);
        return self::render($layout, $vars);
    }

    public static function component(string $name, array $props = []): string
    {
        return self::render('components.' . $name, $props);
    }

    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    private static function compiledPath(string $src): string
    {
        $dir = BASE_PATH . '/storage/framework/views';
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir . '/' . md5($src) . '.php';
    }
}
