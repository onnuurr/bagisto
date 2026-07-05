<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sms.index.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.sms.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.sms.index')" />
        </div>
    </div>

    {!! view_render_event('bagisto.admin.sms.list.before') !!}

    <x-admin::datagrid :src="route('admin.sms.index')" />

    {!! view_render_event('bagisto.admin.sms.list.after') !!}
</x-admin::layouts>
