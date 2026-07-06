<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.sales.rma.all-rma.index.title')
    </x-slot:title>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.sales.rma.index.rma-title')
        </x-slot>

        <x-slot:actions>
            @if (bouncer()->hasPermission('sales.rma.requests.create'))
                <a
                    href="{{ route('admin.sales.rma.requests.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.sales.rma.index.create-rma-title')
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.rma.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.sales.rma.requests.index') }}" />

    {!! view_render_event('bagisto.admin.rma.list.after') !!}

</x-admin::layouts>
