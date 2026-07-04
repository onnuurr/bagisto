<?php

namespace Webkul\Admin\DataGrids\Accounting;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class JournalEntryDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('accounting_journal_entries')
            ->select(
                'id',
                'entry_number',
                'entry_date',
                'reference_type',
                'description',
                'currency_code',
                'status'
            )
            ->selectRaw('(select coalesce(sum(base_debit), 0) from '.DB::getTablePrefix().'accounting_journal_entry_lines where journal_entry_id = '.DB::getTablePrefix().'accounting_journal_entries.id) as total');

        $this->addFilter('id', 'accounting_journal_entries.id');
        $this->addFilter('entry_number', 'accounting_journal_entries.entry_number');
        $this->addFilter('status', 'accounting_journal_entries.status');
        $this->addFilter('reference_type', 'accounting_journal_entries.reference_type');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.accounting.journal-entries.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'entry_number',
            'label' => trans('admin::app.accounting.journal-entries.index.datagrid.entry-number'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'entry_date',
            'label' => trans('admin::app.accounting.journal-entries.index.datagrid.entry-date'),
            'type' => 'datetime',
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'reference_type',
            'label' => trans('admin::app.accounting.journal-entries.index.datagrid.reference-type'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => trans('admin::app.accounting.journal-entries.reference-types.manual'), 'value' => 'manual'],
                ['label' => trans('admin::app.accounting.journal-entries.reference-types.invoice'), 'value' => 'invoice'],
                ['label' => trans('admin::app.accounting.journal-entries.reference-types.refund'), 'value' => 'refund'],
            ],
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'description',
            'label' => trans('admin::app.accounting.journal-entries.index.datagrid.description'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => false,
        ]);

        $this->addColumn([
            'index' => 'total',
            'label' => trans('admin::app.accounting.journal-entries.index.datagrid.total'),
            'type' => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.accounting.journal-entries.index.datagrid.status'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => trans('admin::app.accounting.journal-entries.statuses.draft'), 'value' => 'draft'],
                ['label' => trans('admin::app.accounting.journal-entries.statuses.posted'), 'value' => 'posted'],
                ['label' => trans('admin::app.accounting.journal-entries.statuses.void'), 'value' => 'void'],
            ],
            'sortable' => true,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('accounting.journal_entries.view')) {
            $this->addAction([
                'icon' => 'icon-view',
                'title' => trans('admin::app.accounting.journal-entries.index.datagrid.view'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('admin.accounting.journal_entries.view', $row->id);
                },
            ]);
        }
    }
}
