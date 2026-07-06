<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.inventory-sources.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.settings.inventory-sources.index.title')
        </x-slot>

        <x-slot:actions>
            <!-- Create Button -->
            @if (bouncer()->hasPermission('settings.inventory_sources.create'))
                <a href="{{ route('admin.settings.inventory_sources.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.settings.inventory-sources.index.create-btn')
                    </div>
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.settings.inventory_sources.list.before') !!}

    <x-admin::datagrid :src="route('admin.settings.inventory_sources.index')" />

    {!! view_render_event('bagisto.admin.settings.inventory_sources.list.after') !!}

</x-admin::layouts>
