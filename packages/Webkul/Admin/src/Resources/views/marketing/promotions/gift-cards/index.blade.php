<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.promotions.gift-cards.index.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.promotions.gift_cards.create.before') !!}

    <!-- Create Gift Card Vue Component -->
    <v-create-gift-card>
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.marketing.promotions.gift-cards.index.title')
            </p>

            <!-- Create Button -->
            @if (bouncer()->hasPermission('marketing.promotions.gift_cards.create'))
                <div class="primary-button">
                    @lang('admin::app.marketing.promotions.gift-cards.index.create-btn')
                </div>
            @endif
        </div>

        <!-- Added For Shimmer -->
        <x-admin::shimmer.datagrid />
    </v-create-gift-card>

    {!! view_render_event('bagisto.admin.marketing.promotions.gift_cards.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-gift-card-template"
        >
            <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                <p class="text-xl font-bold text-gray-800 dark:text-white">
                    @lang('admin::app.marketing.promotions.gift-cards.index.title')
                </p>

                <!-- Create Button -->
                @if (bouncer()->hasPermission('marketing.promotions.gift_cards.create'))
                    <div
                        class="primary-button"
                        @click="isEditing = false; $refs.giftCard.toggle()"
                    >
                        @lang('admin::app.marketing.promotions.gift-cards.index.create-btn')
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.marketing.promotions.gift_cards.list.before') !!}

            <x-admin::datagrid
                :src="route('admin.marketing.promotions.gift_cards.index')"
                ref="datagrid"
            >
                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <div
                            v-for="record in available.records"
                            class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                                :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- Mass Actions -->
                            <p v-if="available.massActions.length">
                                <label :for="`mass_action_select_record_${record[available.meta.primary_column]}`">
                                    <input
                                        type="checkbox"
                                        class="peer hidden"
                                        :name="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                        :value="record[available.meta.primary_column]"
                                        :id="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                        v-model="applied.massActions.indices"
                                    >

                                    <span class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600">
                                    </span>
                                </label>
                            </p>

                            <!-- Id -->
                            <p class="break-words">
                                @{{ record.id }}
                            </p>

                            <!-- Code -->
                            <p class="break-words">
                                @{{ record.code }}
                            </p>

                            <!-- Amount -->
                            <p class="break-words">
                                @{{ record.amount }}
                            </p>

                            <!-- Status -->
                            <p class="break-words">
                                @{{ record.status }}
                            </p>

                            <!-- Expires At -->
                            <p class="break-words">
                                @{{ record.expires_at }}
                            </p>

                            <!-- Used At -->
                            <p class="break-words">
                                @{{ record.used_at }}
                            </p>

                            <!-- Actions -->
                            <div class="flex justify-end">
                                @if (bouncer()->hasPermission('marketing.promotions.gift_cards.edit'))
                                    <a @click="isEditing = true; editModal(record)">
                                        <span
                                            :class="record.actions.find(action => action.index === 'edit')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif

                                @if (bouncer()->hasPermission('marketing.promotions.gift_cards.delete'))
                                    <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                        <span
                                            :class="record.actions.find(action => action.index === 'delete')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>

            {!! view_render_event('bagisto.admin.marketing.promotions.gift_cards.list.after') !!}

            <!-- Gift Card Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="giftCardForm"
                >
                    <x-admin::modal ref="giftCard">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-if="isEditing"
                            >
                                @lang('admin::app.marketing.promotions.gift-cards.edit.title')
                            </p>

                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-else
                            >
                                @lang('admin::app.marketing.promotions.gift-cards.create.title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- ID -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            />

                            <!-- Code -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.gift-cards.create.code')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="code"
                                    ::rules="{ 'required': isEditing }"
                                    :label="trans('admin::app.marketing.promotions.gift-cards.create.code')"
                                    :placeholder="trans('admin::app.marketing.promotions.gift-cards.create.code')"
                                />

                                <span
                                    class="text-xs text-gray-400 dark:text-gray-500"
                                    v-if="! isEditing"
                                >
                                    @lang('admin::app.marketing.promotions.gift-cards.create.code-info')
                                </span>

                                <x-admin::form.control-group.error control-name="code" />
                            </x-admin::form.control-group>

                            <!-- Quantity (create only) -->
                            <x-admin::form.control-group v-if="! isEditing">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.gift-cards.create.quantity')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="quantity"
                                    value="1"
                                    :label="trans('admin::app.marketing.promotions.gift-cards.create.quantity')"
                                />

                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    @lang('admin::app.marketing.promotions.gift-cards.create.quantity-info')
                                </span>

                                <x-admin::form.control-group.error control-name="quantity" />
                            </x-admin::form.control-group>

                            <!-- Amount -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.promotions.gift-cards.create.amount')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="amount"
                                    rules="required"
                                    :label="trans('admin::app.marketing.promotions.gift-cards.create.amount')"
                                />

                                <x-admin::form.control-group.error control-name="amount" />
                            </x-admin::form.control-group>

                            <!-- Currency -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.promotions.gift-cards.create.currency')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="currency"
                                    rules="required"
                                    :label="trans('admin::app.marketing.promotions.gift-cards.create.currency')"
                                >
                                    @foreach (core()->getAllCurrencies() as $currency)
                                        <option
                                            value="{{ $currency->code }}"
                                            v-pre
                                        >
                                            {{ $currency->code }}
                                        </option>
                                    @endforeach
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="currency" />
                            </x-admin::form.control-group>

                            <!-- Status (edit only) -->
                            <x-admin::form.control-group v-if="isEditing">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.promotions.gift-cards.edit.status')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="status"
                                    rules="required"
                                    :label="trans('admin::app.marketing.promotions.gift-cards.edit.status')"
                                >
                                    <option value="unused" v-pre>
                                        @lang('admin::app.marketing.promotions.gift-cards.edit.unused')
                                    </option>

                                    <option value="used" v-pre>
                                        @lang('admin::app.marketing.promotions.gift-cards.edit.used')
                                    </option>

                                    <option value="expired" v-pre>
                                        @lang('admin::app.marketing.promotions.gift-cards.edit.expired')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="status" />
                            </x-admin::form.control-group>

                            <!-- Customer Email -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.gift-cards.create.customer-email')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="email"
                                    name="customer_email"
                                    :label="trans('admin::app.marketing.promotions.gift-cards.create.customer-email')"
                                />

                                <x-admin::form.control-group.error control-name="customer_email" />
                            </x-admin::form.control-group>

                            <!-- Expires At -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.gift-cards.create.expires-at')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="expires_at"
                                    :label="trans('admin::app.marketing.promotions.gift-cards.create.expires-at')"
                                />

                                <x-admin::form.control-group.error control-name="expires_at" />
                            </x-admin::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <x-admin::button
                                button-type="submit"
                                class="primary-button"
                                :title="trans('admin::app.marketing.promotions.gift-cards.create.save-btn')"
                                ::loading="isLoading"
                                ::disabled="isLoading"
                            />
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-create-gift-card', {
                template: '#v-create-gift-card-template',

                data() {
                    return {
                        isEditing: false,

                        isLoading: false,
                    }
                },

                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.length;

                        if (this.$refs.datagrid.available.actions.length) {
                            ++count;
                        }

                        if (this.$refs.datagrid.available.massActions.length) {
                            ++count;
                        }

                        return count;
                    },
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors }) {
                        this.isLoading = true;

                        let formData = new FormData(this.$refs.giftCardForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.marketing.promotions.gift_cards.update') }}" : "{{ route('admin.marketing.promotions.gift_cards.store') }}", formData)
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.$refs.giftCard.toggle();

                                this.$refs.datagrid.get();

                                resetForm();

                                this.isLoading = false;
                            })
                            .catch(error => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(values) {
                        this.$refs.giftCard.toggle();

                        this.$refs.modalForm.setValues(values);
                    },
                },
            })
        </script>
    @endPushOnce
</x-admin::layouts>
