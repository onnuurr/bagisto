<?php

namespace Webkul\Shop\Mail\Customer\RMA;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\RMA\Contracts\RMA;
use Webkul\Shop\Mail\Mailable;

class CustomerRMARequestNotification extends Mailable
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
        $adminDetails = core()->getAdminEmailDetails();

        return new Envelope(
            from: new Address(
                $adminDetails['email'],
                $adminDetails['name']
            ),
            to: [new Address(
                $this->rma->order->customer->email,
                $this->rma->order->customer->name
            )],
            subject: $this->resolveSubject('shop.customers.rma.new-request', 'shop::app.rma.customer.create.heading'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.customers.rma.new-request', 'shop::emails.customers.rma.new-rma-request', [
            '{{customer_name}}' => $this->rma->order->customer->name,
            '{{order_id}}' => '<a href="'.route('shop.customers.account.orders.view', $this->rma->order_id).'" style="font-weight: 600; color: #2563eb; text-decoration: none;">#'.$this->rma->order_id.'</a>',
            '{{rma_details}}' => view('shop::emails.customers.rma.partials.new-rma-request', ['rma' => $this->rma])->render(),
        ]);
    }
}
