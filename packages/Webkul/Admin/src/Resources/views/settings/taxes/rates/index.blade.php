<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.taxes.rates.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.settings.taxes.rates.index.title')
        </x-slot>

        <x-slot:actions>
            <!-- Tax Rate Export -->
            <x-admin::datagrid.export src="{{ route('admin.settings.taxes.rates.index') }}" />

            <!-- Create New Tax Rate Button -->
            @if (bouncer()->hasPermission('settings.taxes.tax_rates.create'))
                <a href="{{ route('admin.settings.taxes.rates.create') }}" class="primary-button">
                    @lang('admin::app.settings.taxes.rates.index.button-title')
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    <x-admin::datagrid
        :src="route('admin.settings.taxes.rates.index')"
        ref="datagrid"
    />
</x-admin::layouts>
