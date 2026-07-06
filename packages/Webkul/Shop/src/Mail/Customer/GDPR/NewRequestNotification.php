<?php

namespace Webkul\Shop\Mail\Customer\GDPR;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\GDPR\Contracts\GDPRDataRequest;

class NewRequestNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public GDPRDataRequest $gdprRequest) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjectKey = $this->gdprRequest->type === 'update'
            ? 'shop::app.emails.customers.gdpr.new-update-request'
            : 'shop::app.emails.customers.gdpr.new-delete-request';

        return new Envelope(
            to: [
                new Address($this->gdprRequest->email),
            ],
            subject: $this->resolveSubject('shop.customers.gdpr.new-request', $subjectKey),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $summaryKey = $this->gdprRequest->type === 'update'
            ? 'shop::app.emails.customers.gdpr.new-request.update-summary'
            : 'shop::app.emails.customers.gdpr.new-request.delete-summary';

        return $this->resolveContent('shop.customers.gdpr.new-request', 'shop::emails.customers.gdpr.new-request', [
            '{{customer_name}}' => $this->gdprRequest->customer->name,
            '{{request_summary}}' => trans($summaryKey),
            '{{request_status}}' => $this->gdprRequest->status,
            '{{request_type}}' => $this->gdprRequest->type,
            '{{message}}' => e($this->gdprRequest->message),
        ], layoutView: 'shop::emails.layout');
    }
}
