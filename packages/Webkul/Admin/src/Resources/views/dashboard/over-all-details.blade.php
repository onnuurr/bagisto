<!-- Over Details Vue Component -->
<v-dashboard-overall-details>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.over-all-details />
</v-dashboard-overall-details>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-overall-details-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.over-all-details />
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
                <!-- Total Sales -->
                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-full bg-primary/10 dark:bg-primary/20">
                        <img
                            class="h-6 w-6 dark:mix-blend-exclusion dark:invert"
                            src="{{ bagisto_asset('images/total-sales.svg')}}"
                            title="@lang('admin::app.dashboard.index.total-sales')"
                        >
                    </div>

                    <!-- Sales Stats -->
                    <div class="grid place-content-start gap-1">
                        <p class="text-lg font-bold leading-none text-gray-800 dark:text-white">
                            @{{ report.statistics.total_sales.formatted_total }}
                        </p>

                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                            @lang('admin::app.dashboard.index.total-sales')
                        </p>

                        <!-- Sales Percentage -->
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

                <!-- Total Orders -->
                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-full bg-success/10 dark:bg-success/20">
                        <img
                            class="h-6 w-6 dark:mix-blend-exclusion dark:invert"
                            src="{{ bagisto_asset('images/total-orders.svg')}}"
                            title="@lang('admin::app.dashboard.index.total-orders')"
                        >
                    </div>

                    <!-- Orders Stats -->
                    <div class="grid place-content-start gap-1">
                        <p class="text-lg font-bold leading-none text-gray-800 dark:text-white">
                            @{{ report.statistics.total_orders.current }}
                        </p>

                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                            @lang('admin::app.dashboard.index.total-orders')
                        </p>

                        <!-- Order Percentage -->
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

                <!-- Total Customers -->
                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/40">
                        <img
                            class="h-6 w-6 dark:mix-blend-exclusion dark:invert"
                            src="{{ bagisto_asset('images/customers.svg')}}"
                            title="@lang('admin::app.dashboard.index.total-customers')"
                        >
                    </div>

                    <!-- Customers Stats -->
                    <div class="grid place-content-start gap-1">
                        <p class="text-lg font-bold leading-none text-gray-800 dark:text-white">
                            @{{ report.statistics.total_customers.current }}
                        </p>

                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                            @lang('admin::app.dashboard.index.total-customers')
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

                <!-- Average sales -->
                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-full bg-warning/10 dark:bg-warning/20">
                        <img
                            class="h-6 w-6 dark:mix-blend-exclusion dark:invert"
                            src="{{ bagisto_asset('images/average-orders.svg')}}"
                            title="@lang('admin::app.dashboard.index.average-sale')"
                        >
                    </div>

                    <!-- Sales Stats -->
                    <div class="grid place-content-start gap-1">
                        <p class="text-lg font-bold leading-none text-gray-800 dark:text-white">
                            @{{ report.statistics.avg_sales.formatted_total }}
                        </p>

                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                            @lang('admin::app.dashboard.index.average-sale')
                        </p>

                        <!-- Sales Percentage -->
                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base text-success"
                                :class="[report.statistics.avg_sales.progress < 0 ? 'icon-down-stat text-danger dark:!text-danger' : 'icon-up-stat text-success dark:!text-success']"
                            ></span>

                            <p
                                class="text-xs font-semibold"
                                :class="[report.statistics.avg_sales.progress < 0 ?  'text-danger' : 'text-success']"
                            >
                                @{{ Math.abs(report.statistics.avg_sales.progress).toFixed(2) }}%
                            </p>

                        </div>
                    </div>
                </div>

                <!-- Unpaid Invoices -->
                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-full bg-danger/10 dark:bg-danger/20">
                        <img
                            class="h-6 w-6 dark:mix-blend-exclusion dark:invert"
                            src="{{ bagisto_asset('images/unpaid-invoices.svg')}}"
                            title="@lang('admin::app.dashboard.index.total-unpaid-invoices')"
                        >
                    </div>

                    <div class="grid place-content-start gap-1">
                        <p class="text-lg font-bold leading-none text-gray-800 dark:text-white">
                            @{{ report.statistics.total_unpaid_invoices.formatted_total }}
                        </p>

                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                            @lang('admin::app.dashboard.index.total-unpaid-invoices')
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-overall-details', {
            template: '#v-dashboard-overall-details-template',

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

                    filters.type = 'over-all';

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
