<!-- Gift Card Vue Component -->
<v-gift-card
    :cart="cart"
    @gift-card-applied="getCart"
    @gift-card-removed="getCart"
>
</v-gift-card>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-gift-card-template"
    >
        <div class="flex justify-between text-right">
            <p class="text-base max-md:font-normal max-sm:text-sm">
                @{{ cart.gift_card_code ? "@lang('shop::app.checkout.gift-card.applied')" : "@lang('shop::app.checkout.gift-card.title')" }}
            </p>

            {!! view_render_event('bagisto.shop.checkout.cart.gift_card.before') !!}

            <p class="text-base font-medium max-sm:text-sm">
                <!-- Apply Gift Card Form -->
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <!-- Apply gift card form -->
                    <form @submit="handleSubmit($event, applyGiftCard)">
                        {!! view_render_event('bagisto.shop.checkout.cart.gift_card.form_controls.before') !!}

                        <!-- Apply gift card modal -->
                        <x-shop::modal ref="giftCardModel">
                            <!-- Modal Toggler -->
                            <x-slot:toggle>
                                <span
                                    class="cursor-pointer text-base text-blue-700 max-sm:text-sm"
                                    role="button"
                                    tabindex="0"
                                    v-if="! cart.gift_card_code"
                                >
                                    @lang('shop::app.checkout.gift-card.apply')
                                </span>
                            </x-slot>

                            <!-- Modal Header -->
                            <x-slot:header class="max-md:p-5">
                                <h2 class="text-2xl font-medium max-md:text-base">
                                    @lang('shop::app.checkout.gift-card.apply')
                                </h2>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content class="!px-4">
                                <x-shop::form.control-group class="!mb-0">
                                    <x-shop::form.control-group.control
                                        type="text"
                                        class="px-6 py-4 max-md:!mb-0 max-md:!p-3 max-sm:!p-2"
                                        name="code"
                                        rules="required"
                                        :placeholder="trans('shop::app.checkout.gift-card.enter-your-code')"
                                    />

                                    <x-shop::form.control-group.error
                                        class="flex"
                                        control-name="code"
                                    />
                                </x-shop::form.control-group>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <!-- Gift Card Form Action Container -->
                                <div class="flex flex-wrap items-center gap-4 max-md:justify-between">
                                    <div class="flex items-center gap-4 max-md:block">
                                        <p class="text-sm font-medium text-zinc-500 max-md:text-left max-md:text-xs">
                                            @lang('shop::app.checkout.gift-card.subtotal')
                                        </p>

                                        <p class="text-3xl font-semibold max-md:text-lg">
                                            @{{ cart.formatted_sub_total }}
                                        </p>
                                    </div>

                                    <x-shop::button
                                        class="primary-button max-w-none flex-auto rounded-2xl px-11 py-3 max-md:max-w-[153px] max-md:rounded-lg max-md:py-2"
                                        :title="trans('shop::app.checkout.gift-card.button-title')"
                                        ::loading="isStoring"
                                        ::disabled="isStoring"
                                    />
                                </div>
                            </x-slot>
                        </x-shop::modal>

                        {!! view_render_event('bagisto.shop.checkout.cart.gift_card.form_controls.after') !!}
                    </form>
                </x-shop::form>

                <!-- Applied Gift Card Information Container -->
                <span
                    class="inline-flex items-center gap-2"
                    v-if="cart.gift_card_code"
                >
                    <span
                        class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-0.5 text-sm font-semibold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 max-sm:text-xs"
                        :title="'@lang('shop::app.checkout.gift-card.applied')'"
                    >
                        @{{ cart.gift_card_code }}
                    </span>

                    <span
                        class="icon-cancel cursor-pointer text-xl text-gray-400 transition-colors hover:text-red-500 max-sm:text-base"
                        title="@lang('shop::app.checkout.gift-card.remove')"
                        @click="destroyGiftCard"
                    >
                    </span>
                </span>
            </p>

            {!! view_render_event('bagisto.shop.checkout.cart.gift_card.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-gift-card', {
            template: '#v-gift-card-template',

            props: ['cart'],

            data() {
                return {
                    isStoring: false,
                }
            },

            methods: {
                applyGiftCard(params, { resetForm }) {
                    this.isStoring = true;

                    this.$axios.post("{{ route('shop.api.checkout.cart.gift_card.apply') }}", params)
                        .then((response) => {
                            this.isStoring = false;

                            this.$emit('gift-card-applied');

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$refs.giftCardModel.toggle();

                            resetForm();
                        })
                        .catch((error) => {
                            this.isStoring = false;

                            this.$refs.giftCardModel.toggle();

                            if ([400, 422].includes(error.response.request.status)) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });

                                resetForm();

                                return;
                            }

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },

                destroyGiftCard() {
                    this.$axios.delete("{{ route('shop.api.checkout.cart.gift_card.remove') }}", {
                            '_token': "{{ csrf_token() }}"
                        })
                        .then((response) => {
                            this.$emit('gift-card-removed');

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => console.log(error));
                },
            }
        })
    </script>
@endPushOnce
