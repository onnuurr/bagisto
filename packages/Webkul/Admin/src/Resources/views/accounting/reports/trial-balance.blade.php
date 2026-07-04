<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.reports.trial-balance.title')
    </x-slot>

    <p class="text-xl font-bold text-gray-800 dark:text-white">
        @lang('admin::app.accounting.reports.trial-balance.title')
    </p>

    <form method="GET" action="{{ route('admin.accounting.reports.trial_balance') }}" class="mt-3.5 box-shadow flex items-end gap-2.5 rounded bg-white p-4 dark:bg-gray-900">
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                @lang('admin::app.accounting.reports.trial-balance.as-of-date')
            </label>

            <input type="date" name="as_of_date" value="{{ $asOfDate }}" class="h-10 rounded-md border px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        </div>

        <button type="submit" class="primary-button h-10">
            @lang('admin::app.accounting.reports.trial-balance.filter-btn')
        </button>
    </form>

    <div class="mt-2.5 box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <table class="min-w-full">
            <thead>
                <tr class="border-b text-left text-xs uppercase text-gray-500 dark:border-gray-800 dark:text-gray-300">
                    <th class="pb-2">@lang('admin::app.accounting.reports.trial-balance.code')</th>
                    <th class="pb-2">@lang('admin::app.accounting.reports.trial-balance.account')</th>
                    <th class="pb-2 text-right">@lang('admin::app.accounting.reports.trial-balance.debit')</th>
                    <th class="pb-2 text-right">@lang('admin::app.accounting.reports.trial-balance.credit')</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($rows as $row)
                    <tr class="border-b dark:border-gray-800">
                        <td class="py-2 text-gray-800 dark:text-white">{{ $row['account']->code }}</td>
                        <td class="py-2 text-gray-800 dark:text-white">{{ $row['account']->name }}</td>
                        <td class="py-2 text-right text-gray-800 dark:text-white">{{ $row['debit'] ? core()->formatBasePrice($row['debit']) : '-' }}</td>
                        <td class="py-2 text-right text-gray-800 dark:text-white">{{ $row['credit'] ? core()->formatBasePrice($row['credit']) : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr class="font-semibold text-gray-800 dark:text-white">
                    <td colspan="2" class="pt-2">@lang('admin::app.accounting.reports.trial-balance.total')</td>
                    <td class="pt-2 text-right">{{ core()->formatBasePrice($totalDebit) }}</td>
                    <td class="pt-2 text-right">{{ core()->formatBasePrice($totalCredit) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</x-admin::layouts>
