<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.product-tags.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.catalog.product-tags.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            {!! view_render_event('bagisto.admin.catalog.product_tags.index.create-button.before') !!}

            @if (bouncer()->hasPermission('catalog.product_tags.create'))
                <a href="{{ route('admin.catalog.product_tags.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.catalog.product-tags.index.add-btn')
                    </div>
                </a>
            @endif

            {!! view_render_event('bagisto.admin.catalog.product_tags.index.create-button.after') !!}
        </div>
    </div>

    {!! view_render_event('bagisto.admin.catalog.product_tags.list.before') !!}

    <x-admin::datagrid :src="route('admin.catalog.product_tags.index')" />

    {!! view_render_event('bagisto.admin.catalog.product_tags.list.after') !!}

</x-admin::layouts>
