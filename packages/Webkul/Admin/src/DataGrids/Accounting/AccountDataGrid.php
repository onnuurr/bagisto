<?php

namespace Webkul\Admin\DataGrids\Accounting;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class AccountDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('accounting_accounts')
            ->select(
                'accounting_accounts.id',
                'accounting_accounts.code',
                'accounting_accounts.name',
                'accounting_accounts.type',
                'accounting_accounts.opening_balance',
                'accounting_accounts.is_active',
                'parent.name as parent_name'
            )
            ->leftJoin('accounting_accounts as parent', 'accounting_accounts.parent_id', '=', 'parent.id');

        $this->addFilter('id', 'accounting_accounts.id');
        $this->addFilter('code', 'accounting_accounts.code');
        $this->addFilter('type', 'accounting_accounts.type');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.accounting.accounts.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('admin::app.accounting.accounts.index.datagrid.code'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.accounting.accounts.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'parent_name',
            'label' => trans('admin::app.accounting.accounts.index.datagrid.parent'),
            'type' => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => trans('admin::app.accounting.accounts.index.datagrid.type'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => trans('admin::app.accounting.accounts.types.asset'), 'value' => 'asset'],
                ['label' => trans('admin::app.accounting.accounts.types.liability'), 'value' => 'liability'],
                ['label' => trans('admin::app.accounting.accounts.types.equity'), 'value' => 'equity'],
                ['label' => trans('admin::app.accounting.accounts.types.revenue'), 'value' => 'revenue'],
                ['label' => trans('admin::app.accounting.accounts.types.expense'), 'value' => 'expense'],
            ],
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'opening_balance',
            'label' => trans('admin::app.accounting.accounts.index.datagrid.opening-balance'),
            'type' => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'is_active',
            'label' => trans('admin::app.accounting.accounts.index.datagrid.status'),
            'type' => 'boolean',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => trans('admin::app.accounting.accounts.index.datagrid.active'), 'value' => 1],
                ['label' => trans('admin::app.accounting.accounts.index.datagrid.inactive'), 'value' => 0],
            ],
            'sortable' => true,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('accounting.accounts.edit')) {
            $this->addAction([
                'icon' => 'icon-edit',
                'title' => trans('admin::app.accounting.accounts.index.datagrid.edit'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('admin.accounting.accounts.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('accounting.accounts.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.accounting.accounts.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.accounting.accounts.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('accounting.accounts.delete')) {
            $this->addMassAction([
                'title' => trans('admin::app.accounting.accounts.index.datagrid.delete'),
                'method' => 'POST',
                'url' => route('admin.accounting.accounts.mass_delete'),
            ]);
        }
    }
}
