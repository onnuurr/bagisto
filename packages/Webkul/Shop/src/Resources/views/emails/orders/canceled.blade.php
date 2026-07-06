@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #121A26">
            @lang('shop::app.emails.orders.canceled.title')
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('shop::app.emails.dear', ['customer_name' => $order->customer_full_name]),👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {!! trans('shop::app.emails.orders.canceled.greeting', [
                'order_id' => '<a href="' . route('shop.customers.account.orders.view', $order->id) . '" style="color: #2969FF;">#' . $order->increment_id . '</a>',
                'created_at' => core()->formatDate($order->created_at, 'Y-m-d H:i:s')
                ])
            !!}
        </p>
    </div>

    @include('shop::emails.orders.partials.canceled', ['order' => $order])
@endcomponent
