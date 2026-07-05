<?php

namespace Webkul\SMS\Facades;

use Illuminate\Support\Facades\Facade;
use Webkul\SMS\SmsManager;

class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SmsManager::class;
    }
}
