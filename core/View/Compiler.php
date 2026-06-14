<?php
namespace Core\View;

final class Compiler
{
    public static function compile(string $content): string
    {
        $content = preg_replace('/@extends\([\'\"](.+?)[\'\"]\)/', '<?php $__layout="$1"; ?>', $content);
        $content = preg_replace('/@section\([\'\"](.+?)[\'\"]\)/', '<?php ob_start(); $__section="$1"; ?>', $content);
        $content = str_replace('@endsection', '<?php $__sections[$__section]=ob_get_clean(); ?>', $content);
        $content = preg_replace('/@yield\([\'\"](.+?)[\'\"]\)/', '<?= $__sections["$1"] ?? "" ?>', $content);
        $content = preg_replace('/@foreach\s*\((.*?)\)/', '<?php foreach($1): ?>', $content);
        $content = str_replace('@endforeach', '<?php endforeach; ?>', $content);
        $content = preg_replace('/@if\s*\((.*?)\)/', '<?php if($1): ?>', $content);
        $content = str_replace('@else', '<?php else: ?>', $content);
        $content = str_replace('@endif', '<?php endif; ?>', $content);
        $content = preg_replace('/<x-([a-zA-Z0-9_-]+)([^>]*)\/>/', '<?= \\Core\\View\\ComponentTag::render("$1", "$2", get_defined_vars()) ?>', $content);
        $content = preg_replace('/\{\{\s*(.*?)\s*\}\}/', '<?= \\Core\\View\\View::e($1) ?>', $content);
        $content = preg_replace('/\{!!\s*(.*?)\s*!!\}/', '<?= $1 ?>', $content);
        $content .= '<?php if(isset($__layout)){ echo \\Core\\View\\View::renderLayout($__layout, get_defined_vars()); } ?>';
        return $content;
    }
}
