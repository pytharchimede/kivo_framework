<?php
use App\View\Components\Form;
echo Form::select($name ?? '', $label ?? '', $options ?? [], $selected ?? null, $attrs ?? []);
