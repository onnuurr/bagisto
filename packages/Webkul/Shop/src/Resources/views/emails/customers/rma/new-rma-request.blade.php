@component('shop::emails.layout')
    <!-- Header Section -->
    <div style="margin-bottom: 40px;">
        <h1 style="font-size: 28px; font-weight: 700; color: #121A26; margin: 0 0 20px 0;">
            @lang('shop::app.rma.mail.customer-rma-create.heading')
        </h1>

        <p style="font-size: 16px; color: #5E5E5E; line-height: 26px; margin: 0 0 16px 0;">
            @lang('shop::app.rma.mail.customer-rma-create.hello', ['name' => $rma->order->customer->name]), 👋
        </p>

        <p style="font-size: 16px; color: #5E5E5E; line-height: 26px; margin: 0;">
            @lang('shop::app.rma.mail.customer-rma-create.greeting', [
                'order_id' =>
                    '<a href="' .
                    route('shop.customers.account.orders.view', $rma->order_id) .
                    '" style="font-weight: 600; color: #2563eb; text-decoration: none;">#' .
                    $rma->order_id .
                    '</a>',
            ])
        </p>
    </div>

    @include('shop::emails.customers.rma.partials.new-rma-request', ['rma' => $rma])
@endcomponent
