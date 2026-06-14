<?php

declare(strict_types=1);

namespace Core\View;

use App\View\Components\Form;
use App\View\Components\Html;
use App\View\Components\Ui;
use InvalidArgumentException;

final class ComponentRegistry
{
    /** @var array<string, callable(array<string,mixed>): string> */
    private static array $components = [];
    private static bool $booted = false;

    /** @param callable(array<string,mixed>): string $renderer */
    public static function register(string $name, callable $renderer): void
    {
        self::$components[self::normalize($name)] = $renderer;
    }

    /** @var array<int,string> */
    private static array $renderingStack = [];

    /** @param array<string,mixed> $props */
    public static function render(string $name, array $props = []): string
    {
        self::boot();
        $key = self::normalize($name);

        if (! isset(self::$components[$key])) {
            throw new InvalidArgumentException("Composant KIVO introuvable: {$name}");
        }

        if (in_array($key, self::$renderingStack, true)) {
            $chain = implode(' -> ', [...self::$renderingStack, $key]);
            throw new \RuntimeException("Boucle de rendu de composant détectée: {$chain}");
        }

        self::$renderingStack[] = $key;
        try {
            return (string) self::$components[$key]($props);
        } finally {
            array_pop(self::$renderingStack);
        }
    }

    public static function boot(): void
    {
        if (self::$booted) {
            return;
        }
        self::$booted = true;

        self::register('input', fn(array $p): string => Form::input((string)($p['name'] ?? ''), (string)($p['label'] ?? ''), $p['value'] ?? '', self::attrs($p, ['name','label','value'])));
        self::register('textarea', fn(array $p): string => Form::textarea((string)($p['name'] ?? ''), (string)($p['label'] ?? ''), $p['value'] ?? '', self::attrs($p, ['name','label','value'])));
        self::register('select', fn(array $p): string => Form::select((string)($p['name'] ?? ''), (string)($p['label'] ?? ''), is_array($p['options'] ?? null) ? $p['options'] : [], $p['selected'] ?? null, self::attrs($p, ['name','label','options','selected'])));
        self::register('select-search', fn(array $p): string => Form::selectSearch((string)($p['name'] ?? ''), (string)($p['label'] ?? ''), is_array($p['options'] ?? null) ? $p['options'] : [], $p['selected'] ?? null, self::attrs($p, ['name','label','options','selected'])));
        self::register('checkbox', fn(array $p): string => Form::checkbox((string)($p['name'] ?? ''), (string)($p['label'] ?? ''), (bool)($p['checked'] ?? false), self::attrs($p, ['name','label','checked'])));
        self::register('dropzone', fn(array $p): string => Form::dropzone((string)($p['name'] ?? ''), (string)($p['label'] ?? 'Fichier'), self::attrs($p, ['name','label'])));
        self::register('button', fn(array $p): string => Ui::button((string)($p['label'] ?? ''), $p['href'] ?? '', (string)($p['variant'] ?? 'primary'), (string)($p['type'] ?? 'button')));
        self::register('badge', fn(array $p): string => Ui::badge((string)($p['label'] ?? ''), (string)($p['tone'] ?? 'neutral')));
        self::register('empty-state', fn(array $p): string => Ui::emptyState((string)($p['title'] ?? ''), (string)($p['message'] ?? '')));
        self::register('page-header', fn(array $p): string => Ui::pageHeader((string)($p['title'] ?? ''), (string)($p['subtitle'] ?? ''), [
            'eyebrow' => (string)($p['eyebrow'] ?? ''),
            'actions' => (string)($p['actions'] ?? ''),
            'class' => (string)($p['class'] ?? ''),
        ]));
        self::register('section', fn(array $p): string => Ui::section((string)($p['title'] ?? ''), (string)($p['content'] ?? ''), (string)($p['subtitle'] ?? ''), self::attrs($p, ['title','content','subtitle'])));
        self::register('module-card', fn(array $p): string => '<a class="kivo-module-card" href="' . View::e((string)($p['href'] ?? '#')) . '"><strong>' . View::e((string)($p['title'] ?? 'Module')) . '</strong><p>' . View::e((string)($p['description'] ?? '')) . '</p></a>');
    }

    /** @param array<string,mixed> $props @param array<int,string> $except @return array<string,mixed> */
    private static function attrs(array $props, array $except = []): array
    {
        foreach ($except as $key) {
            unset($props[$key]);
        }
        $nested = isset($props['attrs']) && is_array($props['attrs']) ? $props['attrs'] : [];
        unset($props['attrs']);
        $props += $nested;
        return $props;
    }

    private static function normalize(string $name): string
    {
        return str_replace('_', '-', strtolower(trim($name)));
    }
}
