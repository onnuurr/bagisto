<?php

namespace Webkul\Admin\DataGrids\SMS;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SmsLogDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('sms_logs')
            ->select(
                'id',
                'gateway',
                'event',
                'recipient',
                'message',
                'status',
                'created_at'
            );

        $this->addFilter('id', 'sms_logs.id');
        $this->addFilter('gateway', 'sms_logs.gateway');
        $this->addFilter('event', 'sms_logs.event');
        $this->addFilter('recipient', 'sms_logs.recipient');
        $this->addFilter('status', 'sms_logs.status');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.sms.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'gateway',
            'label' => trans('admin::app.sms.index.datagrid.gateway'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'event',
            'label' => trans('admin::app.sms.index.datagrid.event'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'recipient',
            'label' => trans('admin::app.sms.index.datagrid.recipient'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'message',
            'label' => trans('admin::app.sms.index.datagrid.message'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => false,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.sms.index.datagrid.status'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => 'Sent', 'value' => 'sent'],
                ['label' => 'Failed', 'value' => 'failed'],
            ],
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.sms.index.datagrid.created-at'),
            'type' => 'datetime',
            'sortable' => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('sms.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.sms.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.sms.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('sms.delete')) {
            $this->addMassAction([
                'title' => trans('admin::app.sms.index.datagrid.delete'),
                'method' => 'POST',
                'url' => route('admin.sms.mass_delete'),
            ]);
        }
    }
}
