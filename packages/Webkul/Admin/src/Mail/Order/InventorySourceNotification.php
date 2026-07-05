<?php

namespace Webkul\Admin\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\Sales\Contracts\Shipment;

class InventorySourceNotification extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(public Shipment $shipment) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $inventory = $this->shipment->inventory_source;

        return new Envelope(
            to: [
                new Address(
                    $inventory->contact_email,
                    $inventory->contact_name
                ),
            ],
            subject: $this->resolveSubject('admin.orders.inventory-source', 'admin::app.emails.orders.inventory-source.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('admin.orders.inventory-source', 'admin::emails.orders.inventory-source', [
            '{{contact_name}}' => $this->shipment->inventory_source->contact_name,
            '{{shipment_id}}' => $this->shipment->increment_id,
            '{{order_id}}' => '<a href="'.route('admin.sales.orders.view', $this->shipment->order_id).'" style="color: #2969FF;">#'.$this->shipment->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->shipment->order->created_at, 'Y-m-d H:i:s'),
            '{{order_details}}' => view('admin::emails.orders.partials.inventory-source', ['shipment' => $this->shipment])->render(),
        ]);
    }
}
