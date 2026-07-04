<?php

namespace Webkul\Admin\Http\Controllers\Accounting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Accounting\Models\FiscalYear;
use Webkul\Accounting\Repositories\FiscalYearRepository;
use Webkul\Admin\DataGrids\Accounting\FiscalYearDataGrid;
use Webkul\Admin\Http\Controllers\Controller;

class FiscalYearController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected FiscalYearRepository $fiscalYearRepository) {}

    /**
     * Load the fiscal years index page.
     *
     * @return View|JsonResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(FiscalYearDataGrid::class)->process();
        }

        return view('admin::accounting.fiscal-years.index');
    }

    /**
     * Show the fiscal year creation form.
     */
    public function create(): View
    {
        return view('admin::accounting.fiscal-years.create');
    }

    /**
     * Store a newly created fiscal year.
     */
    public function store(): RedirectResponse
    {
        $this->validate(request(), [
            'code' => 'required|string|unique:accounting_fiscal_years,code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $this->fiscalYearRepository->create([
            'code' => request('code'),
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
            'status' => FiscalYear::STATUS_OPEN,
        ]);

        session()->flash('success', trans('admin::app.accounting.fiscal-years.create-success'));

        return redirect()->route('admin.accounting.fiscal_years.index');
    }

    /**
     * Close an open fiscal year.
     */
    public function close(int $id): RedirectResponse
    {
        $this->fiscalYearRepository->update(['status' => FiscalYear::STATUS_CLOSED], $id);

        session()->flash('success', trans('admin::app.accounting.fiscal-years.close-success'));

        return redirect()->route('admin.accounting.fiscal_years.index');
    }
}
