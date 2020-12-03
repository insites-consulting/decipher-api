<?php

namespace InsitesConsulting\DecipherApi;

use Illuminate\Support\Facades\Facade;

class DecipherFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'decipher';
    }
}
