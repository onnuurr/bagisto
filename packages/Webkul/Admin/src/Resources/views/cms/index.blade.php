<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.cms.index.title')
    </x-slot>

    <x-admin::layouts.page-header>
        <x-slot:title>
            @lang('admin::app.cms.index.title')
        </x-slot>

        <x-slot:actions>
            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.cms.index')" />

            <!-- Create New Pages Button -->
            @if (bouncer()->hasPermission('cms.create'))
                <a
                    href="{{ route('admin.cms.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.cms.index.create-btn')
                </a>
            @endif
        </x-slot>
    </x-admin::layouts.page-header>

    {!! view_render_event('bagisto.admin.cms.pages.list.before') !!}

    <x-admin::datagrid :src="route('admin.cms.index')" />
    
    {!! view_render_event('bagisto.admin.cms.pages.list.after') !!}

</x-admin::layouts>
