<div {{ $attributes->merge(['class' => 'flex items-center justify-between gap-4 max-sm:flex-wrap']) }}>
    <div class="grid gap-1">
        <p
            class="text-xl font-bold text-gray-800 dark:text-white"
            v-pre
        >
            {{ $title }}
        </p>

        @isset($subtitle)
            <p
                class="text-sm text-gray-500 dark:text-gray-400"
                v-pre
            >
                {{ $subtitle }}
            </p>
        @endisset
    </div>

    @isset($actions)
        <div class="flex items-center gap-x-2.5">
            {{ $actions }}
        </div>
    @endisset
</div>
