@php
    $statusIntroKey = 'shop::app.eu_withdrawal.emails.confirmation.intro_'.$withdrawal->status;
    $statusIntroKey = trans()->has($statusIntroKey) ? $statusIntroKey : 'shop::app.eu_withdrawal.emails.confirmation.intro';

    $titleKey = 'shop::app.eu_withdrawal.emails.confirmation.title_'.$withdrawal->status;
    $titleKey = trans()->has($titleKey) ? $titleKey : 'shop::app.eu_withdrawal.emails.confirmation.title';
@endphp

@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #121A26;">
            @lang($titleKey)
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('shop::app.emails.dear', ['customer_name' => $withdrawal->customer_email]), 👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang($statusIntroKey, [
                'order_id' => $withdrawal->order->increment_id ?? $withdrawal->order_id,
            ])
        </p>
    </div>

    @include('shop::emails.customers.eu-withdrawal.partials.confirmation', ['withdrawal' => $withdrawal])
@endcomponent
