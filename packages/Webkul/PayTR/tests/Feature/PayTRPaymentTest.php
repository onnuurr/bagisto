<?php

use Illuminate\Support\Facades\Http;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Core\Models\CoreConfig;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

beforeEach(function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_id',
        'value' => 'test_merchant_id',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_key',
        'value' => 'test_merchant_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_salt',
        'value' => 'test_merchant_salt',
        'channel_code' => 'default',
    ]);
});

function payTRNotificationHash(string $merchantOid, string $status, string $totalAmount): string
{
    return base64_encode(hash_hmac(
        'sha256',
        $merchantOid.'test_merchant_salt'.$status.$totalAmount,
        'test_merchant_key',
        true
    ));
}

it('redirects back when paytr credentials are invalid', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_id',
        'value' => '',
        'channel_code' => 'default',
    ]);

    // Act
    $response = $this->get(route('paytr.redirect'));

    // Assert
    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('redirects back when cart is not found', function () {
    // Arrange
    Cart::shouldReceive('getCart')->andReturn(null);

    // Act
    $response = $this->get(route('paytr.redirect'));

    // Assert
    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('requests an iframe token and renders the embedded checkout page', function () {
    // Arrange
    $this->createCartWithItems('paytr');

    Http::fake([
        'https://www.paytr.com/odeme/api/get-token' => Http::response([
            'status' => 'success',
            'token' => 'FAKE_TOKEN_123',
        ]),
    ]);

    // Act
    $response = $this->get(route('paytr.redirect'));

    // Assert
    $response->assertOk();

    $response->assertViewIs('paytr::checkout.redirect');

    $response->assertViewHas('iframeUrl', 'https://www.paytr.com/odeme/guvenli/FAKE_TOKEN_123');
});

it('redirects back when the get-token request fails', function () {
    // Arrange
    $this->createCartWithItems('paytr');

    Http::fake([
        'https://www.paytr.com/odeme/api/get-token' => Http::response([
            'status' => 'failed',
            'reason' => 'invalid merchant',
        ]),
    ]);

    // Act
    $response = $this->get(route('paytr.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('rejects the notification when the hash is invalid', function () {
    // Arrange
    $cart = $this->createCartWithItems('paytr');

    // Act
    $response = $this->post(route('paytr.notify'), [
        'merchant_oid' => 'CART'.$cart->id.'TABCDEF1',
        'status' => 'success',
        'total_amount' => (string) (int) round($cart->base_grand_total * 100),
        'hash' => 'invalid_hash_value',
    ]);

    // Assert
    $response->assertStatus(400);

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('creates an order and invoice from a valid success notification', function () {
    // Arrange
    $cart = $this->createCartWithItems('paytr');

    $merchantOid = 'CART'.$cart->id.'TABCDEF1';

    $totalAmount = (string) (int) round($cart->base_grand_total * 100);

    // Act
    $response = $this->post(route('paytr.notify'), [
        'merchant_oid' => $merchantOid,
        'status' => 'success',
        'total_amount' => $totalAmount,
        'payment_type' => 'card',
        'hash' => payTRNotificationHash($merchantOid, 'success', $totalAmount),
    ]);

    // Assert
    $response->assertOk();

    $response->assertSeeText('OK');

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing')
        ->and($order->customer_id)->toBe($cart->customer_id);

    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull()
        ->and($invoice->state)->toBe('paid');

    $orderTransaction = OrderTransaction::where('order_id', $order->id)->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->status)->toBe('success')
        ->and($orderTransaction->type)->toBe('paytr');
});

it('does not create a duplicate order when the notification is received twice', function () {
    // Arrange
    $cart = $this->createCartWithItems('paytr');

    $merchantOid = 'CART'.$cart->id.'TABCDEF1';

    $totalAmount = (string) (int) round($cart->base_grand_total * 100);

    $payload = [
        'merchant_oid' => $merchantOid,
        'status' => 'success',
        'total_amount' => $totalAmount,
        'hash' => payTRNotificationHash($merchantOid, 'success', $totalAmount),
    ];

    // Act
    $this->post(route('paytr.notify'), $payload);

    $this->post(route('paytr.notify'), $payload);

    // Assert
    expect(Order::where('cart_id', $cart->id)->count())->toBe(1);
});

it('does not create an order for a failed notification', function () {
    // Arrange
    $cart = $this->createCartWithItems('paytr');

    $merchantOid = 'CART'.$cart->id.'TABCDEF1';

    $totalAmount = (string) (int) round($cart->base_grand_total * 100);

    // Act
    $response = $this->post(route('paytr.notify'), [
        'merchant_oid' => $merchantOid,
        'status' => 'failed',
        'total_amount' => $totalAmount,
        'hash' => payTRNotificationHash($merchantOid, 'failed', $totalAmount),
    ]);

    // Assert
    $response->assertOk();

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('redirects to success once the order created by the notification is found', function () {
    // Arrange
    $cart = $this->createCartWithItems('paytr');

    $merchantOid = 'CART'.$cart->id.'TABCDEF1';

    $totalAmount = (string) (int) round($cart->base_grand_total * 100);

    $this->post(route('paytr.notify'), [
        'merchant_oid' => $merchantOid,
        'status' => 'success',
        'total_amount' => $totalAmount,
        'hash' => payTRNotificationHash($merchantOid, 'success', $totalAmount),
    ]);

    $order = Order::where('cart_id', $cart->id)->firstOrFail();

    // Act
    $response = $this->get(route('paytr.ok'));

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $response->assertSessionHas('order_id', $order->id);
});

it('redirects to cart with an info message when the notification has not arrived yet', function () {
    // Arrange
    $this->createCartWithItems('paytr');

    // Act
    $response = $this->get(route('paytr.ok'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('info');
})->group('slow');

it('redirects to cart with an error on payment failure', function () {
    // Arrange
    CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    // Act
    $response = $this->get(route('paytr.fail'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});
