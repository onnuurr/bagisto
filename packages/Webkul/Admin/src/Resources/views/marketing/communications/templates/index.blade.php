<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.communications.templates.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.marketing.communications.templates.index.title')
        </x-slot>

        <x-slot:actions>
            @if (bouncer()->hasPermission('marketing.communications.email_templates.create'))
                <a href="{{ route('admin.marketing.communications.email_templates.create') }}">
                    <div class="primary-button">
                        @lang('admin::app.marketing.communications.templates.index.create-btn')
                    </div>
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.marketing.communications.templates.list.before') !!}

    <x-admin::datagrid :src="route('admin.marketing.communications.email_templates.index')" />

    {!! view_render_event('bagisto.admin.marketing.communications.templates.list.after') !!}

</x-admin::layouts>
