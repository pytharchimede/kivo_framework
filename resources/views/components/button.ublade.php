<?php
use App\View\Components\Ui;
echo Ui::button($label ?? '', $href ?? '', $variant ?? 'primary', $type ?? 'button');
