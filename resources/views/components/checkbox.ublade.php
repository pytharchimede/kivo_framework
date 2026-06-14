<?php
use App\View\Components\Form;
echo Form::checkbox($name ?? '', $label ?? '', (bool) ($checked ?? false), $attrs ?? []);
