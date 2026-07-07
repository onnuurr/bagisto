<!-- Todays Details Vue Component -->
<v-dashboard-todays-details>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.todays-details />
</v-dashboard-todays-details>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-todays-details-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.todays-details />
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="grid gap-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <!-- Today's Sales -->
                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-full bg-primary/10 dark:bg-primary/20">
                            <img
                                class="h-6 w-6 dark:mix-blend-exclusion dark:invert"
                                src="{{ bagisto_asset('images/total-sales.svg')}}"
                                title="@lang('admin::app.dashboard.index.today-sales')"
                            >
                        </div>

                        <!-- Sales Stats -->
                        <div class="grid place-content-start gap-1">
                            <p class="text-lg font-bold leading-none text-gray-800 dark:text-white">
                                @{{ report.statistics.total_sales.formatted_total }}
                            </p>

                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                @lang('admin::app.dashboard.index.today-sales')
                            </p>

                            <!-- Percentage Of Sales -->
                            <div class="flex items-center gap-0.5">
                                <span
                                    class="text-base text-success"
                                    :class="[report.statistics.total_sales.progress < 0 ? 'icon-down-stat text-danger dark:!text-danger' : 'icon-up-stat text-success dark:!text-success']"
                                ></span>

                                <p
                                    class="text-xs font-semibold text-success"
                                    :class="[report.statistics.total_sales.progress < 0 ?  'text-danger' : 'text-success']"
                                >
                                    @{{ Math.abs(report.statistics.total_sales.progress.toFixed(2)) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Orders -->
                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-full bg-success/10 dark:bg-success/20">
                            <img
                                class="h-6 w-6 dark:mix-blend-exclusion dark:invert"
                                src="{{ bagisto_asset('images/total-orders.svg')}}"
                                title="@lang('admin::app.dashboard.index.today-orders')"
                            >
                        </div>

                        <!-- Orders Stats -->
                        <div class="grid place-content-start gap-1">
                            <p class="text-lg font-bold leading-none text-gray-800 dark:text-white">
                                @{{ report.statistics.total_orders.current }}
                            </p>

                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                @lang('admin::app.dashboard.index.today-orders')
                            </p>

                            <!-- Orders Percentage -->
                            <div class="flex items-center gap-0.5">
                                <span
                                    class="text-base text-success"
                                    :class="[report.statistics.total_orders.progress < 0 ? 'icon-down-stat text-danger dark:!text-danger' : 'icon-up-stat text-success dark:!text-success']"
                                ></span>

                                <p
                                    class="text-xs font-semibold text-success"
                                    :class="[report.statistics.total_orders.progress < 0 ?  'text-danger' : 'text-success']"
                                >
                                    @{{ Math.abs(report.statistics.total_orders.progress.toFixed(2)) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Customers -->
                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/40">
                            <img
                                class="h-6 w-6 dark:mix-blend-exclusion dark:invert"
                                src="{{ bagisto_asset('images/customers.svg')}}"
                                title="@lang('admin::app.dashboard.index.today-customers')"
                            >
                        </div>

                        <!-- Customers Stats -->
                        <div class="grid place-content-start gap-1">
                            <p class="text-lg font-bold leading-none text-gray-800 dark:text-white">
                                @{{ report.statistics.total_customers.current }}
                            </p>

                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                @lang('admin::app.dashboard.index.today-customers')
                            </p>

                            <!-- Customers Percentage -->
                            <div class="flex items-center gap-0.5">
                                <span
                                    class="text-base text-success"
                                    :class="[report.statistics.total_customers.progress < 0 ? 'icon-down-stat text-danger dark:!text-danger' : 'icon-up-stat text-success dark:!text-success']"
                                ></span>

                                <p
                                    class="text-xs font-semibold text-success"
                                    :class="[report.statistics.total_customers.progress < 0 ?  'text-danger' : 'text-success']"
                                >
                                    @{{ Math.abs(report.statistics.total_customers.progress.toFixed(2)) }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today Orders Details -->
                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
                <div
                    v-for="order in report.statistics.orders"
                    class="border-b bg-white p-4 last:border-b-0 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950"
                >
                    <div class="flex flex-wrap gap-4">
                        <!-- Total Sales -->
                        <div class="flex min-w-[180px] flex-1 gap-2.5">
                            <div class="flex flex-col gap-1.5">
                                <!-- Order Id -->
                                <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
                                    @{{ "@lang('admin::app.dashboard.index.order-id', ['id' => ':replace'])".replace(':replace', order.increment_id) }}
                                </p>
    
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ order.created_at}}
                                </p>
    
                                <!-- Order Status -->
                                <p :class="'label-' + order.status">
                                    @{{ order.status_label }}
                                </p>
                            </div>
                        </div>

                        <div class="flex min-w-[180px] flex-1 gap-2.5">
                            <div class="flex flex-col gap-1.5">
                                <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
                                    @{{ order.formatted_base_grand_total }}
                                </p>
        
                                <!-- Payment Mode -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ order.payment_method }}
                                </p>
        
                                <!-- Channel Name -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ order.channel_name }}
                                </p>
                            </div>
                        </div>

                        <div class="flex min-w-[200px] flex-1 gap-2.5">
                            <div class="flex flex-col gap-1.5">
                            <!-- Customer Details -->
                                <p class="text-base text-gray-800 dark:text-white">
                                    @{{ order.customer_name }}
                                </p>
        
                                <p class="max-w-[180px] break-words text-gray-600 dark:text-gray-300">
                                    @{{ order.customer_email }}
                                </p>
        
                                <!-- Order Address -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ order.billing_address }}
                                </p>
                            </div>
                        </div>
 
                        <div class="flex min-w-[180px] flex-1 items-center justify-between gap-2.5">
                            <div class="flex flex-col gap-1.5">
                                <!-- Ordered Product Images -->
                                <div
                                    class="flex flex-wrap gap-1.5"
                                    v-html="order.items"
                                >
                                </div>
                            </div>

                             <!-- View More Icon -->
                             <a :href="'{{ route('admin.sales.orders.view', ':replace') }}'.replace(':replace', order.id)">
                                <span class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"></span>
                            </a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-todays-details', {
            template: '#v-dashboard-todays-details-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'today';

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                }
            }
        });
    </script>
@endPushOnce