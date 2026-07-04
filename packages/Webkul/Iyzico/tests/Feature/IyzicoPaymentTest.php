<?php

use Iyzipay\Model\CheckoutForm;
use Iyzipay\Model\CheckoutFormInitialize;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Core\Models\CoreConfig;
use Webkul\Iyzico\Payment\Iyzico as IyzicoPayment;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

beforeEach(function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.api_key',
        'value' => 'test_api_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.secret_key',
        'value' => 'test_secret_key',
        'channel_code' => 'default',
    ]);
});

it('redirects back when iyzico credentials are invalid', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.api_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    $response = $this->get(route('iyzico.redirect'));

    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('redirects back when cart is not found', function () {
    Cart::shouldReceive('getCart')->andReturn(null);

    $response = $this->get(route('iyzico.redirect'));

    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('renders the embedded checkout form when initialize succeeds', function () {
    $this->createCartWithItems('iyzico');

    $fakeResource = new CheckoutFormInitialize;
    $fakeResource->setStatus('success');
    $fakeResource->setConversationId('conv-1');
    $fakeResource->setToken('token-abc');
    $fakeResource->setCheckoutFormContent('<script>fake iyzico form</script>');
    $fakeResource->setSignature(bin2hex(hash_hmac('sha256', 'conv-1:token-abc', 'test_secret_key', true)));

    $mock = $this->mock(IyzicoPayment::class)->makePartial();
    $mock->shouldReceive('initializeCheckoutForm')->andReturn($fakeResource);
    $this->app->instance(IyzicoPayment::class, $mock);

    $response = $this->get(route('iyzico.redirect'));

    $response->assertOk();

    $response->assertViewIs('iyzico::checkout.redirect');

    $response->assertSee('fake iyzico form', false);
});

it('redirects back when the initialize signature is invalid', function () {
    $this->createCartWithItems('iyzico');

    $fakeResource = new CheckoutFormInitialize;
    $fakeResource->setStatus('success');
    $fakeResource->setConversationId('conv-1');
    $fakeResource->setToken('token-abc');
    $fakeResource->setCheckoutFormContent('<script>fake</script>');
    $fakeResource->setSignature('tampered-signature');

    $mock = $this->mock(IyzicoPayment::class)->makePartial();
    $mock->shouldReceive('initializeCheckoutForm')->andReturn($fakeResource);
    $this->app->instance(IyzicoPayment::class, $mock);

    $response = $this->get(route('iyzico.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

function fakeCheckoutFormResult(string $basketId, string $paymentStatus, string $totalPrice, string $secret): CheckoutForm
{
    $fields = [$paymentStatus, 'pay-1', 'TRY', $basketId, 'conv-1', $totalPrice, $totalPrice, 'token-abc'];

    $checkoutForm = new CheckoutForm;
    $checkoutForm->setStatus('success');
    $checkoutForm->setPaymentStatus($fields[0]);
    $checkoutForm->setPaymentId($fields[1]);
    $checkoutForm->setCurrency($fields[2]);
    $checkoutForm->setBasketId($fields[3]);
    $checkoutForm->setConversationId($fields[4]);
    $checkoutForm->setPaidPrice($fields[5]);
    $checkoutForm->setPrice($fields[6]);
    $checkoutForm->setToken($fields[7]);
    $checkoutForm->setSignature(bin2hex(hash_hmac('sha256', implode(':', $fields), $secret, true)));

    return $checkoutForm;
}

it('creates an order and invoice from a successful callback', function () {
    $cart = $this->createCartWithItems('iyzico');

    $fakeResult = fakeCheckoutFormResult('CART'.$cart->id, 'SUCCESS', (string) $cart->base_grand_total, 'test_secret_key');

    $mock = $this->mock(IyzicoPayment::class)->makePartial();
    $mock->shouldReceive('retrieveCheckoutForm')->andReturn($fakeResult);
    $this->app->instance(IyzicoPayment::class, $mock);

    $response = $this->post(route('iyzico.callback'), ['token' => 'token-abc']);

    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull()
        ->and($invoice->state)->toBe('paid');

    $orderTransaction = OrderTransaction::where('order_id', $order->id)->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->type)->toBe('iyzico');
});

it('does not create a duplicate order when the callback is received twice', function () {
    $cart = $this->createCartWithItems('iyzico');

    $fakeResult = fakeCheckoutFormResult('CART'.$cart->id, 'SUCCESS', (string) $cart->base_grand_total, 'test_secret_key');

    $mock = $this->mock(IyzicoPayment::class)->makePartial();
    $mock->shouldReceive('retrieveCheckoutForm')->andReturn($fakeResult);
    $this->app->instance(IyzicoPayment::class, $mock);

    $this->post(route('iyzico.callback'), ['token' => 'token-abc']);
    $this->post(route('iyzico.callback'), ['token' => 'token-abc']);

    expect(Order::where('cart_id', $cart->id)->count())->toBe(1);
});

it('redirects to cart with an error when the callback signature is invalid', function () {
    $cart = $this->createCartWithItems('iyzico');

    $fakeResult = fakeCheckoutFormResult('CART'.$cart->id, 'SUCCESS', (string) $cart->base_grand_total, 'wrong_secret');

    $mock = $this->mock(IyzicoPayment::class)->makePartial();
    $mock->shouldReceive('retrieveCheckoutForm')->andReturn($fakeResult);
    $this->app->instance(IyzicoPayment::class, $mock);

    $response = $this->post(route('iyzico.callback'), ['token' => 'token-abc']);

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('does not create an order for a failed payment status', function () {
    $cart = $this->createCartWithItems('iyzico');

    $fakeResult = fakeCheckoutFormResult('CART'.$cart->id, 'FAILURE', (string) $cart->base_grand_total, 'test_secret_key');

    $mock = $this->mock(IyzicoPayment::class)->makePartial();
    $mock->shouldReceive('retrieveCheckoutForm')->andReturn($fakeResult);
    $this->app->instance(IyzicoPayment::class, $mock);

    $response = $this->post(route('iyzico.callback'), ['token' => 'token-abc']);

    $response->assertRedirect(route('shop.checkout.cart.index'));

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('redirects to cart with an error when no token is present in the callback', function () {
    CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    $response = $this->post(route('iyzico.callback'), []);

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});
