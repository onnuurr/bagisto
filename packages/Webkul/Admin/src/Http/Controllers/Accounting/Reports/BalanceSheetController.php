<?php

namespace Webkul\Admin\Http\Controllers\Accounting\Reports;

use Illuminate\View\View;
use Webkul\Accounting\Models\Account;
use Webkul\Accounting\Repositories\AccountRepository;
use Webkul\Admin\Http\Controllers\Controller;

class BalanceSheetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected AccountRepository $accountRepository) {}

    /**
     * Display the balance sheet as of the requested date.
     */
    public function index(): View
    {
        $asOfDate = request('as_of_date', now()->format('Y-m-d'));

        $accounts = $this->accountRepository->getActiveAccounts();

        $assets = $this->rowsForType($accounts, Account::TYPE_ASSET, $asOfDate);

        $liabilities = $this->rowsForType($accounts, Account::TYPE_LIABILITY, $asOfDate);

        $equity = $this->rowsForType($accounts, Account::TYPE_EQUITY, $asOfDate);

        $totalAssets = $assets->sum('amount');

        $totalLiabilities = $liabilities->sum('amount');

        $totalEquity = $equity->sum('amount');

        // Retained earnings for the current, not-yet-closed period: revenue minus
        // expense, up to the report date, across all time.
        $currentEarnings = $accounts->where('type', Account::TYPE_REVENUE)->sum(
            fn ($account) => $this->accountRepository->getMovement($account->id, null, $asOfDate)
        ) - $accounts->where('type', Account::TYPE_EXPENSE)->sum(
            fn ($account) => $this->accountRepository->getMovement($account->id, null, $asOfDate)
        );

        $totalEquity += $currentEarnings;

        return view('admin::accounting.reports.balance-sheet', compact(
            'assets',
            'liabilities',
            'equity',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'currentEarnings',
            'asOfDate'
        ));
    }

    /**
     * Build the report rows for a given account type as of the given date.
     */
    protected function rowsForType($accounts, string $type, string $asOfDate)
    {
        return $accounts
            ->where('type', $type)
            ->map(fn ($account) => [
                'account' => $account,
                'amount' => $this->accountRepository->getBalance($account->id, $asOfDate),
            ])
            ->filter(fn ($row) => (float) $row['amount'] !== 0.0)
            ->values();
    }
}
