<?php

namespace Webkul\Admin\Http\Controllers\Accounting;

use Illuminate\View\View;
use Webkul\Accounting\Repositories\AccountRepository;
use Webkul\Admin\Http\Controllers\Controller;

class LedgerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected AccountRepository $accountRepository) {}

    /**
     * Display the ledger (running balance) for the requested account.
     */
    public function index(): View
    {
        $accounts = $this->accountRepository->getActiveAccounts();

        $account = null;

        $transactions = collect();

        $openingBalance = 0;

        $closingBalance = 0;

        if ($accountId = request('account_id')) {
            $account = $this->accountRepository->find($accountId);
        }

        if ($account) {
            $startDate = request('start_date');

            $endDate = request('end_date');

            $openingBalance = $startDate
                ? $this->accountRepository->getBalance($account->id, date('Y-m-d', strtotime($startDate.' -1 day')))
                : (float) $account->opening_balance;

            $lines = $account->journalEntryLines()
                ->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'posted');

                    if ($startDate) {
                        $query->where('entry_date', '>=', $startDate);
                    }

                    if ($endDate) {
                        $query->where('entry_date', '<=', $endDate);
                    }
                })
                ->with('journalEntry')
                ->get()
                ->sortBy(fn ($line) => [$line->journalEntry->entry_date, $line->id])
                ->values();

            $runningBalance = $openingBalance;

            $isDebitNormal = $account->hasDebitNormalBalance();

            $transactions = $lines->map(function ($line) use (&$runningBalance, $isDebitNormal) {
                $runningBalance += $isDebitNormal
                    ? ((float) $line->base_debit - (float) $line->base_credit)
                    : ((float) $line->base_credit - (float) $line->base_debit);

                return [
                    'date'    => $line->journalEntry->entry_date,
                    'number'  => $line->journalEntry->entry_number,
                    'description' => $line->description ?: $line->journalEntry->description,
                    'debit'   => (float) $line->base_debit,
                    'credit'  => (float) $line->base_credit,
                    'balance' => $runningBalance,
                ];
            });

            $closingBalance = $runningBalance;
        }

        return view('admin::accounting.ledger.index', compact(
            'accounts',
            'account',
            'transactions',
            'openingBalance',
            'closingBalance'
        ));
    }
}
