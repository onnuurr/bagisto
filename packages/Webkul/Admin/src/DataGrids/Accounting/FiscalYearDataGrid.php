<?php

namespace Webkul\Admin\DataGrids\Accounting;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class FiscalYearDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('accounting_fiscal_years')
            ->select('id', 'code', 'start_date', 'end_date', 'status');

        $this->addFilter('id', 'accounting_fiscal_years.id');
        $this->addFilter('code', 'accounting_fiscal_years.code');
        $this->addFilter('status', 'accounting_fiscal_years.status');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.accounting.fiscal-years.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('admin::app.accounting.fiscal-years.index.datagrid.code'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'start_date',
            'label' => trans('admin::app.accounting.fiscal-years.index.datagrid.start-date'),
            'type' => 'datetime',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'end_date',
            'label' => trans('admin::app.accounting.fiscal-years.index.datagrid.end-date'),
            'type' => 'datetime',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.accounting.fiscal-years.index.datagrid.status'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => trans('admin::app.accounting.fiscal-years.statuses.open'), 'value' => 'open'],
                ['label' => trans('admin::app.accounting.fiscal-years.statuses.closed'), 'value' => 'closed'],
            ],
            'sortable' => true,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('accounting.fiscal_years.close')) {
            $this->addAction([
                'icon' => 'icon-done',
                'title' => trans('admin::app.accounting.fiscal-years.index.datagrid.close'),
                'method' => 'POST',
                'url' => function ($row) {
                    return route('admin.accounting.fiscal_years.close', $row->id);
                },
            ]);
        }
    }
}
