<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Customer\Contracts\Customer;
use Webkul\Shop\Mail\Mailable;

class EmailVerificationNotification extends Mailable
{
    /**
     * Create a new mailable instance.
     *
     * @return void
     */
    public function __construct(public Customer $customer) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->customer->email),
            ],
            subject: $this->resolveSubject('shop.customers.email-verification', 'shop::app.emails.customers.verification.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.customers.email-verification', 'shop::emails.customers.email-verification', [
            '{{customer_name}}' => $this->customer->name,
            '{{verify_email_url}}' => route('shop.customers.verify', $this->customer->token),
        ]);
    }
}
