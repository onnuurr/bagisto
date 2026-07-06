<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.product-tags.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.catalog.product_tags.create.before') !!}

    <!-- Product Tag Create Form -->
    <x-admin::form
        :action="route('admin.catalog.product_tags.store')"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.catalog.product_tags.create.create_form_controls.before') !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.product-tags.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.catalog.product_tags.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.catalog.product-tags.create.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.catalog.product-tags.create.save-btn')
                </button>
            </div>
        </div>

        <!-- Full Panel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.catalog.product_tags.create.card.general.before') !!}

                <!-- General -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.catalog.product-tags.create.general')
                    </p>

                    <!-- Locales -->
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="locale"
                        value="all"
                    />

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.catalog.product-tags.create.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            id="name"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('admin::app.catalog.product-tags.create.name')"
                            :placeholder="trans('admin::app.catalog.product-tags.create.name')"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.catalog.product_tags.create.card.general.after') !!}

                {!! view_render_event('bagisto.admin.catalog.product_tags.create.card.image.before') !!}

                <!-- Image -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.catalog.product-tags.create.image')
                    </p>

                    <p class="mb-4 text-xs text-gray-500">
                        @lang('admin::app.catalog.product-tags.create.image-info')
                    </p>

                    <x-admin::media.images name="image" />
                </div>

                {!! view_render_event('bagisto.admin.catalog.product_tags.create.card.image.after') !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.product_tags.create.create_form_controls.after') !!}
    </x-admin::form>

    {!! view_render_event('bagisto.admin.catalog.product_tags.create.after') !!}
</x-admin::layouts>
