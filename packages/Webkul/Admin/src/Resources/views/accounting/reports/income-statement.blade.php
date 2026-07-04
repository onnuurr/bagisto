<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.reports.income-statement.title')
    </x-slot>

    <p class="text-xl font-bold text-gray-800 dark:text-white">
        @lang('admin::app.accounting.reports.income-statement.title')
    </p>

    <form method="GET" action="{{ route('admin.accounting.reports.income_statement') }}" class="mt-3.5 box-shadow flex items-end gap-2.5 rounded bg-white p-4 dark:bg-gray-900">
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                @lang('admin::app.accounting.reports.income-statement.start-date')
            </label>

            <input type="date" name="start_date" value="{{ $startDate }}" class="h-10 rounded-md border px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        </div>

        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                @lang('admin::app.accounting.reports.income-statement.end-date')
            </label>

            <input type="date" name="end_date" value="{{ $endDate }}" class="h-10 rounded-md border px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        </div>

        <button type="submit" class="primary-button h-10">
            @lang('admin::app.accounting.reports.income-statement.filter-btn')
        </button>
    </form>

    <div class="mt-2.5 box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <p class="mb-2 text-base font-semibold text-gray-800 dark:text-white">
            @lang('admin::app.accounting.reports.income-statement.revenue')
        </p>

        <table class="mb-6 min-w-full">
            <tbody>
                @foreach ($revenues as $row)
                    <tr class="border-b dark:border-gray-800">
                        <td class="py-2 text-gray-800 dark:text-white">{{ $row['account']->name }}</td>
                        <td class="py-2 text-right text-gray-800 dark:text-white">{{ core()->formatBasePrice($row['amount']) }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr class="font-semibold text-gray-800 dark:text-white">
                    <td class="pt-2">@lang('admin::app.accounting.reports.income-statement.total-revenue')</td>
                    <td class="pt-2 text-right">{{ core()->formatBasePrice($totalRevenue) }}</td>
                </tr>
            </tfoot>
        </table>

        <p class="mb-2 text-base font-semibold text-gray-800 dark:text-white">
            @lang('admin::app.accounting.reports.income-statement.expenses')
        </p>

        <table class="min-w-full">
            <tbody>
                @foreach ($expenses as $row)
                    <tr class="border-b dark:border-gray-800">
                        <td class="py-2 text-gray-800 dark:text-white">{{ $row['account']->name }}</td>
                        <td class="py-2 text-right text-gray-800 dark:text-white">{{ core()->formatBasePrice($row['amount']) }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr class="font-semibold text-gray-800 dark:text-white">
                    <td class="pt-2">@lang('admin::app.accounting.reports.income-statement.total-expense')</td>
                    <td class="pt-2 text-right">{{ core()->formatBasePrice($totalExpense) }}</td>
                </tr>

                <tr class="border-t-2 border-gray-800 text-lg font-bold text-gray-800 dark:border-white dark:text-white">
                    <td class="pt-2">@lang('admin::app.accounting.reports.income-statement.net-income')</td>
                    <td class="pt-2 text-right">{{ core()->formatBasePrice($netIncome) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</x-admin::layouts>
