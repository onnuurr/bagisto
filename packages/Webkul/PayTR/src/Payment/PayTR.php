<?php

namespace Webkul\PayTR\Payment;

use Illuminate\Support\Facades\Storage;
use Webkul\Checkout\Contracts\Cart as CartContract;
use Webkul\Checkout\Facades\Cart;
use Webkul\Payment\Payment\Payment;

class PayTR extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'paytr';

    /**
     * PayTR get-token API endpoint.
     *
     * @var string
     */
    protected $tokenUrl = 'https://www.paytr.com/odeme/api/get-token';

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('paytr.redirect');
    }

    /**
     * Check if payment method is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return parent::isAvailable() && $this->hasValidCredentials();
    }

    /**
     * Get payment method title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfigData('title') ?: trans('paytr::app.title');
    }

    /**
     * Get payment method description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getConfigData('description') ?: trans('paytr::app.description');
    }

    /**
     * Get payment method image/logo.
     *
     * @return string
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/paytr.png', 'shop');
    }

    /**
     * Get merchant id from configuration.
     *
     * @return string|null
     */
    public function getMerchantId()
    {
        return $this->getConfigData('merchant_id');
    }

    /**
     * Get merchant key from configuration.
     *
     * @return string|null
     */
    public function getMerchantKey()
    {
        return $this->getConfigData('merchant_key');
    }

    /**
     * Get merchant salt from configuration.
     *
     * @return string|null
     */
    public function getMerchantSalt()
    {
        return $this->getConfigData('merchant_salt');
    }

    /**
     * Check if test (sandbox) mode is enabled.
     *
     * @return bool
     */
    public function isTestMode()
    {
        return (bool) $this->getConfigData('sandbox');
    }

    /**
     * Get the currency code sent to PayTR (TL, USD, EUR or GBP).
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getConfigData('currency') ?: 'TL';
    }

    /**
     * Get the PayTR get-token API endpoint.
     *
     * @return string
     */
    public function getTokenUrl()
    {
        return $this->tokenUrl;
    }

    /**
     * Validate merchant credentials.
     *
     * @return bool
     */
    public function hasValidCredentials()
    {
        return ! empty($this->getMerchantId())
            && ! empty($this->getMerchantKey())
            && ! empty($this->getMerchantSalt());
    }

    /**
     * Generate a unique, alphanumeric-only merchant_oid that embeds the cart id,
     * so the notification callback (which has no session) can resolve the cart
     * without any extra state. PayTR only accepts letters and digits here.
     *
     * @param  int  $cartId
     * @return string
     */
    public function generateMerchantOid($cartId)
    {
        return 'CART'.$cartId.'T'.strtoupper(bin2hex(random_bytes(4)));
    }

    /**
     * Extract the cart id previously embedded by generateMerchantOid().
     *
     * @return int|null
     */
    public function getCartIdFromMerchantOid(string $merchantOid)
    {
        if (! preg_match('/^CART(\d+)T[A-F0-9]+$/', $merchantOid, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    /**
     * Build the base64 encoded user_basket parameter from the cart items.
     *
     * @return string
     */
    public function getBasket(CartContract $cart)
    {
        $basket = [];

        foreach ($cart->items as $item) {
            $basket[] = [
                $item->name,
                number_format((float) $item->price, 2, '.', ''),
                (int) $item->quantity,
            ];
        }

        return base64_encode(json_encode($basket));
    }

    /**
     * Build the full get-token request payload, including the computed paytr_token.
     *
     * @return array
     */
    public function getPaymentData(?CartContract $cart = null, ?string $userIp = null)
    {
        if (! $cart) {
            $cart = Cart::getCart();
        }

        $billingAddress = $cart->billing_address;

        $data = [
            'merchant_id' => $this->getMerchantId(),
            'user_ip' => $userIp ?: request()->ip(),
            'merchant_oid' => $this->generateMerchantOid($cart->id),
            'email' => $cart->customer_email,
            'payment_amount' => (int) round($cart->base_grand_total * 100),
            'user_basket' => $this->getBasket($cart),
            'no_installment' => 0,
            'max_installment' => 0,
            'user_name' => trim($cart->customer_first_name.' '.$cart->customer_last_name),
            'user_address' => $billingAddress->address ?? '-',
            'user_phone' => $billingAddress->phone ?? '-',
            'merchant_ok_url' => route('paytr.ok'),
            'merchant_fail_url' => route('paytr.fail'),
            'timeout_limit' => 30,
            'currency' => $this->getCurrency(),
            'test_mode' => $this->isTestMode() ? 1 : 0,
            'debug_on' => $this->isTestMode() ? 1 : 0,
            'lang' => app()->getLocale() === 'tr' ? 'tr' : 'en',
        ];

        $data['paytr_token'] = $this->generateToken($data);

        return $data;
    }

    /**
     * Generate the paytr_token for the get-token request.
     *
     * Formula (per PayTR iFrame API): base64_encode(hash_hmac('sha256',
     * merchant_id.user_ip.merchant_oid.email.payment_amount.user_basket
     * .no_installment.max_installment.currency.test_mode.merchant_salt,
     * merchant_key, true))
     *
     * @return string
     */
    public function generateToken(array $data)
    {
        $hashStr = $data['merchant_id']
            .$data['user_ip']
            .$data['merchant_oid']
            .$data['email']
            .$data['payment_amount']
            .$data['user_basket']
            .$data['no_installment']
            .$data['max_installment']
            .$data['currency']
            .$data['test_mode'];

        return base64_encode(
            hash_hmac('sha256', $hashStr.$this->getMerchantSalt(), $this->getMerchantKey(), true)
        );
    }

    /**
     * Verify the hash sent with the merchant_notify_url server-to-server callback.
     *
     * Formula: base64_encode(hash_hmac('sha256',
     * merchant_oid.merchant_salt.status.total_amount, merchant_key, true))
     *
     * @return bool
     */
    public function verifyNotificationHash(array $data)
    {
        $merchantOid = $data['merchant_oid'] ?? '';
        $status = $data['status'] ?? '';
        $totalAmount = $data['total_amount'] ?? '';
        $receivedHash = $data['hash'] ?? '';

        if (! $receivedHash) {
            return false;
        }

        $hashStr = $merchantOid.$this->getMerchantSalt().$status.$totalAmount;

        $calculatedHash = base64_encode(
            hash_hmac('sha256', $hashStr, $this->getMerchantKey(), true)
        );

        return hash_equals($calculatedHash, $receivedHash);
    }
}
