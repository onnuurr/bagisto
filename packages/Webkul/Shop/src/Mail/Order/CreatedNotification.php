<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Sales\Contracts\Order;
use Webkul\Shop\Mail\Mailable;

class CreatedNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Order $order) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->order->customer_email,
                    $this->order->customer_full_name
                ),
            ],
            subject: $this->resolveSubject('shop.orders.created', 'shop::app.emails.orders.created.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.orders.created', 'shop::emails.orders.created', [
            '{{customer_name}}' => $this->order->customer_full_name,
            '{{order_id}}' => '<a href="'.route('shop.customers.account.orders.view', $this->order->id).'" style="color: #2969FF;">#'.$this->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->order->created_at, 'Y-m-d H:i:s'),
            '{{order_details}}' => view('shop::emails.orders.partials.created', ['order' => $this->order])->render(),
        ]);
    }
}
