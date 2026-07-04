<?php

namespace Webkul\Admin\Http\Controllers\Accounting;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Accounting\Repositories\AccountRepository;
use Webkul\Accounting\Repositories\SettingRepository;
use Webkul\Admin\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * The account mapping settings managed by this controller.
     *
     * @var string[]
     */
    protected array $mappings = [
        'accounts_receivable_account',
        'cash_bank_account',
        'sales_revenue_account',
        'shipping_revenue_account',
        'sales_discount_account',
        'sales_refund_account',
        'tax_payable_account',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SettingRepository $settingRepository,
        protected AccountRepository $accountRepository
    ) {}

    /**
     * Show the account mapping settings form.
     */
    public function edit(): View
    {
        $accounts = $this->accountRepository->getActiveAccounts();

        $settings = collect($this->mappings)->mapWithKeys(
            fn ($mapping) => [$mapping => $this->settingRepository->getValue($mapping)]
        );

        return view('admin::accounting.settings.edit', compact('accounts', 'settings'));
    }

    /**
     * Persist the account mapping settings.
     */
    public function update(): RedirectResponse
    {
        $this->validate(request(), collect($this->mappings)->mapWithKeys(
            fn ($mapping) => [$mapping => 'nullable|integer|exists:accounting_accounts,id']
        )->all());

        foreach ($this->mappings as $mapping) {
            $this->settingRepository->setValue($mapping, request($mapping));
        }

        session()->flash('success', trans('admin::app.accounting.settings.update-success'));

        return redirect()->route('admin.accounting.settings.edit');
    }
}
