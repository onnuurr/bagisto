<?php

namespace Webkul\SMS\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('checkout.order.save.after', 'Webkul\SMS\Listeners\Order@orderPlaced');

        Event::listen('sales.order.cancel.after', 'Webkul\SMS\Listeners\Order@orderCancelled');

        Event::listen('sales.shipment.save.after', 'Webkul\SMS\Listeners\Order@shipmentCreated');

        Event::listen('sales.invoice.save.after', 'Webkul\SMS\Listeners\Order@invoiceCreated');

        Event::listen('sales.refund.save.after', 'Webkul\SMS\Listeners\Order@refundCreated');
    }
}
