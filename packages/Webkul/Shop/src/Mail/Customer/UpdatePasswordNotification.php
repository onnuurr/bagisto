<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Customer\Models\Customer;
use Webkul\Shop\Mail\Mailable;

class UpdatePasswordNotification extends Mailable
{
    /**
     * Create a new message instance.
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
                new Address($this->customer->email, $this->customer->name),
            ],
            subject: $this->resolveSubject('shop.customers.update-password', 'shop::app.emails.customers.update-password.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.customers.update-password', 'shop::emails.customers.update-password', [
            '{{customer_name}}' => $this->customer->name,
        ]);
    }
}
