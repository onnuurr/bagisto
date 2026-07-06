<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Sales\Contracts\OrderComment;
use Webkul\Shop\Mail\Mailable;

class CommentedNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public OrderComment $comment) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->comment->order->customer_email, $this->comment->order->customer_full_name),
            ],
            subject: $this->resolveSubject('shop.orders.commented', 'shop::app.emails.orders.commented.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.orders.commented', 'shop::emails.orders.commented', [
            '{{customer_name}}' => $this->comment->order->customer_full_name,
            '{{order_id}}' => '<a href="'.route('shop.customers.account.orders.view', $this->comment->order_id).'" style="color: #2969FF;">#'.$this->comment->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->comment->order->created_at, 'Y-m-d H:i:s'),
            '{{comment}}' => e($this->comment->comment),
        ]);
    }
}
