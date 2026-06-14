<?php
namespace Core\View;
final class Layout{public static function render(string $layout,array $vars):string{extract($vars,EXTR_SKIP); ob_start(); require BASE_PATH.'/resources/views/'.str_replace('.','/',$layout).'.ublade.php'; return ob_get_clean();}}
