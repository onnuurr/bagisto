<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.ledger.index.title')
    </x-slot>

    <p class="text-xl font-bold text-gray-800 dark:text-white">
        @lang('admin::app.accounting.ledger.index.title')
    </p>

    <form method="GET" action="{{ route('admin.accounting.ledger.index') }}" class="mt-3.5 box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <div class="flex items-end gap-2.5 max-sm:flex-wrap">
            <div class="w-1/3 max-sm:w-full">
                <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                    @lang('admin::app.accounting.ledger.index.account')
                </label>

                <select name="account_id" class="custom-select flex h-10 w-full rounded-md border bg-white px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <option value="">@lang('admin::app.accounting.ledger.index.select-account')</option>

                    @foreach ($accounts as $item)
                        <option value="{{ $item->id }}" {{ $account && $account->id === $item->id ? 'selected' : '' }}>
                            {{ $item->code }} - {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-1/4 max-sm:w-full">
                <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                    @lang('admin::app.accounting.ledger.index.start-date')
                </label>

                <input type="date" name="start_date" value="{{ request('start_date') }}" class="h-10 w-full rounded-md border px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            </div>

            <div class="w-1/4 max-sm:w-full">
                <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                    @lang('admin::app.accounting.ledger.index.end-date')
                </label>

                <input type="date" name="end_date" value="{{ request('end_date') }}" class="h-10 w-full rounded-md border px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            </div>

            <button type="submit" class="primary-button h-10">
                @lang('admin::app.accounting.ledger.index.filter-btn')
            </button>
        </div>
    </form>

    @if ($account)
        <div class="mt-2.5 box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between">
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    {{ $account->code }} - {{ $account->name }}
                </p>

                <p class="text-sm text-gray-600 dark:text-gray-300">
                    @lang('admin::app.accounting.ledger.index.opening-balance'): {{ core()->formatBasePrice($openingBalance) }}
                </p>
            </div>

            <table class="min-w-full">
                <thead>
                    <tr class="border-b text-left text-xs uppercase text-gray-500 dark:border-gray-800 dark:text-gray-300">
                        <th class="pb-2">@lang('admin::app.accounting.ledger.index.date')</th>
                        <th class="pb-2">@lang('admin::app.accounting.ledger.index.entry-number')</th>
                        <th class="pb-2">@lang('admin::app.accounting.ledger.index.description')</th>
                        <th class="pb-2 text-right">@lang('admin::app.accounting.ledger.index.debit')</th>
                        <th class="pb-2 text-right">@lang('admin::app.accounting.ledger.index.credit')</th>
                        <th class="pb-2 text-right">@lang('admin::app.accounting.ledger.index.balance')</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="border-b dark:border-gray-800">
                            <td class="py-2 text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($transaction['date'])->format('d M, Y') }}</td>
                            <td class="py-2 text-gray-800 dark:text-white">{{ $transaction['number'] }}</td>
                            <td class="py-2 text-gray-600 dark:text-gray-300">{{ $transaction['description'] }}</td>
                            <td class="py-2 text-right text-gray-800 dark:text-white">{{ $transaction['debit'] ? core()->formatBasePrice($transaction['debit']) : '-' }}</td>
                            <td class="py-2 text-right text-gray-800 dark:text-white">{{ $transaction['credit'] ? core()->formatBasePrice($transaction['credit']) : '-' }}</td>
                            <td class="py-2 text-right font-medium text-gray-800 dark:text-white">{{ core()->formatBasePrice($transaction['balance']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500 dark:text-gray-300">
                                @lang('admin::app.accounting.ledger.index.no-transactions')
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr class="font-semibold text-gray-800 dark:text-white">
                        <td colspan="5" class="pt-2 text-right">@lang('admin::app.accounting.ledger.index.closing-balance')</td>
                        <td class="pt-2 text-right">{{ core()->formatBasePrice($closingBalance) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</x-admin::layouts>
