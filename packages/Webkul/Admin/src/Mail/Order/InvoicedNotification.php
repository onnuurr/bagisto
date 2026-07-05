<?php

namespace Webkul\Admin\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\Sales\Contracts\Invoice;

class InvoicedNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Invoice $invoice) {}

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
            subject: $this->resolveSubject('admin.orders.invoiced', 'admin::app.emails.orders.invoiced.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('admin.orders.invoiced', 'admin::emails.orders.invoiced', [
            '{{admin_name}}' => core()->getAdminEmailDetails()['name'],
            '{{invoice_id}}' => $this->invoice->increment_id,
            '{{order_id}}' => '<a href="'.route('admin.sales.orders.view', $this->invoice->order_id).'" style="color: #2969FF;">#'.$this->invoice->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->invoice->order->created_at, 'Y-m-d H:i:s'),
            '{{order_details}}' => view('admin::emails.orders.partials.invoiced', ['invoice' => $this->invoice])->render(),
        ]);
    }
}
