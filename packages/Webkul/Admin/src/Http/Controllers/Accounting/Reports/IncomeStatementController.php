<?php

namespace Webkul\Admin\Http\Controllers\Accounting\Reports;

use Illuminate\View\View;
use Webkul\Accounting\Models\Account;
use Webkul\Accounting\Repositories\AccountRepository;
use Webkul\Admin\Http\Controllers\Controller;

class IncomeStatementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected AccountRepository $accountRepository) {}

    /**
     * Display the income statement for the requested period.
     */
    public function index(): View
    {
        $startDate = request('start_date', now()->startOfMonth()->format('Y-m-d'));

        $endDate = request('end_date', now()->format('Y-m-d'));

        $accounts = $this->accountRepository->getActiveAccounts();

        $revenues = $this->rowsForType($accounts, Account::TYPE_REVENUE, $startDate, $endDate);

        $expenses = $this->rowsForType($accounts, Account::TYPE_EXPENSE, $startDate, $endDate);

        $totalRevenue = $revenues->sum('amount');

        $totalExpense = $expenses->sum('amount');

        $netIncome = $totalRevenue - $totalExpense;

        return view('admin::accounting.reports.income-statement', compact(
            'revenues',
            'expenses',
            'totalRevenue',
            'totalExpense',
            'netIncome',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Build the report rows for a given account type over the period.
     */
    protected function rowsForType($accounts, string $type, string $startDate, string $endDate)
    {
        return $accounts
            ->where('type', $type)
            ->map(fn ($account) => [
                'account' => $account,
                'amount' => $this->accountRepository->getMovement($account->id, $startDate, $endDate),
            ])
            ->filter(fn ($row) => (float) $row['amount'] !== 0.0)
            ->values();
    }
}
