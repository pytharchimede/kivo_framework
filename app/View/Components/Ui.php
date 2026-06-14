<?php

declare(strict_types=1);

namespace App\View\Components;

use Core\View\View;

final class Ui
{
    /** @param string|array<string,mixed> $subtitleOrOptions @param array<string,mixed> $attrs */
    public static function pageHeader(string $titleOrEyebrow, string $subtitleOrTitle = '', string|array $subtitleOrOptions = '', string $actions = '', array $attrs = []): string
    {
        if (is_array($subtitleOrOptions)) {
            $options = $subtitleOrOptions;
            $title = $titleOrEyebrow;
            $subtitle = $subtitleOrTitle;
            $eyebrow = (string) ($options['eyebrow'] ?? '');
            $actions = (string) ($options['actions'] ?? '');
            $attrs = $options;
            unset($attrs['eyebrow'], $attrs['actions']);
        } else {
            $eyebrow = $titleOrEyebrow;
            $title = $subtitleOrTitle;
            $subtitle = $subtitleOrOptions;
        }

        $class = Html::classes(['kivo-page-header', (string) ($attrs['class'] ?? '')]);
        $eyebrowHtml = $eyebrow !== '' ? '<p class="kivo-eyebrow">' . View::e($eyebrow) . '</p>' : '';

        return '<section class="' . View::e($class) . '"><div>' . $eyebrowHtml . '<h1>' . View::e($title) . '</h1>'
            . ($subtitle !== '' ? '<p>' . View::e($subtitle) . '</p>' : '')
            . '</div>' . $actions . '</section>';
    }

    /** @param array<string,mixed> $attrs */
    public static function section(string $title, string $content, string $subtitle = '', array $attrs = []): string
    {
        $class = Html::classes(['kivo-section-card', (string) ($attrs['class'] ?? '')]);
        return '<section class="' . View::e($class) . '"><div class="kivo-section-heading"><h2 class="kivo-section-title">' . View::e($title) . '</h2>'
            . ($subtitle !== '' ? '<span>' . View::e($subtitle) . '</span>' : '')
            . '</div>' . $content . '</section>';
    }

    /** @param string|array<string,mixed>|null $hrefOrOptions @param string|array<string,mixed> $variantOrOptions */
    public static function button(string $label, string|array|null $hrefOrOptions = '', string|array $variantOrOptions = 'primary', string $type = 'button'): string
    {
        if (is_array($hrefOrOptions)) {
            $options = $hrefOrOptions;
            $href = (string) ($options['href'] ?? '');
            $variant = (string) ($options['variant'] ?? 'primary');
            $type = (string) ($options['type'] ?? $type);
        } elseif (is_array($variantOrOptions)) {
            $options = $variantOrOptions;
            $href = (string) ($hrefOrOptions ?? '');
            $variant = (string) ($options['variant'] ?? 'primary');
            $type = (string) ($options['type'] ?? $type);
        } else {
            $href = (string) ($hrefOrOptions ?? '');
            $variant = $variantOrOptions;
        }

        $safeVariant = preg_replace('/[^a-z0-9_-]/i', '', $variant) ?: 'primary';
        $class = 'kivo-action-btn kivo-action-btn--' . $safeVariant;
        if ($href !== '') {
            return '<a class="' . View::e($class) . '" href="/' . View::e(ltrim($href, '/')) . '">' . View::e($label) . '</a>';
        }
        return '<button class="' . View::e($class) . '" type="' . View::e($type) . '">' . View::e($label) . '</button>';
    }

    public static function badge(string $label, string $tone = 'neutral'): string
    {
        return '<span class="kivo-badge kivo-badge--' . View::e($tone) . '">' . View::e($label) . '</span>';
    }

    public static function emptyState(string $title, string $message = ''): string
    {
        return '<div class="kivo-empty-state"><strong>' . View::e($title) . '</strong>' . ($message !== '' ? '<p>' . View::e($message) . '</p>' : '') . '</div>';
    }
}
