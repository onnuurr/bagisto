<?php

namespace Webkul\Admin\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\Sales\Contracts\Refund;

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
                    core()->getAdminEmailDetails()['email'],
                    core()->getAdminEmailDetails()['name']
                ),
            ],
            subject: $this->resolveSubject('admin.orders.refunded', 'admin::app.emails.orders.refunded.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('admin.orders.refunded', 'admin::emails.orders.refunded', [
            '{{admin_name}}' => core()->getAdminEmailDetails()['name'],
            '{{invoice_id}}' => $this->refund->increment_id,
            '{{order_id}}' => '<a href="'.route('admin.sales.orders.view', $this->refund->order_id).'" style="color: #2969FF;">#'.$this->refund->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->refund->order->created_at, 'Y-m-d H:i:s'),
            '{{order_details}}' => view('admin::emails.orders.partials.refunded', ['refund' => $this->refund])->render(),
        ]);
    }
}
