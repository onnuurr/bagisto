@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #121A26">
            {{ trans('admin::app.emails.orders.refunded.title') }}
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {{ trans('admin::app.emails.dear', ['admin_name' => core()->getAdminEmailDetails()['name']]) }},👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('admin::app.emails.orders.refunded.greeting', [
                'invoice_id' => $refund->increment_id,
                'order_id'   => '<a href="' . route('admin.sales.orders.view', $refund->order_id) . '" style="color: #2969FF;">#' . $refund->order->increment_id . '</a>',
                'created_at' => core()->formatDate($refund->order->created_at, 'Y-m-d H:i:s')
            ])
        </p>
    </div>

    @include('admin::emails.orders.partials.refunded', ['refund' => $refund])
@endcomponent
