<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.accounts.edit.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.accounting.accounts.edit.before', ['account' => $account]) !!}

    <x-admin::form
        :action="route('admin.accounting.accounts.update', $account->id)"
        method="PUT"
    >
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.accounting.accounts.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.accounting.accounts.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.accounting.accounts.edit.back-btn')
                </a>

                <button type="submit" class="primary-button">
                    @lang('admin::app.accounting.accounts.edit.save-btn')
                </button>
            </div>
        </div>

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.accounting.accounts.edit.general')
                    </p>

                    <div class="flex gap-2.5 max-sm:flex-wrap">
                        <x-admin::form.control-group class="w-1/2 max-sm:w-full">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.accounting.accounts.edit.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                rules="required"
                                :value="old('code', $account->code)"
                                :label="trans('admin::app.accounting.accounts.edit.code')"
                                :disabled="(bool) $account->is_system"
                            />

                            @if ($account->is_system)
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="code"
                                    :value="$account->code"
                                />
                            @endif

                            <x-admin::form.control-group.error control-name="code" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="w-1/2 max-sm:w-full">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.accounting.accounts.edit.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                rules="required"
                                :value="old('name', $account->name)"
                                :label="trans('admin::app.accounting.accounts.edit.name')"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>
                    </div>

                    <div class="flex gap-2.5 max-sm:flex-wrap">
                        <x-admin::form.control-group class="w-1/2 max-sm:w-full">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.accounting.accounts.edit.type')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="type"
                                rules="required"
                                :value="old('type', $account->type)"
                                :label="trans('admin::app.accounting.accounts.edit.type')"
                                :disabled="(bool) $account->is_system"
                            >
                                @foreach ($types as $value => $label)
                                    <option value="{{ $value }}" {{ old('type', $account->type) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            @if ($account->is_system)
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="type"
                                    :value="$account->type"
                                />
                            @endif

                            <x-admin::form.control-group.error control-name="type" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="w-1/2 max-sm:w-full">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.accounting.accounts.edit.parent')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="parent_id"
                                :value="old('parent_id', $account->parent_id)"
                                :label="trans('admin::app.accounting.accounts.edit.parent')"
                            >
                                <option value="">@lang('admin::app.accounting.accounts.edit.no-parent')</option>

                                @foreach ($accounts as $item)
                                    <option value="{{ $item->id }}" {{ (string) old('parent_id', $account->parent_id) === (string) $item->id ? 'selected' : '' }}>
                                        {{ $item->code }} - {{ $item->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="parent_id" />
                        </x-admin::form.control-group>
                    </div>

                    <div class="flex gap-2.5 max-sm:flex-wrap">
                        <x-admin::form.control-group class="w-1/2 max-sm:w-full">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.accounting.accounts.edit.opening-balance')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="opening_balance"
                                :value="old('opening_balance', $account->opening_balance)"
                                :label="trans('admin::app.accounting.accounts.edit.opening-balance')"
                            />

                            <x-admin::form.control-group.error control-name="opening_balance" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="w-1/2 max-sm:w-full">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.accounting.accounts.edit.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="is_active"
                                value="1"
                                :label="trans('admin::app.accounting.accounts.edit.status')"
                                :checked="(boolean) old('is_active', $account->is_active)"
                            />

                            <x-admin::form.control-group.error control-name="is_active" />
                        </x-admin::form.control-group>
                    </div>

                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.accounting.accounts.edit.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            :value="old('description', $account->description)"
                            :label="trans('admin::app.accounting.accounts.edit.description')"
                        />

                        <x-admin::form.control-group.error control-name="description" />
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>
    </x-admin::form>

    {!! view_render_event('bagisto.admin.accounting.accounts.edit.after', ['account' => $account]) !!}
</x-admin::layouts>
