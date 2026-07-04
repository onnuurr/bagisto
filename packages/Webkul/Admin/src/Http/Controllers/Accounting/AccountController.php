<?php

namespace Webkul\Admin\Http\Controllers\Accounting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Accounting\Models\Account;
use Webkul\Accounting\Repositories\AccountRepository;
use Webkul\Admin\DataGrids\Accounting\AccountDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected AccountRepository $accountRepository) {}

    /**
     * Load the chart of accounts index page.
     *
     * @return View|JsonResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(AccountDataGrid::class)->process();
        }

        return view('admin::accounting.accounts.index');
    }

    /**
     * Show the account creation form.
     */
    public function create(): View
    {
        $accounts = $this->accountRepository->getActiveAccounts();

        $types = $this->types();

        return view('admin::accounting.accounts.create', compact('accounts', 'types'));
    }

    /**
     * Store a newly created account.
     */
    public function store(): RedirectResponse
    {
        $this->validate(request(), [
            'code' => 'required|string|unique:accounting_accounts,code',
            'name' => 'required|string',
            'type' => 'required|in:'.implode(',', array_keys($this->types())),
            'parent_id' => 'nullable|integer|exists:accounting_accounts,id',
            'opening_balance' => 'nullable|numeric',
        ]);

        $this->accountRepository->create(request()->only([
            'code',
            'name',
            'type',
            'parent_id',
            'description',
            'opening_balance',
            'is_active',
        ]));

        session()->flash('success', trans('admin::app.accounting.accounts.create-success'));

        return redirect()->route('admin.accounting.accounts.index');
    }

    /**
     * Show the account edit form.
     */
    public function edit(int $id): View
    {
        $account = $this->accountRepository->findOrFail($id);

        $accounts = $this->accountRepository->getActiveAccounts()->reject(fn ($item) => $item->id === $account->id);

        $types = $this->types();

        return view('admin::accounting.accounts.edit', compact('account', 'accounts', 'types'));
    }

    /**
     * Update the previously created account.
     */
    public function update(int $id): RedirectResponse
    {
        $this->validate(request(), [
            'code' => 'required|string|unique:accounting_accounts,code,'.$id,
            'name' => 'required|string',
            'type' => 'required|in:'.implode(',', array_keys($this->types())),
            'parent_id' => 'nullable|integer|exists:accounting_accounts,id|not_in:'.$id,
            'opening_balance' => 'nullable|numeric',
        ]);

        $this->accountRepository->update(request()->only([
            'code',
            'name',
            'type',
            'parent_id',
            'description',
            'opening_balance',
            'is_active',
        ]), $id);

        session()->flash('success', trans('admin::app.accounting.accounts.update-success'));

        return redirect()->route('admin.accounting.accounts.index');
    }

    /**
     * Delete the given account, provided it isn't a protected system account or in use.
     */
    public function delete(int $id): JsonResponse
    {
        $account = $this->accountRepository->findOrFail($id);

        if ($account->is_system) {
            return new JsonResponse(['message' => trans('admin::app.accounting.accounts.system-account-error')], 400);
        }

        if ($account->children()->exists() || $account->journalEntryLines()->exists()) {
            return new JsonResponse(['message' => trans('admin::app.accounting.accounts.in-use-error')], 400);
        }

        $this->accountRepository->delete($id);

        return new JsonResponse(['message' => trans('admin::app.accounting.accounts.delete-success')]);
    }

    /**
     * Mass delete the given accounts.
     */
    public function massDelete(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        foreach ($massDestroyRequest->input('indices') as $id) {
            $account = $this->accountRepository->find($id);

            if (
                ! $account
                || $account->is_system
                || $account->children()->exists()
                || $account->journalEntryLines()->exists()
            ) {
                continue;
            }

            $this->accountRepository->delete($id);
        }

        return new JsonResponse(['message' => trans('admin::app.accounting.accounts.delete-success')]);
    }

    /**
     * The account types available for the account form.
     */
    protected function types(): array
    {
        return [
            Account::TYPE_ASSET => trans('admin::app.accounting.accounts.types.asset'),
            Account::TYPE_LIABILITY => trans('admin::app.accounting.accounts.types.liability'),
            Account::TYPE_EQUITY => trans('admin::app.accounting.accounts.types.equity'),
            Account::TYPE_REVENUE => trans('admin::app.accounting.accounts.types.revenue'),
            Account::TYPE_EXPENSE => trans('admin::app.accounting.accounts.types.expense'),
        ];
    }
}
