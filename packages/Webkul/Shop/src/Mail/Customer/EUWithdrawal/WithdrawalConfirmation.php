<?php

namespace Webkul\Shop\Mail\Customer\EUWithdrawal;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\EUWithdrawal\Contracts\Withdrawal as WithdrawalContract;
use Webkul\Shop\Mail\Mailable;

class WithdrawalConfirmation extends Mailable
{
    /**
     * Create a new mailable instance.
     */
    public function __construct(public WithdrawalContract $withdrawal) {}

    /**
     * Build the message envelope.
     */
    public function envelope(): Envelope
    {
        $sender = core()->getSenderEmailDetails();

        return new Envelope(
            from: new Address($sender['email'], $sender['name']),
            to: [new Address($this->withdrawal->customer_email)],
            subject: $this->resolveSubject('shop.customers.eu-withdrawal.confirmation', 'shop::app.eu_withdrawal.emails.confirmation.subject', [
                'order_id' => $this->withdrawal->order->increment_id ?? $this->withdrawal->order_id,
            ]),
        );
    }

    /**
     * Build the message content.
     */
    public function content(): Content
    {
        $statusIntroKey = 'shop::app.eu_withdrawal.emails.confirmation.intro_'.$this->withdrawal->status;
        $statusIntroKey = trans()->has($statusIntroKey) ? $statusIntroKey : 'shop::app.eu_withdrawal.emails.confirmation.intro';

        $titleKey = 'shop::app.eu_withdrawal.emails.confirmation.title_'.$this->withdrawal->status;
        $titleKey = trans()->has($titleKey) ? $titleKey : 'shop::app.eu_withdrawal.emails.confirmation.title';

        return $this->resolveContent('shop.customers.eu-withdrawal.confirmation', 'shop::emails.customers.eu-withdrawal.confirmation', [
            '{{customer_name}}' => $this->withdrawal->customer_email,
            '{{title}}' => trans($titleKey),
            '{{intro}}' => trans($statusIntroKey, [
                'order_id' => $this->withdrawal->order->increment_id ?? $this->withdrawal->order_id,
            ]),
            '{{withdrawal_details}}' => view('shop::emails.customers.eu-withdrawal.partials.confirmation', ['withdrawal' => $this->withdrawal])->render(),
        ], fallbackWith: ['withdrawal' => $this->withdrawal]);
    }
}
