<?php

namespace Webkul\Admin\Http\Controllers\Catalog;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Catalog\ProductTagDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\ProductTag\Repositories\ProductTagRepository;

class ProductTagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ProductTagRepository $productTagRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ProductTagDataGrid::class)->process();
        }

        return view('admin::catalog.product-tags.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin::catalog.product-tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required',
            'image' => 'array',
            'image.*' => 'mimes:bmp,jpeg,jpg,png,webp',
        ]);

        Event::dispatch('catalog.product_tag.create.before');

        $data = request()->only([
            'locale',
            'name',
            'image',
        ]);

        $productTag = $this->productTagRepository->create($data);

        Event::dispatch('catalog.product_tag.create.after', $productTag);

        session()->flash('success', trans('admin::app.catalog.product-tags.create.create-success'));

        return redirect()->route('admin.catalog.product_tags.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(int $id)
    {
        $productTag = $this->productTagRepository->findOrFail($id);

        return view('admin::catalog.product-tags.edit', compact('productTag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(int $id)
    {
        $locale = core()->getRequestedLocaleCode();

        $this->validate(request(), [
            $locale.'.name' => 'required',
            'image' => 'array',
            'image.*' => 'mimes:bmp,jpeg,jpg,png,webp',
        ]);

        Event::dispatch('catalog.product_tag.update.before', $id);

        $data = request()->only('image');

        $data[$locale] = request()->input($locale);

        $productTag = $this->productTagRepository->update($data, $id);

        Event::dispatch('catalog.product_tag.update.after', $productTag);

        session()->flash('success', trans('admin::app.catalog.product-tags.edit.update-success'));

        return redirect()->route('admin.catalog.product_tags.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            Event::dispatch('catalog.product_tag.delete.before', $id);

            $this->productTagRepository->delete($id);

            Event::dispatch('catalog.product_tag.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.catalog.product-tags.index.datagrid.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.catalog.product-tags.index.datagrid.delete-failed'),
            ], 500);
        }
    }

    /**
     * Remove the specified resources from database.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            Event::dispatch('catalog.product_tag.delete.before', $index);

            $this->productTagRepository->delete($index);

            Event::dispatch('catalog.product_tag.delete.after', $index);
        }

        return new JsonResponse([
            'message' => trans('admin::app.catalog.product-tags.index.datagrid.mass-delete-success'),
        ], 200);
    }
}
