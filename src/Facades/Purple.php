<?php

namespace Purple\Anbu\Facades;

use Illuminate\Support\Facades\Facade;

class Purple extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'purple';
    }
}