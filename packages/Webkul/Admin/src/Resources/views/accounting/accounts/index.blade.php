<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.accounts.index.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.accounting.accounts.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <x-admin::datagrid.export :src="route('admin.accounting.accounts.index')" />

            @if (bouncer()->hasPermission('accounting.accounts.create'))
                <a
                    href="{{ route('admin.accounting.accounts.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.accounting.accounts.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.accounting.accounts.list.before') !!}

    <x-admin::datagrid :src="route('admin.accounting.accounts.index')" />

    {!! view_render_event('bagisto.admin.accounting.accounts.list.after') !!}
</x-admin::layouts>
