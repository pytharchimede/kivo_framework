<?php
namespace Core\View;

final class ComponentTag
{
    public static function render(string $name, string $attrs, array $scope = []): string
    {
        preg_match_all('/([:\\w-]+)=\"([^\"]*)\"/', $attrs, $matches, PREG_SET_ORDER);
        $props = [];
        foreach ($matches as $attr) {
            $rawKey = $attr[1];
            $key = ltrim($rawKey, ':');
            $value = $attr[2];
            if (str_starts_with($rawKey, ':')) {
                extract($scope, EXTR_SKIP);
                $props[$key] = eval('return ' . $value . ';');
            } else {
                $props[$key] = $value;
            }
        }
        return View::component(str_replace('-', '_', $name), $props);
    }
}
