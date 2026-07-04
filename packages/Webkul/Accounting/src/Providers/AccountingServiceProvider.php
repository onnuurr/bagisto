<?php

namespace Webkul\Accounting\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Accounting\Listeners\SalesEventSubscriber;

class AccountingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        Event::subscribe(SalesEventSubscriber::class);
    }
}
