<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Customer\Contracts\Customer;
use Webkul\Shop\Mail\Mailable;

class RegistrationNotification extends Mailable
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
            subject: $this->resolveSubject('shop.customers.registration', 'shop::app.emails.customers.registration.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.customers.registration', 'shop::emails.customers.registration', [
            '{{customer_name}}' => $this->customer->name,
            '{{sign_in_url}}' => route('shop.customer.session.index'),
        ]);
    }
}
