<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Sales\Contracts\Shipment;
use Webkul\Shop\Mail\Mailable;

class ShippedNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Shipment $shipment) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->shipment->order->customer_email,
                    $this->shipment->order->customer_full_name
                ),
            ],
            subject: $this->resolveSubject('shop.orders.shipped', 'shop::app.emails.orders.shipped.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.orders.shipped', 'shop::emails.orders.shipped', [
            '{{customer_name}}' => $this->shipment->order->customer_full_name,
            '{{shipment_id}}' => $this->shipment->increment_id,
            '{{order_id}}' => '<a href="'.route('shop.customers.account.orders.view', $this->shipment->order_id).'" style="color: #2969FF;">#'.$this->shipment->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->shipment->order->created_at, 'Y-m-d H:i:s'),
            '{{order_details}}' => view('shop::emails.orders.partials.shipped', ['shipment' => $this->shipment])->render(),
        ]);
    }
}
