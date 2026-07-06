<?php

namespace Webkul\Admin\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\Sales\Contracts\Order;

class CanceledNotification extends Mailable
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
                    core()->getAdminEmailDetails()['email'],
                    core()->getAdminEmailDetails()['name']
                ),
            ],
            subject: $this->resolveSubject('admin.orders.canceled', 'admin::app.emails.orders.canceled.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('admin.orders.canceled', 'admin::emails.orders.canceled', [
            '{{admin_name}}' => core()->getAdminEmailDetails()['name'],
            '{{order_id}}' => '<a href="'.route('admin.sales.orders.view', $this->order->id).'" style="color: #2969FF;">#'.$this->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->order->created_at, 'Y-m-d H:i:s'),
            '{{order_details}}' => view('admin::emails.orders.partials.canceled', ['order' => $this->order])->render(),
        ]);
    }
}
