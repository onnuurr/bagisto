<?php

use Iyzipay\Model\CheckoutForm;
use Iyzipay\Model\CheckoutFormInitialize;
use Webkul\Core\Models\CoreConfig;
use Webkul\Iyzico\Payment\Iyzico;

beforeEach(function () {
    $this->iyzico = app(Iyzico::class);
});

it('returns the correct payment method code', function () {
    expect($this->iyzico->getCode())->toBe('iyzico');
});

it('returns the payment method title from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.title',
        'value' => 'iyzico Payment Gateway',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    expect($this->iyzico->getTitle())->toBe('iyzico Payment Gateway');
});

it('returns the api key and secret key from configuration', function () {
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

    expect($this->iyzico->getApiKey())->toBe('test_api_key')
        ->and($this->iyzico->getSecretKey())->toBe('test_secret_key');
});

it('checks if credentials are valid', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.api_key',
        'value' => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.secret_key',
        'value' => 'test_secret',
        'channel_code' => 'default',
    ]);

    expect($this->iyzico->hasValidCredentials())->toBeTrue();
});

it('returns false if any credential is missing', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.api_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    expect($this->iyzico->hasValidCredentials())->toBeFalse();
});

it('is not available when credentials are invalid', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.api_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    expect($this->iyzico->isAvailable())->toBeFalse();
});

it('defaults currency to TRY when not configured', function () {
    expect($this->iyzico->getCurrency())->toBe('TRY');
});

it('returns the sandbox base url when sandbox is enabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    $options = $this->iyzico->getOptions();

    expect($options->getBaseUrl())->toBe('https://sandbox-api.iyzipay.com');
});

it('returns the production base url when sandbox is disabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    $options = $this->iyzico->getOptions();

    expect($options->getBaseUrl())->toBe('https://api.iyzipay.com');
});

it('returns redirect URL for payment', function () {
    expect($this->iyzico->getRedirectUrl())->toBe(route('iyzico.redirect'));
});

it('embeds and extracts the cart id from the basket id round-trip', function () {
    $basketId = $this->iyzico->buildBasketId(456);

    expect($basketId)->toBe('CART456')
        ->and($this->iyzico->getCartIdFromBasketId($basketId))->toBe(456);
});

it('returns null when the basket id cannot be parsed', function () {
    expect($this->iyzico->getCartIdFromBasketId('not-a-valid-id'))->toBeNull();
});

it('calculates the signature using the documented hmac formula', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.secret_key',
        'value' => 'TEST_SECRET',
        'channel_code' => 'default',
    ]);

    $calculated = $this->iyzico->calculateHmacSHA256Signature(['conv-1', 'token-abc']);

    $expected = bin2hex(hash_hmac('sha256', 'conv-1:token-abc', 'TEST_SECRET', true));

    expect($calculated)->toBe($expected);
});

it('verifies a correctly signed checkout form initialize response', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.secret_key',
        'value' => 'TEST_SECRET',
        'channel_code' => 'default',
    ]);

    $resource = new CheckoutFormInitialize;
    $resource->setConversationId('conv-1');
    $resource->setToken('token-abc');
    $resource->setSignature(bin2hex(hash_hmac('sha256', 'conv-1:token-abc', 'TEST_SECRET', true)));

    expect($this->iyzico->verifyInitializeSignature($resource))->toBeTrue();
});

it('rejects a tampered checkout form initialize signature', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.secret_key',
        'value' => 'TEST_SECRET',
        'channel_code' => 'default',
    ]);

    $resource = new CheckoutFormInitialize;
    $resource->setConversationId('conv-1');
    $resource->setToken('token-abc');
    $resource->setSignature('not-the-right-signature');

    expect($this->iyzico->verifyInitializeSignature($resource))->toBeFalse();
});

it('verifies a correctly signed checkout form retrieve response', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.secret_key',
        'value' => 'TEST_SECRET',
        'channel_code' => 'default',
    ]);

    $fields = ['SUCCESS', 'pay-1', 'TRY', 'CART1', 'conv-1', '100.0', '100.0', 'token-abc'];

    $checkoutForm = new CheckoutForm;
    $checkoutForm->setPaymentStatus($fields[0]);
    $checkoutForm->setPaymentId($fields[1]);
    $checkoutForm->setCurrency($fields[2]);
    $checkoutForm->setBasketId($fields[3]);
    $checkoutForm->setConversationId($fields[4]);
    $checkoutForm->setPaidPrice($fields[5]);
    $checkoutForm->setPrice($fields[6]);
    $checkoutForm->setToken($fields[7]);
    $checkoutForm->setSignature(bin2hex(hash_hmac('sha256', implode(':', $fields), 'TEST_SECRET', true)));

    expect($this->iyzico->verifyRetrieveSignature($checkoutForm))->toBeTrue();
});

it('rejects a tampered checkout form retrieve signature', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.iyzico.secret_key',
        'value' => 'TEST_SECRET',
        'channel_code' => 'default',
    ]);

    $checkoutForm = new CheckoutForm;
    $checkoutForm->setPaymentStatus('SUCCESS');
    $checkoutForm->setPaymentId('pay-1');
    $checkoutForm->setCurrency('TRY');
    $checkoutForm->setBasketId('CART1');
    $checkoutForm->setConversationId('conv-1');
    $checkoutForm->setPaidPrice('100.0');
    $checkoutForm->setPrice('100.0');
    $checkoutForm->setToken('token-abc');
    $checkoutForm->setSignature('invalid-signature');

    expect($this->iyzico->verifyRetrieveSignature($checkoutForm))->toBeFalse();
});
