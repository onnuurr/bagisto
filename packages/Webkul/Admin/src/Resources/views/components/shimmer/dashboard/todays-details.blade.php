<div class="grid gap-4">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="shimmer h-[52px] w-[52px] rounded-full"></div>

            <div class="grid place-content-start gap-1">
                <div class="shimmer h-[17px] w-[60px]"></div>

                <div class="shimmer h-[17px] w-[100px]"></div>

                <div class="shimmer h-[17px] w-10"></div>
            </div>
        </div>

        <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="shimmer h-[52px] w-[52px] rounded-full"></div>

            <div class="grid place-content-start gap-1">
                <div class="shimmer h-[17px] w-[60px]"></div>

                <div class="shimmer h-[17px] w-[100px]"></div>

                <div class="shimmer h-[17px] w-10"></div>
            </div>
        </div>

        <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="shimmer h-[52px] w-[52px] rounded-full"></div>

            <div class="grid place-content-start gap-1">
                <div class="shimmer h-[17px] w-[60px]"></div>

                <div class="shimmer h-[17px] w-[100px]"></div>

                <div class="shimmer h-[17px] w-10"></div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
    @for ($i = 1; $i <= 5; $i++)
        <div class="border-b bg-white p-4 last:border-b-0 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950">
            <div class="flex flex-wrap gap-4">
                <div class="flex min-w-[180px] flex-1 gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="shimmer h-[17px] w-[30px]"></div>

                        <div class="shimmer h-[17px] w-[130px]"></div>

                        <div class="shimmer h-[19px] w-[60px] rounded-[35px]"></div>
                    </div>
                </div>

                <div class="flex min-w-[180px] flex-1 gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="shimmer h-[17px] w-[50px]"></div>

                        <div class="shimmer h-[17px] w-[180px]"></div>

                        <div class="shimmer h-[17px] w-[60px]"></div>
                    </div>
                </div>

                <div class="flex min-w-[180px] flex-1 gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="shimmer h-[17px] w-[130px]"></div>

                        <div class="shimmer h-[17px] w-[130px]"></div>

                        <div class="shimmer h-[17px] w-[130px]"></div>
                    </div>
                </div>

                <div class="flex min-w-[180px] flex-1 items-center justify-between gap-2.5">
                    <div class="flex flex-col gap-1.5">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <div class="shimmer h-[65px] w-[65px] rounded"></div>
                            
                            <div class="shimmer h-[65px] w-[65px] rounded"></div>
                        </div>
                    </div>

                    <div class="shimmer h-9 w-9 rounded-md"></div>
                </div>
            </div>
        </div>
    @endfor
    </div>
</div>