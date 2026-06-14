<?php
use App\View\Components\Ui;
echo Ui::section($title ?? '', $content ?? '', $subtitle ?? '', $attrs ?? []);
