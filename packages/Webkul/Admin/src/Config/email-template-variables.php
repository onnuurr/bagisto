<?php

/**
 * List of variables (tokens) that can be used inside a customizable
 * notification email's subject/content, keyed by the email template's
 * unique `code`. Used only to render the reference list on the
 * Settings > Notification Emails edit screen.
 */
return [
    'shop.orders.created' => [
        'customer_name' => 'Customer\'s full name.',
        'order_id' => 'Order number, linked to the order.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, items and totals.',
    ],

    'shop.orders.canceled' => [
        'customer_name' => 'Customer\'s full name.',
        'order_id' => 'Order number, linked to the order.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, items and totals.',
    ],

    'shop.orders.shipped' => [
        'customer_name' => 'Customer\'s full name.',
        'shipment_id' => 'Shipment number.',
        'order_id' => 'Order number, linked to the order.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, shipped items and carrier/tracking info.',
    ],

    'shop.orders.invoiced' => [
        'customer_name' => 'Customer\'s full name.',
        'invoice_id' => 'Invoice number.',
        'order_id' => 'Order number, linked to the order.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, invoiced items and totals.',
    ],

    'shop.orders.refunded' => [
        'customer_name' => 'Customer\'s full name.',
        'invoice_id' => 'Refund number.',
        'order_id' => 'Order number, linked to the order.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, refunded items and totals.',
    ],

    'shop.orders.commented' => [
        'customer_name' => 'Customer\'s full name.',
        'order_id' => 'Order number, linked to the order.',
        'order_date' => 'Date the order was placed.',
        'comment' => 'The comment left on the order.',
    ],

    'shop.customers.registration' => [
        'customer_name' => 'Customer\'s full name.',
        'sign_in_url' => 'Link to the storefront sign-in page.',
    ],

    'admin.orders.created' => [
        'admin_name' => 'Admin\'s name.',
        'order_id' => 'Order number, linked to the order in the admin panel.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, items and totals.',
    ],

    'admin.orders.canceled' => [
        'admin_name' => 'Admin\'s name.',
        'order_id' => 'Order number, linked to the order in the admin panel.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, items and totals.',
    ],

    'admin.orders.shipped' => [
        'admin_name' => 'Admin\'s name.',
        'shipment_id' => 'Shipment number.',
        'order_id' => 'Order number, linked to the order in the admin panel.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, shipped items and carrier/tracking info.',
    ],

    'admin.orders.invoiced' => [
        'admin_name' => 'Admin\'s name.',
        'invoice_id' => 'Invoice number.',
        'order_id' => 'Order number, linked to the order in the admin panel.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, invoiced items and totals.',
    ],

    'admin.orders.refunded' => [
        'admin_name' => 'Admin\'s name.',
        'invoice_id' => 'Refund number.',
        'order_id' => 'Order number, linked to the order in the admin panel.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, refunded items and totals.',
    ],

    'admin.customers.registration' => [
        'admin_name' => 'Admin\'s name.',
        'customer_name' => 'The newly registered customer\'s name, linked to their profile.',
    ],
];
