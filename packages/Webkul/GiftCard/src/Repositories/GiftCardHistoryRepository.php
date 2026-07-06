<?php

namespace Webkul\GiftCard\Repositories;

use Webkul\Core\Eloquent\Repository;

class GiftCardHistoryRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\GiftCard\Contracts\GiftCardHistory';
    }
}
