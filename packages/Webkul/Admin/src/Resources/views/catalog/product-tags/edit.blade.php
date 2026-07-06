<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.product-tags.edit.title')
    </x-slot>

    @php
        $currentLocale = core()->getRequestedLocale();
    @endphp

    {!! view_render_event('bagisto.admin.catalog.product_tags.edit.before', ['productTag' => $productTag]) !!}

    <!-- Product Tag Edit Form -->
    <x-admin::form
        :action="route('admin.catalog.product_tags.update', $productTag->id)"
        enctype="multipart/form-data"
        method="PUT"
    >
        {!! view_render_event('bagisto.admin.catalog.product_tags.edit.edit_form_controls.before', ['productTag' => $productTag]) !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.catalog.product-tags.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.catalog.product_tags.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.catalog.product-tags.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.catalog.product-tags.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Filter Row -->
        <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
            <div class="flex items-center gap-x-1">
                <!-- Locale Switcher -->
                <x-admin::dropdown
                    position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'left' : 'right' }}"
                    :class="core()->getAllLocales()->count() <= 1 ? 'hidden' : ''"
                >
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 focus:bg-gray-200 dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                        >
                            <span class="icon-language text-2xl"></span>

                            <span v-pre>{{ $currentLocale->name }}</span>

                            <input
                                type="hidden"
                                name="locale"
                                value="{{ $currentLocale->code }}"
                            />

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-0">
                        @foreach (core()->getAllLocales() as $locale)
                            <a
                                href="?{{ Arr::query(['locale' => $locale->code]) }}"
                                class="flex gap-2.5 px-5 py-2 text-base cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                                v-pre
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-admin::dropdown>
            </div>
        </div>

        <!-- Full Panel -->
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.catalog.product_tags.edit.card.general.before', ['productTag' => $productTag]) !!}

                <!-- General -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.catalog.product-tags.edit.general')
                    </p>

                    <!-- Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.catalog.product-tags.edit.name')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            :id="$currentLocale->code.'[name]'"
                            :name="$currentLocale->code.'[name]'"
                            rules="required"
                            :value="old($currentLocale->code)['name'] ?? ($productTag->translate($currentLocale->code)['name'] ?? '')"
                            :label="trans('admin::app.catalog.product-tags.edit.name')"
                            :placeholder="trans('admin::app.catalog.product-tags.edit.name')"
                        />

                        <x-admin::form.control-group.error :control-name="$currentLocale->code.'[name]'" />
                    </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.catalog.product_tags.edit.card.general.after', ['productTag' => $productTag]) !!}

                {!! view_render_event('bagisto.admin.catalog.product_tags.edit.card.image.before', ['productTag' => $productTag]) !!}

                <!-- Image -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.catalog.product-tags.edit.image')
                    </p>

                    <p class="mb-4 text-xs text-gray-500">
                        @lang('admin::app.catalog.product-tags.edit.image-info')
                    </p>

                    <x-admin::media.images
                        name="image"
                        :uploaded-images="$productTag->image ? [['id' => 'image', 'url' => $productTag->image_url]] : []"
                    />
                </div>

                {!! view_render_event('bagisto.admin.catalog.product_tags.edit.card.image.after', ['productTag' => $productTag]) !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.product_tags.edit.edit_form_controls.after', ['productTag' => $productTag]) !!}
    </x-admin::form>

    {!! view_render_event('bagisto.admin.catalog.product_tags.edit.after', ['productTag' => $productTag]) !!}
</x-admin::layouts>
