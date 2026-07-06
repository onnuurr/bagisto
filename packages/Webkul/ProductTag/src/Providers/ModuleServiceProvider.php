<?php

namespace Webkul\ProductTag\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\ProductTag\Models\ProductTag;
use Webkul\ProductTag\Models\ProductTagTranslation;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        ProductTag::class,
        ProductTagTranslation::class,
    ];
}
