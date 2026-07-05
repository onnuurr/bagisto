<?php

namespace Webkul\Admin\DataGrids\Marketing\Promotions;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\GiftCard\Models\GiftCard;

class GiftCardDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('gift_cards')
            ->select(
                'id',
                'code',
                'amount',
                'currency',
                'status',
                'expires_at',
                'used_at',
                'created_at'
            );

        $this->addFilter('id', 'gift_cards.id');
        $this->addFilter('code', 'gift_cards.code');

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
            'label' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.code'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'amount',
            'label' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.amount'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($row) {
                return core()->formatPrice($row->amount, $row->currency);
            },
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.status'),
            'type' => 'string',
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.unused'),
                    'value' => GiftCard::STATUS_UNUSED,
                ],
                [
                    'label' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.used'),
                    'value' => GiftCard::STATUS_USED,
                ],
                [
                    'label' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.expired'),
                    'value' => GiftCard::STATUS_EXPIRED,
                ],
            ],
            'sortable' => true,
            'closure' => function ($row) {
                return trans('admin::app.marketing.promotions.gift-cards.index.datagrid.'.$row->status);
            },
        ]);

        $this->addColumn([
            'index' => 'expires_at',
            'label' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.expires-at'),
            'type' => 'datetime',
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'sortable' => true,
            'closure' => function ($row) {
                return $row->expires_at ?? '-';
            },
        ]);

        $this->addColumn([
            'index' => 'used_at',
            'label' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.used-at'),
            'type' => 'datetime',
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'sortable' => true,
            'closure' => function ($row) {
                return $row->used_at ?? '-';
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('marketing.promotions.gift_cards.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.edit'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('admin.marketing.promotions.gift_cards.update', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.promotions.gift_cards.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.marketing.promotions.gift_cards.delete', $row->id);
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
        if (bouncer()->hasPermission('marketing.promotions.gift_cards.delete')) {
            $this->addMassAction([
                'title' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.delete'),
                'method' => 'POST',
                'url' => route('admin.marketing.promotions.gift_cards.mass_delete'),
            ]);
        }
    }
}
