<?php

namespace YWatchman\LaravelEPP;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Epp';
    }
}
