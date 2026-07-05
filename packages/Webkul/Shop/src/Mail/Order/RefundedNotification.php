<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Sales\Contracts\Refund;
use Webkul\Shop\Mail\Mailable;

class RefundedNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Refund $refund) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->refund->order->customer_email,
                    $this->refund->order->customer_full_name
                ),
            ],
            subject: $this->resolveSubject('shop.orders.refunded', 'shop::app.emails.orders.refunded.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.orders.refunded', 'shop::emails.orders.refunded', [
            '{{customer_name}}' => $this->refund->order->customer_full_name,
            '{{invoice_id}}' => $this->refund->increment_id,
            '{{order_id}}' => '<a href="'.route('shop.customers.account.orders.view', $this->refund->order_id).'" style="color: #2969FF;">#'.$this->refund->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->refund->order->created_at, 'Y-m-d H:i:s'),
            '{{order_details}}' => view('shop::emails.orders.partials.refunded', ['refund' => $this->refund])->render(),
        ]);
    }
}
