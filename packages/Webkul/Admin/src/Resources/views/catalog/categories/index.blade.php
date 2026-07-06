<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.categories.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.catalog.categories.index.title')
        </x-slot>

        <x-slot:actions>
            {!! view_render_event('bagisto.admin.catalog.categories.index.create-button.before') !!}

            @if (bouncer()->hasPermission('catalog.categories.create'))
                <a href="{{ route('admin.catalog.categories.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.catalog.categories.index.add-btn')
                    </div>
                </a>
            @endif

            {!! view_render_event('bagisto.admin.catalog.categories.index.create-button.after') !!}
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.catalog.categories.list.before') !!}

    <x-admin::datagrid :src="route('admin.catalog.categories.index')" />

    {!! view_render_event('bagisto.admin.catalog.categories.list.after') !!}

</x-admin::layouts>
