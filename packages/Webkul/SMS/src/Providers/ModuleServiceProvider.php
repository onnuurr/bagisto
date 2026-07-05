<?php

namespace Webkul\SMS\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\SMS\Models\SmsLog;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        SmsLog::class,
    ];
}
