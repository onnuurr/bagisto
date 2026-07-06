<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.promotions.cart-rules.index.title')
    </x-slot>

    <x-admin::layouts.page-header class="mt-3">
        <x-slot:title>
            @lang('admin::app.marketing.promotions.cart-rules.index.title')
        </x-slot>

        <x-slot:actions>
            @if (bouncer()->hasPermission('marketing.promotions.cart_rules.create'))
                <a
                    href="{{ route('admin.marketing.promotions.cart_rules.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.marketing.promotions.cart-rules.index.create-btn')
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>
    
    {!! view_render_event('bagisto.admin.marketing.promotions.cart-rules.list.before') !!}

    <x-admin::datagrid :src="route('admin.marketing.promotions.cart_rules.index')" />

    {!! view_render_event('bagisto.admin.marketing.promotions.cart-rules.list.after') !!}

</x-admin::layouts>