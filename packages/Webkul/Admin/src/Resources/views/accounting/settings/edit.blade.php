<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.settings.edit.title')
    </x-slot>

    <x-admin::form :action="route('admin.accounting.settings.update')">
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.accounting.settings.edit.title')
            </p>

            <button type="submit" class="primary-button">
                @lang('admin::app.accounting.settings.edit.save-btn')
            </button>
        </div>

        <div class="mt-3.5 box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.accounting.settings.edit.mapping-title')
            </p>

            <p class="mb-4 text-sm text-gray-500 dark:text-gray-300">
                @lang('admin::app.accounting.settings.edit.mapping-info')
            </p>

            @php
                $mappingFields = [
                    'accounts_receivable_account' => trans('admin::app.accounting.settings.edit.accounts-receivable-account'),
                    'cash_bank_account'           => trans('admin::app.accounting.settings.edit.cash-bank-account'),
                    'sales_revenue_account'       => trans('admin::app.accounting.settings.edit.sales-revenue-account'),
                    'shipping_revenue_account'    => trans('admin::app.accounting.settings.edit.shipping-revenue-account'),
                    'sales_discount_account'      => trans('admin::app.accounting.settings.edit.sales-discount-account'),
                    'sales_refund_account'        => trans('admin::app.accounting.settings.edit.sales-refund-account'),
                    'tax_payable_account'         => trans('admin::app.accounting.settings.edit.tax-payable-account'),
                ];
            @endphp

            <div class="grid grid-cols-2 gap-x-4 max-sm:grid-cols-1">
                @foreach ($mappingFields as $name => $label)
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            {{ $label }}
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            :name="$name"
                            :value="old($name, $settings[$name] ?? '')"
                            :label="$label"
                        >
                            <option value="">@lang('admin::app.accounting.settings.edit.not-mapped')</option>

                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}" {{ (string) old($name, $settings[$name] ?? '') === (string) $account->id ? 'selected' : '' }}>
                                    {{ $account->code }} - {{ $account->name }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error :control-name="$name" />
                    </x-admin::form.control-group>
                @endforeach
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
