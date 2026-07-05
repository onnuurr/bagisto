@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

@push('scripts')
    @if(! empty($categories))
        <script>
            localStorage.setItem('categories', JSON.stringify(@json($categories)));
        </script>
    @endif
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <!-- Loop over the theme customization -->
    @foreach ($customizations as $customization)
        @php
            $data = $customization->options;

            $themeBlockDefinition = app(\Webkul\Theme\ThemeBlockRegistry::class)->get($customization->type);
        @endphp

        {{--
            Rendered via the block's `shop_view`, registered in
            Webkul\Theme\ThemeBlockRegistry (see ThemeServiceProvider for the
            core blocks). Blocks rendered by a dedicated layout slot instead
            (footer links, services strip) register a null `shop_view` and
            are intentionally skipped here.
        --}}
        @includeWhen(! empty($themeBlockDefinition['shop_view']), $themeBlockDefinition['shop_view'] ?? '')
    @endforeach
</x-shop::layouts>
