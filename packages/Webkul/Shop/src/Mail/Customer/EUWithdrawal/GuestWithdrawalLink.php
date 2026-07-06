<?php

namespace Webkul\Shop\Mail\Customer\EUWithdrawal;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Shop\Mail\Mailable;

class GuestWithdrawalLink extends Mailable
{
    /**
     * Create a new mailable instance.
     */
    public function __construct(
        public string $toEmail,
        public string $signedUrl,
        public string $orderIncrementId,
    ) {}

    /**
     * Build the message envelope.
     */
    public function envelope(): Envelope
    {
        $sender = core()->getSenderEmailDetails();

        return new Envelope(
            from: new Address($sender['email'], $sender['name']),
            to: [new Address($this->toEmail)],
            subject: $this->resolveSubject('shop.customers.eu-withdrawal.guest-link', 'shop::app.eu_withdrawal.emails.guest_link.subject'),
        );
    }

    /**
     * Build the message content.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.customers.eu-withdrawal.guest-link', 'shop::emails.customers.eu-withdrawal.guest-link', [
            '{{customer_name}}' => $this->toEmail,
            '{{order_id}}' => $this->orderIncrementId,
            '{{withdrawal_link}}' => $this->signedUrl,
        ], fallbackWith: [
            'signedUrl' => $this->signedUrl,
            'orderIncrementId' => $this->orderIncrementId,
        ]);
    }
}
