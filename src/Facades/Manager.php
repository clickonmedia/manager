<?php

namespace Clickonmedia\Manager\Facades;

use Illuminate\Support\Facades\Facade;

class Manager extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'manager';
    }
}
