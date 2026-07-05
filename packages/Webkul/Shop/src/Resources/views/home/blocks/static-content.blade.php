{{-- Push Style --}}
@if (! empty($data['css']))
    @push ('styles')
        <style>
            {!! $data['css'] !!}
        </style>
    @endpush
@endif

{{-- Render HTML --}}
@if (! empty($data['html']))
    {!! $data['html'] !!}
@endif
