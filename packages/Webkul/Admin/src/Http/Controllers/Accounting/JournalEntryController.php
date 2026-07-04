<?php

namespace Webkul\Admin\Http\Controllers\Accounting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Accounting\Repositories\AccountRepository;
use Webkul\Accounting\Repositories\JournalEntryRepository;
use Webkul\Admin\DataGrids\Accounting\JournalEntryDataGrid;
use Webkul\Admin\Http\Controllers\Controller;

class JournalEntryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected JournalEntryRepository $journalEntryRepository,
        protected AccountRepository $accountRepository
    ) {}

    /**
     * Load the journal entries index page.
     *
     * @return View|JsonResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(JournalEntryDataGrid::class)->process();
        }

        return view('admin::accounting.journal-entries.index');
    }

    /**
     * Show the journal entry creation form.
     */
    public function create(): View
    {
        $accounts = $this->accountRepository->getActiveAccounts();

        return view('admin::accounting.journal-entries.create', compact('accounts'));
    }

    /**
     * Store a newly created, balanced journal entry.
     */
    public function store(): RedirectResponse
    {
        $this->validate(request(), [
            'entry_date' => 'required|date',
            'description' => 'nullable|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|integer|exists:accounting_accounts,id',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
            'lines.*.description' => 'nullable|string',
        ]);

        try {
            $journalEntry = $this->journalEntryRepository->create([
                'entry_date' => request('entry_date'),
                'description' => request('description'),
                'currency_code' => core()->getBaseCurrencyCode(),
                'status' => request('action') === 'post' ? 'posted' : 'draft',
                'created_by' => auth()->guard('admin')->id(),
                'lines' => request('lines'),
            ]);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->back()->withInput();
        }

        session()->flash('success', trans('admin::app.accounting.journal-entries.create-success'));

        return redirect()->route('admin.accounting.journal_entries.view', $journalEntry->id);
    }

    /**
     * View a single journal entry with its lines.
     */
    public function view(int $id): View
    {
        $journalEntry = $this->journalEntryRepository
            ->getModel()
            ->with(['lines.account', 'fiscalYear'])
            ->findOrFail($id);

        return view('admin::accounting.journal-entries.view', compact('journalEntry'));
    }

    /**
     * Post a draft journal entry.
     */
    public function post(int $id): RedirectResponse
    {
        try {
            $this->journalEntryRepository->post($id);

            session()->flash('success', trans('admin::app.accounting.journal-entries.post-success'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->route('admin.accounting.journal_entries.view', $id);
    }

    /**
     * Void a posted journal entry.
     */
    public function void(int $id): RedirectResponse
    {
        try {
            $this->journalEntryRepository->void($id);

            session()->flash('success', trans('admin::app.accounting.journal-entries.void-success'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->route('admin.accounting.journal_entries.view', $id);
    }
}
