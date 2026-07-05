<?php

namespace Webkul\Admin\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\Customer\Contracts\Customer;

class RegistrationNotification extends Mailable
{
    /**
     * Create a new mailable instance.
     *
     * @return void
     */
    public function __construct(public Customer $customer) {}

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
            subject: $this->resolveSubject('admin.customers.registration', 'admin::app.emails.customers.registration.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('admin.customers.registration', 'admin::emails.customers.registration', [
            '{{admin_name}}' => core()->getAdminEmailDetails()['name'],
            '{{customer_name}}' => '<a href="'.route('admin.customers.customers.view', $this->customer->id).'" style="color: #2969FF;">'.$this->customer->name.'</a>',
        ]);
    }
}
