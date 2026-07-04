<?php

namespace Webkul\Accounting\Repositories;

use Webkul\Core\Eloquent\Repository;

class AccountRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Accounting\Contracts\Account';
    }

    /**
     * Get the closing balance, in base currency, of the given account as of an optional date.
     *
     * Includes the account's opening balance, unlike {@see getMovement()}.
     */
    public function getBalance(int $accountId, ?string $asOfDate = null): float
    {
        $account = $this->find($accountId);

        return (float) $account->opening_balance + $this->getMovement($accountId, null, $asOfDate);
    }

    /**
     * Get the net movement, in base currency, posted to the given account within an optional
     * date range, signed so that a positive value means the account grew on its normal balance side.
     */
    public function getMovement(int $accountId, ?string $startDate = null, ?string $endDate = null): float
    {
        $account = $this->find($accountId);

        $query = $account->journalEntryLines()->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
            $query->where('status', 'posted');

            if ($startDate) {
                $query->where('entry_date', '>=', $startDate);
            }

            if ($endDate) {
                $query->where('entry_date', '<=', $endDate);
            }
        });

        $totalDebit = (clone $query)->sum('base_debit');

        $totalCredit = (clone $query)->sum('base_credit');

        return $account->hasDebitNormalBalance()
            ? $totalDebit - $totalCredit
            : $totalCredit - $totalDebit;
    }

    /**
     * Get all active accounts, ordered by code, for use in dropdowns.
     */
    public function getActiveAccounts()
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('code')
            ->get();
    }
}
