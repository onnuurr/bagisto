<x-admin::layouts>
    <x-slot:title>
        {{ $journalEntry->entry_number }}
    </x-slot>

    {!! view_render_event('bagisto.admin.accounting.journal_entries.view.before', ['journalEntry' => $journalEntry]) !!}

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            {{ $journalEntry->entry_number }}
        </p>

        <div class="flex items-center gap-x-2.5">
            <a
                href="{{ route('admin.accounting.journal_entries.index') }}"
                class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            >
                @lang('admin::app.accounting.journal-entries.view.back-btn')
            </a>

            @if ($journalEntry->status === 'draft' && bouncer()->hasPermission('accounting.journal_entries.post'))
                <form method="POST" action="{{ route('admin.accounting.journal_entries.post', $journalEntry->id) }}">
                    @csrf

                    <button type="submit" class="primary-button">
                        @lang('admin::app.accounting.journal-entries.view.post-btn')
                    </button>
                </form>
            @endif

            @if ($journalEntry->status === 'posted' && bouncer()->hasPermission('accounting.journal_entries.void'))
                <form method="POST" action="{{ route('admin.accounting.journal_entries.void', $journalEntry->id) }}">
                    @csrf

                    <button type="submit" class="secondary-button">
                        @lang('admin::app.accounting.journal-entries.view.void-btn')
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-3.5 box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <div class="grid grid-cols-4 gap-4 max-sm:grid-cols-1">
            <div>
                <p class="text-xs uppercase text-gray-500 dark:text-gray-300">@lang('admin::app.accounting.journal-entries.view.entry-date')</p>
                <p class="text-gray-800 dark:text-white">{{ $journalEntry->entry_date->format('d M, Y') }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500 dark:text-gray-300">@lang('admin::app.accounting.journal-entries.view.status')</p>
                <p class="text-gray-800 dark:text-white">{{ trans('admin::app.accounting.journal-entries.statuses.'.$journalEntry->status) }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500 dark:text-gray-300">@lang('admin::app.accounting.journal-entries.view.reference-type')</p>
                <p class="text-gray-800 dark:text-white">{{ trans('admin::app.accounting.journal-entries.reference-types.'.$journalEntry->reference_type) }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500 dark:text-gray-300">@lang('admin::app.accounting.journal-entries.view.currency')</p>
                <p class="text-gray-800 dark:text-white">{{ $journalEntry->currency_code }}</p>
            </div>
        </div>

        @if ($journalEntry->description)
            <div class="mt-4">
                <p class="text-xs uppercase text-gray-500 dark:text-gray-300">@lang('admin::app.accounting.journal-entries.view.description')</p>
                <p class="text-gray-800 dark:text-white">{{ $journalEntry->description }}</p>
            </div>
        @endif
    </div>

    <div class="mt-2.5 box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <table class="min-w-full">
            <thead>
                <tr class="border-b text-left text-xs uppercase text-gray-500 dark:border-gray-800 dark:text-gray-300">
                    <th class="pb-2">@lang('admin::app.accounting.journal-entries.view.account')</th>
                    <th class="pb-2">@lang('admin::app.accounting.journal-entries.view.line-description')</th>
                    <th class="pb-2 text-right">@lang('admin::app.accounting.journal-entries.view.debit')</th>
                    <th class="pb-2 text-right">@lang('admin::app.accounting.journal-entries.view.credit')</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($journalEntry->lines as $line)
                    <tr class="border-b dark:border-gray-800">
                        <td class="py-2 text-gray-800 dark:text-white">{{ $line->account->code }} - {{ $line->account->name }}</td>
                        <td class="py-2 text-gray-600 dark:text-gray-300">{{ $line->description }}</td>
                        <td class="py-2 text-right text-gray-800 dark:text-white">{{ core()->formatBasePrice($line->base_debit) }}</td>
                        <td class="py-2 text-right text-gray-800 dark:text-white">{{ core()->formatBasePrice($line->base_credit) }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr class="font-semibold text-gray-800 dark:text-white">
                    <td class="pt-2" colspan="2">@lang('admin::app.accounting.journal-entries.view.total')</td>
                    <td class="pt-2 text-right">{{ core()->formatBasePrice($journalEntry->total_base_debit) }}</td>
                    <td class="pt-2 text-right">{{ core()->formatBasePrice($journalEntry->total_base_credit) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {!! view_render_event('bagisto.admin.accounting.journal_entries.view.after', ['journalEntry' => $journalEntry]) !!}
</x-admin::layouts>
