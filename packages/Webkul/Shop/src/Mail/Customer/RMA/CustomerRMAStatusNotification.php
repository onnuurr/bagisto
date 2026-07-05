<?php

namespace Webkul\Shop\Mail\Customer\RMA;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\RMA\Contracts\RMA;
use Webkul\Shop\Mail\Mailable;

class CustomerRMAStatusNotification extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(public RMA $rma) {}

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
                $this->rma->order->customer->email,
                $this->rma->order->customer->name
            )],
            subject: $this->resolveSubject('shop.customers.rma.status', 'shop::app.rma.mail.status.title'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.customers.rma.status', 'shop::emails.customers.rma.status', [
            '{{customer_name}}' => $this->rma->order->customer->name,
            '{{rma_id}}' => '<a href="'.route('shop.customers.account.rma.view', $this->rma->id).'" style="color: #0041FF; font-weight: bold;">#'.$this->rma->id.'</a>',
            '{{rma_status}}' => $this->rma->status->title,
        ]);
    }
}
