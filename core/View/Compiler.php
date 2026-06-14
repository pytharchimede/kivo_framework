<?php

declare(strict_types=1);

namespace Core\View;

final class Compiler
{
    public static function compile(string $content): string
    {
        self::guardAgainstRawPhp($content);

        $content = preg_replace('/@extends\([\'\"](.+?)[\'\"]\)/', '<?php $__layout="$1"; ?>', $content);
        $content = preg_replace('/@section\([\'\"](.+?)[\'\"]\)/', '<?php ob_start(); $__section="$1"; ?>', $content);
        $content = str_replace('@endsection', '<?php $__sections[$__section]=ob_get_clean(); unset($__section); ?>', $content);
        $content = preg_replace('/@yield\([\'\"](.+?)[\'\"]\)/', '<?= $__sections["$1"] ?? "" ?>', $content);

        $content = preg_replace('/@foreach\s*\((.*?)\)/', '<?php foreach($1): ?>', $content);
        $content = str_replace('@endforeach', '<?php endforeach; ?>', $content);
        $content = preg_replace('/@if\s*\((.*?)\)/', '<?php if($1): ?>', $content);
        $content = str_replace('@else', '<?php else: ?>', $content);
        $content = str_replace('@endif', '<?php endif; ?>', $content);

        $content = preg_replace('/\{!!\s*(.*?)\s*!!\}/s', '<?= $1 ?>', $content);
        $content = preg_replace('/\{\{\s*(.*?)\s*\}\}/s', '<?= \\Core\\View\\View::e($1) ?>', $content);

        $content = self::compileSelfClosingComponents($content);

        return $content;
    }

    private static function compileSelfClosingComponents(string $content): string
    {
        return (string) preg_replace_callback('/<x-([a-zA-Z0-9_-]+)([^>]*)\/>/', static function (array $matches): string {
            $name = var_export($matches[1], true);
            $attrs = var_export($matches[2] ?? '', true);
            return '<?= \\Core\\View\\ComponentTag::render(' . $name . ', ' . $attrs . ', get_defined_vars()) ?>';
        }, $content);
    }

    private static function guardAgainstRawPhp(string $content): void
    {
        if (str_contains($content, '<?') || str_contains($content, '?>')) {
            throw new \RuntimeException('PHP brut interdit dans les vues .ublade.php. Utilise les directives KIVO ou un composant enregistré dans ComponentRegistry.');
        }
    }
}
