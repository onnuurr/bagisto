<?php

namespace Webkul\GiftCard\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'checkout.order.save.after' => [
            'Webkul\GiftCard\Listeners\Order@manageGiftCard',
        ],

        'sales.order.cancel.after' => [
            'Webkul\GiftCard\Listeners\Order@refundGiftCard',
        ],

        'sales.refund.save.after' => [
            'Webkul\GiftCard\Listeners\Order@refundGiftCardFromRefund',
        ],
    ];
}
