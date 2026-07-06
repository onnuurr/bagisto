<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.data-transfer.imports.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.settings.data-transfer.imports.index.title')
        </x-slot>

        <x-slot:actions>
            <!-- Create New Tax Rate Button -->
            @if (bouncer()->hasPermission('settings.data_transfer.imports.create'))
                <a href="{{ route('admin.settings.data_transfer.imports.create') }}" class="primary-button">
                    @lang('admin::app.settings.data-transfer.imports.index.button-title')
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    <x-admin::datagrid :src="route('admin.settings.data_transfer.imports.index')"/>
</x-admin::layouts>
