<?php

declare(strict_types=1);

namespace Core\View;

final class ComponentTag
{
    /** @param array<string,mixed> $scope */
    public static function render(string $name, string $attrs, array $scope = []): string
    {
        return ComponentRegistry::render($name, self::parseAttributes($attrs, $scope));
    }

    /** @param array<string,mixed> $scope @return array<string,mixed> */
    private static function parseAttributes(string $attrs, array $scope): array
    {
        $props = [];
        preg_match_all('/\s+(:?[A-Za-z_][A-Za-z0-9_:\-]*)(?:\s*=\s*("[^"]*"|\'[^\']*\'|[^\s"\']+))?/', $attrs, $matches, PREG_SET_ORDER);

        foreach ($matches as $attr) {
            $rawKey = $attr[1];
            $isExpression = str_starts_with($rawKey, ':');
            $key = ltrim($rawKey, ':');
            $key = str_replace('-', '_', $key);

            if (! isset($attr[2]) || $attr[2] === '') {
                $props[$key] = true;
                continue;
            }

            $rawValue = trim($attr[2]);
            if ((str_starts_with($rawValue, '"') && str_ends_with($rawValue, '"')) || (str_starts_with($rawValue, "'") && str_ends_with($rawValue, "'"))) {
                $rawValue = substr($rawValue, 1, -1);
            }

            if ($isExpression) {
                $props[$key] = self::evaluateExpression($rawValue, $scope);
            } else {
                $props[$key] = html_entity_decode($rawValue, ENT_QUOTES, 'UTF-8');
            }
        }

        return $props;
    }

    /** @param array<string,mixed> $scope */
    private static function evaluateExpression(string $expression, array $scope): mixed
    {
        extract($scope, EXTR_SKIP);
        return eval('return ' . $expression . ';');
    }
}
