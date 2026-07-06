@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #121A26">
            @lang('admin::app.emails.orders.created.title')
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('admin::app.emails.dear', ['admin_name' => core()->getAdminEmailDetails()['name']]),👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {!! trans('admin::app.emails.orders.created.greeting', [
                'order_id' => '<a href="' . route('admin.sales.orders.view', $order->id) . '" style="color: #2969FF;">#' . $order->increment_id . '</a>',
                'created_at' => core()->formatDate($order->created_at, 'Y-m-d H:i:s')
                ])
            !!}
        </p>
    </div>

    @include('admin::emails.orders.partials.created', ['order' => $order])
@endcomponent
