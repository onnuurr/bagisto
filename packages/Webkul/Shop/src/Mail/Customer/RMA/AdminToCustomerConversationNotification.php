<?php

namespace Webkul\Shop\Mail\Customer\RMA;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\RMA\Contracts\RMAMessage;
use Webkul\Shop\Mail\Mailable;

class AdminToCustomerConversationNotification extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(public RMAMessage $rmaMessage) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                core()->getSenderEmailDetails()['email'],
                core()->getSenderEmailDetails()['name']
            ),
            to: [new Address(
                $this->rmaMessage->rma->order->customer->email,
                $this->rmaMessage->rma->order->customer->name
            )],
            subject: $this->resolveSubject('shop.customers.rma.conversation', 'shop::app.rma.mail.customer-conversation.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.customers.rma.conversation', 'shop::emails.customers.rma.conversation.message', [
            '{{customer_name}}' => $this->rmaMessage->rma->order->customer->name,
            '{{message}}' => e($this->rmaMessage->message),
        ]);
    }
}
