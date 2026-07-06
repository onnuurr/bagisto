<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.channels.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.settings.channels.index.title')
        </x-slot>

        <x-slot:actions>
            <!-- Create New Channel Button -->
            @if (bouncer()->hasPermission('settings.channels.create'))
                <a
                    href="{{ route('admin.settings.channels.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.settings.channels.index.create-btn')
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.settings.channels.list.before') !!}
    
    <x-admin::datagrid :src="route('admin.settings.channels.index')" />

    {!! view_render_event('bagisto.settings.channels.list.after') !!}

</x-admin::layouts>