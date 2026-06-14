<?php

declare(strict_types=1);

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
            self::compileToFile($src, $compiled);
        }

        $vars = $data;
        $output = self::evaluate($compiled, $vars);

        if (isset($vars['__layout']) && is_string($vars['__layout']) && $vars['__layout'] !== '') {
            $layoutVars = $data;
            $layoutVars['__sections'] = $vars['__sections'] ?? [];
            $layoutVars['__content'] = $output;
            return self::render($vars['__layout'], $layoutVars);
        }

        return $output;
    }

    /** @param array<string,mixed> $vars */
    private static function evaluate(string $compiled, array &$vars): string
    {
        extract($vars, EXTR_SKIP);
        ob_start();
        require $compiled;
        $output = (string) ob_get_clean();

        foreach (get_defined_vars() as $key => $value) {
            if (str_starts_with($key, '__')) {
                $vars[$key] = $value;
            }
        }

        return $output;
    }

    public static function renderLayout(string $layout, array $vars): string
    {
        unset($vars['__layout']);
        return self::render($layout, $vars);
    }

    public static function component(string $name, array $props = []): string
    {
        return ComponentRegistry::render($name, $props);
    }

    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public static function clearCache(): int
    {
        $dir = BASE_PATH . '/storage/framework/views';
        if (! is_dir($dir)) {
            return 0;
        }
        $count = 0;
        foreach (glob($dir . '/*.php') ?: [] as $file) {
            if (is_file($file)) {
                @unlink($file);
                $count++;
            }
        }
        return $count;
    }

    private static function compileToFile(string $src, string $compiled): void
    {
        $php = Compiler::compile((string) file_get_contents($src));
        $tmp = $compiled . '.tmp';
        file_put_contents($tmp, $php);
        $lint = self::lint($tmp);
        if ($lint !== true) {
            @unlink($tmp);
            throw new \RuntimeException('Vue compilée invalide pour ' . basename($src) . ' : ' . $lint);
        }
        rename($tmp, $compiled);
    }

    private static function lint(string $file): true|string
    {
        $cmd = escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file) . ' 2>&1';
        exec($cmd, $output, $code);
        return $code === 0 ? true : implode("\n", $output);
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
