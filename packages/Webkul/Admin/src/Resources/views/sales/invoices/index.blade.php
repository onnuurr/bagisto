<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.invoices.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.sales.invoices.index.title')
        </x-slot>

        <x-slot:actions>
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.sales.invoices.index')" />
        </x-slot>
    </x-admin::layouts.page-header>

    <x-admin::datagrid :src="route('admin.sales.invoices.index')" />

</x-admin::layouts>
