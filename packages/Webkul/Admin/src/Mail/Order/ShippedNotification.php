<?php

namespace Webkul\Admin\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\Sales\Contracts\Shipment;

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
                    core()->getAdminEmailDetails()['email'],
                    core()->getAdminEmailDetails()['name']
                ),
            ],
            subject: $this->resolveSubject('admin.orders.shipped', 'admin::app.emails.orders.shipped.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('admin.orders.shipped', 'admin::emails.orders.shipped', [
            '{{admin_name}}' => core()->getAdminEmailDetails()['name'],
            '{{shipment_id}}' => $this->shipment->increment_id,
            '{{order_id}}' => '<a href="'.route('admin.sales.orders.view', $this->shipment->order_id).'" style="color: #2969FF;">#'.$this->shipment->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->shipment->order->created_at, 'Y-m-d H:i:s'),
            '{{order_details}}' => view('admin::emails.orders.partials.shipped', ['shipment' => $this->shipment])->render(),
        ]);
    }
}
