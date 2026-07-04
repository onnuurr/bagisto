<?php

namespace Webkul\Accounting\Providers;

use Webkul\Accounting\Models\Account;
use Webkul\Accounting\Models\FiscalYear;
use Webkul\Accounting\Models\JournalEntry;
use Webkul\Accounting\Models\JournalEntryLine;
use Webkul\Accounting\Models\Setting;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Account::class,
        FiscalYear::class,
        JournalEntry::class,
        JournalEntryLine::class,
        Setting::class,
    ];
}
