<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.email-templates.index.title')
    </x-slot>

    <div class="flex justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.settings.email-templates.index.title')
        </p>
    </div>

    {!! view_render_event('bagisto.admin.settings.email_templates.list.before') !!}

    <x-admin::datagrid :src="route('admin.settings.email_templates.index')" />

    {!! view_render_event('bagisto.admin.settings.email_templates.list.after') !!}

</x-admin::layouts>
