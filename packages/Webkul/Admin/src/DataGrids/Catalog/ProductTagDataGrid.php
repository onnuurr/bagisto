<?php

namespace Webkul\Admin\DataGrids\Catalog;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\DataGrid\DataGrid;

class ProductTagDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $primaryColumn = 'product_tag_id';

    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_tags')
            ->select(
                'product_tags.id as product_tag_id',
                'product_tags.image',
                'product_tag_translations.name',
            )
            ->leftJoin('product_tag_translations', function ($join) {
                $join->on('product_tags.id', '=', 'product_tag_translations.product_tag_id')
                    ->where('product_tag_translations.locale', '=', app()->getLocale());
            })
            ->groupBy('product_tags.id');

        $this->addFilter('product_tag_id', 'product_tags.id');

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
            'index' => 'product_tag_id',
            'label' => trans('admin::app.catalog.product-tags.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.catalog.product-tags.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'image',
            'label' => trans('admin::app.catalog.product-tags.index.datagrid.image'),
            'type' => 'string',
            'sortable' => false,
            'filterable' => false,
            'exportable' => false,
            'closure' => function ($row) {
                if (! $row->image) {
                    return;
                }

                return Storage::url($row->image);
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
        if (bouncer()->hasPermission('catalog.product_tags.edit')) {
            $this->addAction([
                'icon' => 'icon-edit',
                'title' => trans('admin::app.catalog.product-tags.index.datagrid.edit'),
                'method' => 'GET',
                'url' => function ($row) {
                    return route('admin.catalog.product_tags.edit', $row->product_tag_id);
                },
            ]);
        }

        if (bouncer()->hasPermission('catalog.product_tags.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.catalog.product-tags.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.catalog.product_tags.delete', $row->product_tag_id);
                },
            ]);
        }

        if (bouncer()->hasPermission('catalog.product_tags.delete')) {
            $this->addMassAction([
                'title' => trans('admin::app.catalog.product-tags.index.datagrid.delete'),
                'method' => 'POST',
                'url' => route('admin.catalog.product_tags.mass_delete'),
            ]);
        }
    }
}
