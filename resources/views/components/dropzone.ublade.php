<?php
use App\View\Components\Form;
echo Form::dropzone($name ?? '', $label ?? 'Fichier', $attrs ?? []);
