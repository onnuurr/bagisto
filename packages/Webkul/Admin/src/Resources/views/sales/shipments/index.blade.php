<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.shipments.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.sales.shipments.index.title')
        </x-slot>

        <x-slot:actions>
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.sales.shipments.index')" />
        </x-slot>
    </x-admin::layouts.page-header>

    <x-admin::datagrid :src="route('admin.sales.shipments.index')" />

</x-admin::layouts>
