<?php

namespace Webkul\SMS\Repositories;

use Webkul\Core\Eloquent\Repository;

class SmsLogRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\SMS\Contracts\SmsLog';
    }
}
