<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Customer\Models\CustomerNote;
use Webkul\Shop\Mail\Mailable;

class NoteNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public CustomerNote $customerNote) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->customerNote->customer->email),
            ],
            subject: $this->resolveSubject('shop.customers.note', 'shop::app.emails.orders.commented.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.customers.note', 'shop::emails.customers.commented', [
            '{{customer_name}}' => $this->customerNote->customer->name,
            '{{note}}' => e($this->customerNote->note),
        ]);
    }
}
