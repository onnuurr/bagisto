<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.eu_withdrawal.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.eu_withdrawal.index.title')
        </x-slot>

        <x-slot:actions>
            <x-admin::datagrid.export src="{{ route('admin.sales.eu-withdrawals.index') }}" />
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.sales.eu_withdrawals.index.datagrid.before') !!}

    <x-admin::datagrid :src="route('admin.sales.eu-withdrawals.index')" />

    {!! view_render_event('bagisto.admin.sales.eu_withdrawals.index.datagrid.after') !!}
</x-admin::layouts>
