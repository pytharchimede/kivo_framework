<?php
use App\View\Components\Form;
echo Form::textarea($name ?? '', $label ?? '', $value ?? '', $attrs ?? []);
