<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sales.refunds.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.sales.refunds.index.title')
        </x-slot>

        <x-slot:actions>
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.sales.refunds.index')" />
        </x-slot>
    </x-admin::layouts.page-header>

    <x-admin::datagrid :src="route('admin.sales.refunds.index')" />
</x-admin::layouts>
