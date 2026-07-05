<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Core\Contracts\SubscribersList;
use Webkul\Shop\Mail\Mailable;

class SubscriptionNotification extends Mailable
{
    /**
     * Create a mailable instance
     *
     * @return void
     */
    public function __construct(public SubscribersList $subscribersList) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->subscribersList->email),
            ],
            subject: $this->resolveSubject('shop.customers.subscribed', 'shop::app.emails.customers.subscribed.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $fullName = trim($this->subscribersList->first_name.' '.$this->subscribersList->last_name);

        return $this->resolveContent('shop.customers.subscribed', 'shop::emails.customers.subscribed', [
            '{{customer_name}}' => $fullName ?: $this->subscribersList->email,
            '{{unsubscribe_url}}' => route('shop.subscription.destroy', $this->subscribersList->token),
        ], fallbackWith: ['fullName' => $fullName]);
    }
}
