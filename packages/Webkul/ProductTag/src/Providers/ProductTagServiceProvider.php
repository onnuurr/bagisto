<?php

namespace Webkul\ProductTag\Providers;

use Illuminate\Support\ServiceProvider;

class ProductTagServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
