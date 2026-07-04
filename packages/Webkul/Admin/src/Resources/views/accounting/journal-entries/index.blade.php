<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.journal-entries.index.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.accounting.journal-entries.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <x-admin::datagrid.export :src="route('admin.accounting.journal_entries.index')" />

            @if (bouncer()->hasPermission('accounting.journal_entries.create'))
                <a
                    href="{{ route('admin.accounting.journal_entries.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.accounting.journal-entries.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.accounting.journal_entries.list.before') !!}

    <x-admin::datagrid :src="route('admin.accounting.journal_entries.index')" />

    {!! view_render_event('bagisto.admin.accounting.journal_entries.list.after') !!}
</x-admin::layouts>
