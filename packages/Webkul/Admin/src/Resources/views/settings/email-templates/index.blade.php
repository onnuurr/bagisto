<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.email-templates.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.settings.email-templates.index.title')
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.settings.email_templates.list.before') !!}

    <x-admin::datagrid :src="route('admin.settings.email_templates.index')" />

    {!! view_render_event('bagisto.admin.settings.email_templates.list.after') !!}

</x-admin::layouts>
