<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.promotions.catalog-rules.index.title')
    </x-slot>

    <x-admin::layouts.page-header class="mt-3">
        <x-slot:title>
            @lang('admin::app.marketing.promotions.catalog-rules.index.title')
        </x-slot>

        <x-slot:actions>
            @if (bouncer()->hasPermission('marketing.promotions.catalog_rules.create'))
                <a
                    href="{{ route('admin.marketing.promotions.catalog_rules.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.marketing.promotions.catalog-rules.index.create-btn')
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>
    
    {!! view_render_event('bagisto.admin.marketing.promotions.catalog_rules.list.before') !!}

    <x-admin::datagrid :src="route('admin.marketing.promotions.catalog_rules.index')" />

    {!! view_render_event('bagisto.admin.marketing.promotions.catalog_rules.list.after') !!}

</x-admin::layouts>