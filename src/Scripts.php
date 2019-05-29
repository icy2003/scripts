<?php

namespace icy2003\scripts;

use icy2003\php\I;

class Scripts
{
    public static function load()
    {
        I::setAlias('@icy2003/scripts', __DIR__);
        I::setAlias('@icy2003/scripts_runtime', __DIR__ . '/../runtime');
    }
}
