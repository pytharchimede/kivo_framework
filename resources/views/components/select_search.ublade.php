<?php
use App\View\Components\Form;
echo Form::selectSearch($name ?? '', $label ?? '', $options ?? [], $selected ?? null, ['placeholder' => $placeholder ?? 'Rechercher et sélectionner...'] + ($attrs ?? []));
