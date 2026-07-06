<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sms.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.sms.index.title')
        </x-slot>

        <x-slot:actions>
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.sms.index')" />
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.sms.list.before') !!}

    <x-admin::datagrid :src="route('admin.sms.index')" />

    {!! view_render_event('bagisto.admin.sms.list.after') !!}
</x-admin::layouts>
