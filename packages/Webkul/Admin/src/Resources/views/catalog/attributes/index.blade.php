<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.attributes.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.catalog.attributes.index.title')
        </x-slot>

        <x-slot:actions>
            @if (bouncer()->hasPermission('catalog.attributes.create'))
                <a href="{{ route('admin.catalog.attributes.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.catalog.attributes.index.create-btn')
                    </div>
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.catalog.attributes.list.before') !!}

    <x-admin::datagrid :src="route('admin.catalog.attributes.index')" />

    {!! view_render_event('bagisto.admin.catalog.attributes.list.after') !!}

</x-admin::layouts>
