<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.accounting.journal-entries.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.accounting.journal_entries.create.before') !!}

    <x-admin::form :action="route('admin.accounting.journal_entries.store')">
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.accounting.journal-entries.create.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.accounting.journal_entries.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.accounting.journal-entries.create.back-btn')
                </a>

                <button
                    type="submit"
                    name="action"
                    value="draft"
                    class="secondary-button"
                >
                    @lang('admin::app.accounting.journal-entries.create.save-draft-btn')
                </button>

                <button
                    type="submit"
                    name="action"
                    value="post"
                    class="primary-button"
                >
                    @lang('admin::app.accounting.journal-entries.create.save-post-btn')
                </button>
            </div>
        </div>

        <v-journal-entry-create-form></v-journal-entry-create-form>
    </x-admin::form>

    {!! view_render_event('bagisto.admin.accounting.journal_entries.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-journal-entry-create-form-template"
        >
            <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.accounting.journal-entries.create.general')
                        </p>

                        <div class="flex gap-2.5 max-sm:flex-wrap">
                            <x-admin::form.control-group class="w-1/2 max-sm:w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.accounting.journal-entries.create.entry-date')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="entry_date"
                                    rules="required"
                                    :value="old('entry_date', now()->format('Y-m-d'))"
                                    :label="trans('admin::app.accounting.journal-entries.create.entry-date')"
                                />

                                <x-admin::form.control-group.error control-name="entry_date" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="w-1/2 max-sm:w-full !mb-0">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.accounting.journal-entries.create.description')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="description"
                                    :value="old('description')"
                                    :label="trans('admin::app.accounting.journal-entries.create.description')"
                                />

                                <x-admin::form.control-group.error control-name="description" />
                            </x-admin::form.control-group>
                        </div>
                    </div>

                    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                        <div class="mb-4 flex items-center justify-between">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.accounting.journal-entries.create.lines')
                            </p>

                            <p class="text-sm" :class="isBalanced() ? 'text-green-600' : 'text-red-600'">
                                @{{ formatAmount(totalDebit()) }} / @{{ formatAmount(totalCredit()) }}
                            </p>
                        </div>

                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs uppercase text-gray-500 dark:text-gray-300">
                                    <th class="pb-2">@lang('admin::app.accounting.journal-entries.create.account')</th>
                                    <th class="pb-2">@lang('admin::app.accounting.journal-entries.create.line-description')</th>
                                    <th class="w-32 pb-2">@lang('admin::app.accounting.journal-entries.create.debit')</th>
                                    <th class="w-32 pb-2">@lang('admin::app.accounting.journal-entries.create.credit')</th>
                                    <th class="w-10 pb-2"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(line, index) in lines" :key="index">
                                    <td class="py-1 pr-2">
                                        <select
                                            :name="['lines[' + index + '][account_id]']"
                                            v-model="line.account_id"
                                            class="custom-select flex h-10 w-full rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                        >
                                            <option value="">@lang('admin::app.accounting.journal-entries.create.select-account')</option>

                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="py-1 pr-2">
                                        <input
                                            type="text"
                                            :name="['lines[' + index + '][description]']"
                                            v-model="line.description"
                                            class="w-full rounded-md border px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                        >
                                    </td>

                                    <td class="py-1 pr-2">
                                        <input
                                            type="number"
                                            step="0.0001"
                                            min="0"
                                            :name="['lines[' + index + '][debit]']"
                                            v-model="line.debit"
                                            class="w-full rounded-md border px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                        >
                                    </td>

                                    <td class="py-1 pr-2">
                                        <input
                                            type="number"
                                            step="0.0001"
                                            min="0"
                                            :name="['lines[' + index + '][credit]']"
                                            v-model="line.credit"
                                            class="w-full rounded-md border px-3 py-2.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                        >
                                    </td>

                                    <td class="py-1 text-center">
                                        <a
                                            v-if="lines.length > 2"
                                            @click="removeLine(index)"
                                            class="icon-delete cursor-pointer text-2xl"
                                        ></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div
                            class="secondary-button mt-4 max-w-max"
                            @click="addLine"
                        >
                            @lang('admin::app.accounting.journal-entries.create.add-line')
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-journal-entry-create-form', {
                template: '#v-journal-entry-create-form-template',

                data() {
                    return {
                        lines: [
                            { account_id: '', debit: '', credit: '', description: '' },
                            { account_id: '', debit: '', credit: '', description: '' },
                        ],
                    };
                },

                methods: {
                    addLine() {
                        this.lines.push({ account_id: '', debit: '', credit: '', description: '' });
                    },

                    removeLine(index) {
                        this.lines.splice(index, 1);
                    },

                    totalDebit() {
                        return this.lines.reduce((total, line) => total + (parseFloat(line.debit) || 0), 0);
                    },

                    totalCredit() {
                        return this.lines.reduce((total, line) => total + (parseFloat(line.credit) || 0), 0);
                    },

                    isBalanced() {
                        return Math.abs(this.totalDebit() - this.totalCredit()) < 0.0001;
                    },

                    formatAmount(amount) {
                        return amount.toFixed(4);
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
