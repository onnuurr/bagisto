<?php

namespace Webkul\Admin\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\Customer\Contracts\Customer;

class NewCustomerNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public Customer $customer,
        public string $password
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->customer->email),
            ],
            subject: $this->resolveSubject('admin.customers.new-customer', 'shop::app.emails.customers.registration.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('admin.customers.new-customer', 'shop::emails.customers.new-customer', [
            '{{customer_name}}' => $this->customer->name,
            '{{customer_email}}' => $this->customer->email,
            '{{password}}' => $this->password,
            '{{sign_in_url}}' => route('shop.customer.session.index'),
        ], layoutView: 'shop::emails.layout');
    }
}
