<?php

use Webkul\Core\Models\CoreConfig;
use Webkul\PayTR\Payment\PayTR;

beforeEach(function () {
    $this->payTR = app(PayTR::class);
});

it('returns the correct payment method code', function () {
    // Act
    $code = $this->payTR->getCode();

    // Assert
    expect($code)->toBe('paytr');
});

it('returns the payment method title from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.title',
        'value' => 'PayTR Payment Gateway',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    // Act
    $title = $this->payTR->getTitle();

    // Assert
    expect($title)->toBe('PayTR Payment Gateway');
});

it('returns the payment method description from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.description',
        'value' => 'Pay securely using PayTR',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    // Act
    $description = $this->payTR->getDescription();

    // Assert
    expect($description)->toBe('Pay securely using PayTR');
});

it('returns the merchant id, key and salt from configuration', function () {
    // Arrange
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

    // Act & Assert
    expect($this->payTR->getMerchantId())->toBe('test_merchant_id')
        ->and($this->payTR->getMerchantKey())->toBe('test_merchant_key')
        ->and($this->payTR->getMerchantSalt())->toBe('test_merchant_salt');
});

it('checks if credentials are valid', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_id',
        'value' => 'test_id',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_key',
        'value' => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_salt',
        'value' => 'test_salt',
        'channel_code' => 'default',
    ]);

    // Act
    $hasValidCredentials = $this->payTR->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeTrue();
});

it('returns false if any credential is missing', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_id',
        'value' => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_key',
        'value' => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_salt',
        'value' => 'test_salt',
        'channel_code' => 'default',
    ]);

    // Act
    $hasValidCredentials = $this->payTR->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeFalse();
});

it('is not available when credentials are invalid', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_id',
        'value' => '',
        'channel_code' => 'default',
    ]);

    // Act
    $isAvailable = $this->payTR->isAvailable();

    // Assert
    expect($isAvailable)->toBeFalse();
});

it('checks if sandbox (test) mode is enabled', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    // Act & Assert
    expect($this->payTR->isTestMode())->toBeTrue();
});

it('defaults currency to TL when not configured', function () {
    // Act & Assert
    expect($this->payTR->getCurrency())->toBe('TL');
});

it('returns redirect URL for payment', function () {
    // Act
    $redirectUrl = $this->payTR->getRedirectUrl();

    // Assert
    expect($redirectUrl)->toBe(route('paytr.redirect'));
});

it('embeds and extracts the cart id from the merchant_oid round-trip', function () {
    // Act
    $merchantOid = $this->payTR->generateMerchantOid(123);

    // Assert
    expect($merchantOid)->toMatch('/^CART123T[A-F0-9]+$/')
        ->and($this->payTR->getCartIdFromMerchantOid($merchantOid))->toBe(123);
});

it('returns null when the merchant_oid cannot be parsed', function () {
    // Act & Assert
    expect($this->payTR->getCartIdFromMerchantOid('not-a-valid-oid'))->toBeNull();
});

it('generates the paytr_token using the documented hash formula', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_key',
        'value' => 'TEST_KEY',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_salt',
        'value' => 'TEST_SALT',
        'channel_code' => 'default',
    ]);

    $data = [
        'merchant_id' => 'MID',
        'user_ip' => '127.0.0.1',
        'merchant_oid' => 'CART1TABCDEF12',
        'email' => 'john@example.com',
        'payment_amount' => 10050,
        'user_basket' => base64_encode(json_encode([['Product', '100.50', 1]])),
        'no_installment' => 0,
        'max_installment' => 0,
        'currency' => 'TL',
        'test_mode' => 1,
    ];

    // Act
    $token = $this->payTR->generateToken($data);

    // Assert
    $hashStr = $data['merchant_id'].$data['user_ip'].$data['merchant_oid'].$data['email']
        .$data['payment_amount'].$data['user_basket'].$data['no_installment']
        .$data['max_installment'].$data['currency'].$data['test_mode'];

    $expectedToken = base64_encode(hash_hmac('sha256', $hashStr.'TEST_SALT', 'TEST_KEY', true));

    expect($token)->toBe($expectedToken);
});

it('verifies the notification hash using the documented callback formula', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_key',
        'value' => 'TEST_KEY',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_salt',
        'value' => 'TEST_SALT',
        'channel_code' => 'default',
    ]);

    $data = [
        'merchant_oid' => 'CART1TABCDEF12',
        'status' => 'success',
        'total_amount' => '10050',
    ];

    $hashStr = $data['merchant_oid'].'TEST_SALT'.$data['status'].$data['total_amount'];

    $data['hash'] = base64_encode(hash_hmac('sha256', $hashStr, 'TEST_KEY', true));

    // Act
    $isValid = $this->payTR->verifyNotificationHash($data);

    // Assert
    expect($isValid)->toBeTrue();
});

it('rejects an invalid notification hash', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_key',
        'value' => 'TEST_KEY',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.paytr.merchant_salt',
        'value' => 'TEST_SALT',
        'channel_code' => 'default',
    ]);

    // Act
    $isValid = $this->payTR->verifyNotificationHash([
        'merchant_oid' => 'CART1TABCDEF12',
        'status' => 'success',
        'total_amount' => '10050',
        'hash' => 'invalid_hash_value',
    ]);

    // Assert
    expect($isValid)->toBeFalse();
});
