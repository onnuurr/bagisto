<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.communications.campaigns.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.marketing.communications.campaigns.index.title')
        </x-slot>

        <x-slot:actions>
            @if (bouncer()->hasPermission('marketing.communications.campaigns.create'))
                <a href="{{ route('admin.marketing.communications.campaigns.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.marketing.communications.campaigns.index.create-btn')
                    </div>
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.list.before') !!}

    <x-admin::datagrid :src="route('admin.marketing.communications.campaigns.index')" />

    {!! view_render_event('bagisto.admin.marketing.communications.campaigns.list.after') !!}

</x-admin::layouts>
