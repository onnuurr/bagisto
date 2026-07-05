<?php

namespace Webkul\SMS\Listeners;

use Webkul\SMS\SmsManager;

class Order
{
    /**
     * Create a new listener instance.
     */
    public function __construct(protected SmsManager $smsManager) {}

    /**
     * Notify the customer when an order is placed.
     */
    public function orderPlaced($order): void
    {
        $this->notify($order, 'order_placed');
    }

    /**
     * Notify the customer when an order is cancelled.
     */
    public function orderCancelled($order): void
    {
        $this->notify($order, 'order_cancelled');
    }

    /**
     * Notify the customer when a shipment is created for their order.
     */
    public function shipmentCreated($shipment): void
    {
        $this->notify($shipment->order, 'order_shipped');
    }

    /**
     * Notify the customer when an invoice is created for their order.
     */
    public function invoiceCreated($invoice): void
    {
        $this->notify($invoice->order, 'invoice_created');
    }

    /**
     * Notify the customer when a refund is created for their order.
     */
    public function refundCreated($refund): void
    {
        $this->notify($refund->order, 'refund_created');
    }

    /**
     * Build the SMS message for the given order/event and send it.
     */
    protected function notify($order, string $event): void
    {
        if (
            ! $order
            || ! core()->getConfigData("sms.notifications.{$event}.enabled")
        ) {
            return;
        }

        $address = $order->shipping_address ?: $order->billing_address;

        if (! $address || ! $address->phone) {
            return;
        }

        $template = core()->getConfigData("sms.notifications.{$event}.template")
            ?: trans("admin::app.sms.notifications.{$event}.default-template");

        $message = strtr($template, [
            '{order_id}'       => $order->increment_id,
            '{customer_name}'  => $address->name ?: $order->customer_full_name,
            '{order_total}'    => core()->formatPrice($order->grand_total, $order->order_currency_code),
        ]);

        $this->smsManager->send($address->phone, $message, $event);
    }
}
