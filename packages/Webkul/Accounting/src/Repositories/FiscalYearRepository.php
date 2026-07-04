<?php

namespace Webkul\Accounting\Repositories;

use Webkul\Core\Eloquent\Repository;

class FiscalYearRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Accounting\Contracts\FiscalYear';
    }

    /**
     * Find the open fiscal year that the given date falls into, if any.
     */
    public function findForDate(string $date)
    {
        return $this->model
            ->where('status', 'open')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();
    }
}
