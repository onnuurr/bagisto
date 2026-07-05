<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Core\Traits\PDFHandler;
use Webkul\Sales\Contracts\Invoice;
use Webkul\Shop\Mail\Mailable;

class InvoicedNotification extends Mailable
{
    use PDFHandler;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public Invoice $invoice,
        public ?string $duplicateInvoiceEmail = null
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->duplicateInvoiceEmail ?? $this->invoice->order->customer_email,
                    $this->invoice->order->customer_full_name
                ),
            ],
            subject: $this->resolveSubject('shop.orders.invoiced', 'shop::app.emails.orders.invoiced.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->resolveContent('shop.orders.invoiced', 'shop::emails.orders.invoiced', [
            '{{customer_name}}' => $this->invoice->order->customer_full_name,
            '{{invoice_id}}' => $this->invoice->increment_id,
            '{{order_id}}' => '<a href="'.route('shop.customers.account.orders.view', $this->invoice->order_id).'" style="color: #2969FF;">#'.$this->invoice->order->increment_id.'</a>',
            '{{order_date}}' => core()->formatDate($this->invoice->order->created_at, 'Y-m-d H:i:s'),
            '{{order_details}}' => view('shop::emails.orders.partials.invoiced', ['invoice' => $this->invoice])->render(),
        ]);
    }

    /**
     * Get the attachments.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        try {
            $orderCurrencyCode = $this->invoice->order->order_currency_code;

            $pdfContent = $this->generatePdf(
                view('shop::customers.account.orders.pdf', [
                    'invoice' => $this->invoice,
                    'orderCurrencyCode' => $orderCurrencyCode,
                ])->render(),
                'invoice-'.$this->invoice->created_at->format('d-m-Y')
            );

            return [
                Attachment::fromData(
                    fn () => $pdfContent,
                    'invoice-'.$this->invoice->id.'.pdf'
                )->withMime('application/pdf'),
            ];

        } catch (\Exception $e) {
            report($e);

            return [];
        }
    }
}
