<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.fiscal-years.create.title')
    </x-slot>

    <x-admin::form :action="route('admin.accounting.fiscal_years.store')">
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.accounting.fiscal-years.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.accounting.fiscal_years.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.accounting.fiscal-years.create.back-btn')
                </a>

                <button type="submit" class="primary-button">
                    @lang('admin::app.accounting.fiscal-years.create.save-btn')
                </button>
            </div>
        </div>

        <div class="mt-3.5 box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    @lang('admin::app.accounting.fiscal-years.create.code')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="code"
                    rules="required"
                    :value="old('code')"
                    :label="trans('admin::app.accounting.fiscal-years.create.code')"
                    :placeholder="trans('admin::app.accounting.fiscal-years.create.code-placeholder')"
                />

                <x-admin::form.control-group.error control-name="code" />
            </x-admin::form.control-group>

            <div class="flex gap-2.5 max-sm:flex-wrap">
                <x-admin::form.control-group class="w-1/2 max-sm:w-full">
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.accounting.fiscal-years.create.start-date')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="date"
                        name="start_date"
                        rules="required"
                        :value="old('start_date')"
                        :label="trans('admin::app.accounting.fiscal-years.create.start-date')"
                    />

                    <x-admin::form.control-group.error control-name="start_date" />
                </x-admin::form.control-group>

                <x-admin::form.control-group class="w-1/2 max-sm:w-full !mb-0">
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.accounting.fiscal-years.create.end-date')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="date"
                        name="end_date"
                        rules="required"
                        :value="old('end_date')"
                        :label="trans('admin::app.accounting.fiscal-years.create.end-date')"
                    />

                    <x-admin::form.control-group.error control-name="end_date" />
                </x-admin::form.control-group>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
