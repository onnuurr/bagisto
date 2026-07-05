<?php

namespace Webkul\GiftCard\Providers;

use Illuminate\Support\ServiceProvider;

class GiftCardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
