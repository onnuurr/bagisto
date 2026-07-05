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

    'shop.customers.update-password' => [
        'customer_name' => 'Customer\'s full name.',
    ],

    'shop.customers.email-verification' => [
        'customer_name' => 'Customer\'s full name.',
        'verify_email_url' => 'Link to verify the customer\'s email address.',
    ],

    'shop.customers.subscribed' => [
        'customer_name' => 'Subscriber\'s name (falls back to their email if no name was given).',
        'unsubscribe_url' => 'Link to unsubscribe from the newsletter.',
    ],

    'shop.customers.note' => [
        'customer_name' => 'Customer\'s full name.',
        'note' => 'The note left on the customer\'s account.',
    ],

    'shop.customers.invoice-reminder' => [
        'customer_name' => 'Customer\'s full name.',
    ],

    'shop.customers.forgot-password' => [
        'customer_name' => 'Customer\'s full name.',
        'reset_password_url' => 'Link to reset the customer\'s password.',
    ],

    'shop.customers.gdpr.new-request' => [
        'customer_name' => 'Customer\'s full name.',
        'request_summary' => 'Summary label describing whether this is a data update or delete request.',
        'request_status' => 'Current status of the GDPR request.',
        'request_type' => 'Type of the GDPR request (update/delete).',
        'message' => 'Message attached to the GDPR request.',
    ],

    'shop.customers.gdpr.status-update' => [
        'customer_name' => 'Customer\'s full name.',
        'request_status' => 'Current status of the GDPR request.',
        'request_type' => 'Type of the GDPR request (update/delete).',
        'message' => 'Message attached to the GDPR request.',
    ],

    'shop.customers.rma.new-request' => [
        'customer_name' => 'Customer\'s full name.',
        'order_id' => 'Order number, linked to the order.',
        'rma_details' => 'Auto-generated block with the RMA id, order id, additional information and requested products.',
    ],

    'shop.customers.rma.status' => [
        'customer_name' => 'Customer\'s full name.',
        'rma_id' => 'RMA number, linked to the RMA.',
        'rma_status' => 'Current status of the RMA.',
    ],

    'shop.customers.rma.conversation' => [
        'customer_name' => 'Customer\'s full name.',
        'message' => 'The message sent by the admin.',
    ],

    'shop.customers.eu-withdrawal.confirmation' => [
        'customer_name' => 'Customer\'s email address.',
        'title' => 'Status-dependent title (received/refunded/declined).',
        'intro' => 'Status-dependent introductory legal text.',
        'withdrawal_details' => 'Auto-generated block with the withdrawal reference, dates, order and reason.',
    ],

    'shop.customers.eu-withdrawal.guest-link' => [
        'customer_name' => 'The email address the withdrawal link was requested for.',
        'order_id' => 'Order increment ID.',
        'withdrawal_link' => 'Signed link to file the withdrawal.',
    ],

    'shop.contact-us' => [
        'name' => 'Name entered in the contact form.',
        'email' => 'Email address entered in the contact form.',
        'contact' => 'Phone number entered in the contact form (may be empty).',
        'message' => 'Message entered in the contact form.',
    ],

    'admin.orders.inventory-source' => [
        'contact_name' => 'Inventory source contact name.',
        'shipment_id' => 'Shipment number.',
        'order_id' => 'Order number, linked to the order in the admin panel.',
        'order_date' => 'Date the order was placed.',
        'order_details' => 'Auto-generated block with the shipping/billing address, shipped items and carrier/tracking info.',
    ],

    'admin.customers.new-customer' => [
        'customer_name' => 'Customer\'s full name.',
        'customer_email' => 'Customer\'s email address (used as their username).',
        'password' => 'The plain-text password generated for the customer.',
        'sign_in_url' => 'Link to the storefront sign-in page.',
    ],

    'admin.customers.gdpr.new-request' => [
        'admin_name' => 'Admin\'s name.',
        'customer_name' => 'Customer\'s full name.',
        'request_summary' => 'Summary label describing whether this is a data update or delete request.',
        'request_status' => 'Current status of the GDPR request.',
        'request_type' => 'Type of the GDPR request (update/delete).',
        'message' => 'Message attached to the GDPR request.',
    ],

    'admin.customers.gdpr.status-update' => [
        'admin_name' => 'Admin\'s name.',
        'request_status' => 'Current status of the GDPR request.',
        'request_type' => 'Type of the GDPR request (update/delete).',
        'message' => 'Message attached to the GDPR request.',
    ],

    'admin.reset-password' => [
        'admin_name' => 'Admin\'s name.',
        'reset_password_url' => 'Link to reset the admin\'s password.',
    ],

    'admin.backup-codes' => [
        'admin_name' => 'Admin\'s name.',
        'backup_codes' => 'Auto-generated grid with the two-factor authentication backup codes.',
    ],

    'admin.rma.conversation' => [
        'admin_name' => 'Admin\'s name.',
        'message' => 'The message sent by the customer.',
    ],
];
