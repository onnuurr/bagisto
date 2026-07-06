{!! view_render_event('bagisto.admin.catalog.product.edit.form.tag.before', ['product' => $product]) !!}

@php
    $productTagOptions = app(\Webkul\ProductTag\Repositories\ProductTagRepository::class)->all()
        ->map(fn ($productTag) => ['id' => (string) $productTag->id, 'label' => $productTag->name])
        ->values();
@endphp

<!-- Panel -->
<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <!-- Panel Header -->
    <p class="mb-4 flex justify-between text-base font-semibold text-gray-800 dark:text-white">
        @lang('admin::app.catalog.products.edit.tag.title')
    </p>

    {!! view_render_event('bagisto.admin.catalog.product.edit.form.tag.controls.before', ['product' => $product]) !!}

    <!-- Panel Content -->
    <div class="text-sm text-gray-600 dark:text-gray-300">
        <x-admin::form.control-group.advance.select
            name="product_tag_id"
            :options="$productTagOptions"
            :value="(string) $product->product_tag_id"
            :placeholder="trans('admin::app.catalog.products.edit.tag.title')"
            ::clearable="true"
        />
    </div>

    {!! view_render_event('bagisto.admin.catalog.product.edit.form.tag.controls.after', ['product' => $product]) !!}
</div>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.tag.after', ['product' => $product]) !!}
