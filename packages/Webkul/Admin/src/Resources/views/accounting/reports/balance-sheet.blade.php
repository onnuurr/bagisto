<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.reports.balance-sheet.title')
    </x-slot>

    <p class="text-xl font-bold text-gray-800 dark:text-white">
        @lang('admin::app.accounting.reports.balance-sheet.title')
    </p>

    <form method="GET" action="{{ route('admin.accounting.reports.balance_sheet') }}" class="mt-3.5 box-shadow flex items-end gap-2.5 rounded bg-white p-4 dark:bg-gray-900">
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                @lang('admin::app.accounting.reports.balance-sheet.as-of-date')
            </label>

            <input type="date" name="as_of_date" value="{{ $asOfDate }}" class="h-10 rounded-md border px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        </div>

        <button type="submit" class="primary-button h-10">
            @lang('admin::app.accounting.reports.balance-sheet.filter-btn')
        </button>
    </form>

    <div class="mt-2.5 grid grid-cols-2 gap-2.5 max-sm:grid-cols-1">
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <p class="mb-2 text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.accounting.reports.balance-sheet.assets')
            </p>

            <table class="min-w-full">
                <tbody>
                    @foreach ($assets as $row)
                        <tr class="border-b dark:border-gray-800">
                            <td class="py-2 text-gray-800 dark:text-white">{{ $row['account']->name }}</td>
                            <td class="py-2 text-right text-gray-800 dark:text-white">{{ core()->formatBasePrice($row['amount']) }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr class="font-semibold text-gray-800 dark:text-white">
                        <td class="pt-2">@lang('admin::app.accounting.reports.balance-sheet.total-assets')</td>
                        <td class="pt-2 text-right">{{ core()->formatBasePrice($totalAssets) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <p class="mb-2 text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.accounting.reports.balance-sheet.liabilities')
            </p>

            <table class="mb-6 min-w-full">
                <tbody>
                    @foreach ($liabilities as $row)
                        <tr class="border-b dark:border-gray-800">
                            <td class="py-2 text-gray-800 dark:text-white">{{ $row['account']->name }}</td>
                            <td class="py-2 text-right text-gray-800 dark:text-white">{{ core()->formatBasePrice($row['amount']) }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr class="font-semibold text-gray-800 dark:text-white">
                        <td class="pt-2">@lang('admin::app.accounting.reports.balance-sheet.total-liabilities')</td>
                        <td class="pt-2 text-right">{{ core()->formatBasePrice($totalLiabilities) }}</td>
                    </tr>
                </tfoot>
            </table>

            <p class="mb-2 text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.accounting.reports.balance-sheet.equity')
            </p>

            <table class="min-w-full">
                <tbody>
                    @foreach ($equity as $row)
                        <tr class="border-b dark:border-gray-800">
                            <td class="py-2 text-gray-800 dark:text-white">{{ $row['account']->name }}</td>
                            <td class="py-2 text-right text-gray-800 dark:text-white">{{ core()->formatBasePrice($row['amount']) }}</td>
                        </tr>
                    @endforeach

                    <tr class="border-b dark:border-gray-800">
                        <td class="py-2 text-gray-800 dark:text-white">@lang('admin::app.accounting.reports.balance-sheet.current-earnings')</td>
                        <td class="py-2 text-right text-gray-800 dark:text-white">{{ core()->formatBasePrice($currentEarnings) }}</td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr class="font-semibold text-gray-800 dark:text-white">
                        <td class="pt-2">@lang('admin::app.accounting.reports.balance-sheet.total-equity')</td>
                        <td class="pt-2 text-right">{{ core()->formatBasePrice($totalEquity) }}</td>
                    </tr>

                    <tr class="border-t-2 border-gray-800 text-lg font-bold text-gray-800 dark:border-white dark:text-white">
                        <td class="pt-2">@lang('admin::app.accounting.reports.balance-sheet.total-liabilities-and-equity')</td>
                        <td class="pt-2 text-right">{{ core()->formatBasePrice($totalLiabilities + $totalEquity) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-admin::layouts>
