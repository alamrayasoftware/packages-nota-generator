<?php

namespace ArsoftModules\NotaGenerator\Facades;

use Illuminate\Support\Facades\Facade;

class NotaGenerator extends Facade 
{
    protected static function getFacadeAccessor()
    {
        return 'notagenerator';
    }
}