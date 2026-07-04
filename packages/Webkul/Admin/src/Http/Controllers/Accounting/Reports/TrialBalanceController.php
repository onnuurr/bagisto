<?php

namespace Webkul\Admin\Http\Controllers\Accounting\Reports;

use Illuminate\View\View;
use Webkul\Accounting\Repositories\AccountRepository;
use Webkul\Admin\Http\Controllers\Controller;

class TrialBalanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected AccountRepository $accountRepository) {}

    /**
     * Display the trial balance as of the requested date.
     */
    public function index(): View
    {
        $asOfDate = request('as_of_date', now()->format('Y-m-d'));

        $totalDebit = 0;

        $totalCredit = 0;

        $rows = $this->accountRepository->getActiveAccounts()->map(function ($account) use ($asOfDate, &$totalDebit, &$totalCredit) {
            $balance = $this->accountRepository->getBalance($account->id, $asOfDate);

            // A positive balance sits on the account's own normal side; a negative
            // (contra) balance sits on the opposite side, shown as a positive amount.
            $onDebitSide = $account->hasDebitNormalBalance() ? $balance >= 0 : $balance < 0;

            $debit = $onDebitSide ? abs($balance) : 0;

            $credit = $onDebitSide ? 0 : abs($balance);

            $totalDebit += $debit;

            $totalCredit += $credit;

            return [
                'account' => $account,
                'debit' => $debit,
                'credit' => $credit,
            ];
        })->filter(fn ($row) => $row['debit'] || $row['credit'])->values();

        return view('admin::accounting.reports.trial-balance', compact('rows', 'asOfDate', 'totalDebit', 'totalCredit'));
    }
}
