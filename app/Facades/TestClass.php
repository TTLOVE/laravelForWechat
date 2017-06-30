<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Test
 * @author xiaozhu
 */
class TestClass extends Facade
{   
    protected static function getFacadeAccessor()
    {
        return 'test';
    }
}
