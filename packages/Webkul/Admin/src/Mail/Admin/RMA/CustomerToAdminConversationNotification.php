<?php

namespace Webkul\Admin\Mail\Admin\RMA;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\RMA\Contracts\RMAMessage;

class CustomerToAdminConversationNotification extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(public RMAMessage $rmaMessage) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                $this->rmaMessage->rma->order->customer->email,
                $this->rmaMessage->rma->order->customer->name
            ),
            to: [new Address(
                core()->getConfigData('emails.configure.email_settings.admin_email') ?: config('mail.admin.address'),
                core()->getConfigData('emails.configure.email_settings.admin_name') ?: config('mail.admin.name')
            )],
            subject: $this->resolveSubject('admin.rma.conversation', 'admin::app.emails.rma.conversation.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('admin.rma.conversation', 'admin::emails.rma.conversation.message', [
            '{{admin_name}}' => core()->getAdminEmailDetails()['name'],
            '{{message}}' => e($this->rmaMessage->message),
        ]);
    }
}
