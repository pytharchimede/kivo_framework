<?php
use App\View\Components\Form;
echo Form::input($name ?? '', $label ?? '', $value ?? '', $attrs ?? []);
