<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.sales.rma.custom-field.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.sales.rma.custom-field.index.title')
        </x-slot>

        <x-slot:actions>
            @if (bouncer()->hasPermission('sales.rma.custom-fields.create'))
                <a
                    class="primary-button"
                    href="{{ route('admin.sales.rma.custom-fields.create') }}"
                >
                    @lang('admin::app.sales.rma.custom-field.index.create-btn')
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.catalog.rma.custom-field.list.before') !!}

    <x-admin::datagrid :src="route('admin.sales.rma.custom-fields.index')"/>

    {!! view_render_event('bagisto.admin.catalog.rma.custom-field.list.after') !!}

</x-admin::layouts>