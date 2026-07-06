<?php

namespace Webkul\GiftCard\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\GiftCard\Models\GiftCard;
use Webkul\GiftCard\Models\GiftCardHistory;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        GiftCard::class,
        GiftCardHistory::class,
    ];
}
