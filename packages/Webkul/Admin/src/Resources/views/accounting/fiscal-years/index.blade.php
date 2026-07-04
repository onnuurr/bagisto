<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.fiscal-years.index.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.accounting.fiscal-years.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            @if (bouncer()->hasPermission('accounting.fiscal_years.create'))
                <a
                    href="{{ route('admin.accounting.fiscal_years.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.accounting.fiscal-years.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.accounting.fiscal_years.index')" />
</x-admin::layouts>
