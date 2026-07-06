<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.booking.index.title')
    </x-slot>

    <v-booking-products></v-booking-products>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-booking-products-template"
        >
            <x-admin::layouts.page-header>
                <x-slot:title>
                    @lang('admin::app.sales.booking.index.title')
                </x-slot>

                <x-slot:subtitle>
                    @lang('admin::app.sales.booking.index.title') overview
                </x-slot>

                <x-slot:actions>
                    <!-- Export Modal -->
                    <x-admin::datagrid.export
                        v-if="viewType == 'table'"
                        src="{{ route('admin.sales.bookings.index') }}"
                    />

                    <!-- View Switcher -->
                    <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <!-- Calendar Icon -->
                        <button
                            class="icon-calendar flex h-8 w-9 items-center justify-center rounded-md text-xl transition-all duration-200"
                            :class="viewType === 'calendar'
                                ? 'bg-primary text-white shadow-sm'
                                : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200'"
                            @click="viewType = 'calendar'"
                        ></button>

                        <!-- List Icon -->
                        <button
                            class="icon-list flex h-8 w-9 items-center justify-center rounded-md text-xl transition-all duration-200"
                            :class="viewType === 'table'
                                ? 'bg-primary text-white shadow-sm'
                                : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200'"
                            @click="viewType = 'table'"
                        ></button>
                    </div>
                </x-slot>
            </x-admin::layouts.page-header>

            <template v-if="viewType == 'table'">
                <x-admin::datagrid :src="route('admin.sales.bookings.index')" />
            </template>

            <template v-else>
                @include('admin::sales.bookings.calendar')
            </template>
        </script>

        <script type="module">
            app.component('v-booking-products', {
                template: '#v-booking-products-template',

                data() {
                    return {
                        viewType: 'calendar',
                    };
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
