<?php

namespace Webkul\Iyzico\Payment;

use Illuminate\Support\Facades\Storage;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\CheckoutForm;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Locale;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Options;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Request\RetrieveCheckoutFormRequest;
use Webkul\Checkout\Contracts\Cart as CartContract;
use Webkul\Checkout\Contracts\CartAddress;
use Webkul\Payment\Payment\Payment;

class Iyzico extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'iyzico';

    /**
     * Placeholder Turkish identity number used for buyers Bagisto has no
     * identity number field for (guests, foreign customers). This is the
     * same well-documented workaround used by every other iyzico plugin
     * (WooCommerce, Magento, PrestaShop) since iyzico's Buyer model requires
     * the field but does not reject foreign/placeholder values.
     *
     * @var string
     */
    protected const PLACEHOLDER_IDENTITY_NUMBER = '11111111111';

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('iyzico.redirect');
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
        return $this->getConfigData('title') ?: trans('iyzico::app.title');
    }

    /**
     * Get payment method description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getConfigData('description') ?: trans('iyzico::app.description');
    }

    /**
     * Get payment method image/logo.
     *
     * @return string
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/iyzico.png', 'shop');
    }

    /**
     * Get the API key from configuration.
     *
     * @return string|null
     */
    public function getApiKey()
    {
        return $this->getConfigData('api_key');
    }

    /**
     * Get the secret key from configuration.
     *
     * @return string|null
     */
    public function getSecretKey()
    {
        return $this->getConfigData('secret_key');
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
     * Get the currency code sent to iyzico. Must be one of iyzico's own ISO
     * codes (TRY, USD, EUR, GBP, ...) — note it is "TRY", not "TL".
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getConfigData('currency') ?: Currency::TL;
    }

    /**
     * Validate merchant credentials.
     *
     * @return bool
     */
    public function hasValidCredentials()
    {
        return ! empty($this->getApiKey()) && ! empty($this->getSecretKey());
    }

    /**
     * Build the Iyzipay SDK options (api key, secret key, base url).
     *
     * @return Options
     */
    public function getOptions()
    {
        $options = new Options;

        $options->setApiKey($this->getApiKey());
        $options->setSecretKey($this->getSecretKey());
        $options->setBaseUrl(
            $this->isTestMode()
                ? 'https://sandbox-api.iyzipay.com'
                : 'https://api.iyzipay.com'
        );

        return $options;
    }

    /**
     * Embed the cart id in the basket id, so the callback (which cannot
     * safely rely on the browser's session cookie after a cross-site
     * redirect) can resolve the cart directly from iyzico's authoritative,
     * signed retrieve() response instead.
     *
     * @param  int  $cartId
     * @return string
     */
    public function buildBasketId($cartId)
    {
        return 'CART'.$cartId;
    }

    /**
     * Extract the cart id previously embedded by buildBasketId().
     *
     * @return int|null
     */
    public function getCartIdFromBasketId(string $basketId)
    {
        if (! preg_match('/^CART(\d+)$/', $basketId, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    /**
     * Initialize the iyzico checkout form for the given cart.
     *
     * @return CheckoutFormInitialize
     */
    public function initializeCheckoutForm(CartContract $cart)
    {
        $request = new CreateCheckoutFormInitializeRequest;

        $request->setLocale(app()->getLocale() === 'tr' ? Locale::TR : Locale::EN);
        $request->setConversationId((string) $cart->id.'-'.uniqid());
        $request->setPrice(number_format((float) $cart->base_sub_total, 2, '.', ''));
        $request->setPaidPrice(number_format((float) $cart->base_grand_total, 2, '.', ''));
        $request->setCurrency($this->getCurrency());
        $request->setBasketId($this->buildBasketId($cart->id));
        $request->setPaymentGroup(PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('iyzico.callback'));
        $request->setBuyer($this->buildBuyer($cart));
        $request->setShippingAddress($this->buildAddress($cart->shipping_address ?? $cart->billing_address));
        $request->setBillingAddress($this->buildAddress($cart->billing_address));
        $request->setBasketItems($this->buildBasketItems($cart));

        return CheckoutFormInitialize::create($request, $this->getOptions());
    }

    /**
     * Retrieve the (authoritative, signed) checkout form result for a token.
     *
     * @return CheckoutForm
     */
    public function retrieveCheckoutForm(string $token)
    {
        $request = new RetrieveCheckoutFormRequest;

        $request->setLocale(app()->getLocale() === 'tr' ? Locale::TR : Locale::EN);
        $request->setToken($token);

        return CheckoutForm::retrieve($request, $this->getOptions());
    }

    /**
     * Verify the signature on a CheckoutFormInitialize response.
     *
     * @return bool
     */
    public function verifyInitializeSignature(CheckoutFormInitialize $checkoutFormInitialize)
    {
        $calculated = $this->calculateHmacSHA256Signature([
            $checkoutFormInitialize->getConversationId(),
            $checkoutFormInitialize->getToken(),
        ]);

        return hash_equals($calculated, (string) $checkoutFormInitialize->getSignature());
    }

    /**
     * Verify the signature on a CheckoutForm (retrieve) response.
     *
     * @return bool
     */
    public function verifyRetrieveSignature(CheckoutForm $checkoutForm)
    {
        $calculated = $this->calculateHmacSHA256Signature([
            $checkoutForm->getPaymentStatus(),
            $checkoutForm->getPaymentId(),
            $checkoutForm->getCurrency(),
            $checkoutForm->getBasketId(),
            $checkoutForm->getConversationId(),
            $checkoutForm->getPaidPrice(),
            $checkoutForm->getPrice(),
            $checkoutForm->getToken(),
        ]);

        return hash_equals($calculated, (string) $checkoutForm->getSignature());
    }

    /**
     * Calculate an iyzico HMAC-SHA256 signature over the given ordered
     * fields, per iyzico's documented formula: hex(hmac_sha256(join(':',
     * fields), secretKey)).
     *
     * @return string
     */
    public function calculateHmacSHA256Signature(array $params)
    {
        return bin2hex(hash_hmac('sha256', implode(':', $params), (string) $this->getSecretKey(), true));
    }

    /**
     * Build the iyzico buyer model from the cart.
     *
     * @return Buyer
     */
    protected function buildBuyer(CartContract $cart)
    {
        $address = $cart->billing_address;

        $buyer = new Buyer;

        $buyer->setId((string) ($cart->customer_id ?: 'GUEST'.$cart->id));
        $buyer->setName($cart->customer_first_name ?: 'Guest');
        $buyer->setSurname($cart->customer_last_name ?: 'Customer');
        $buyer->setGsmNumber($address->phone ?? '');
        $buyer->setEmail($cart->customer_email);
        $buyer->setIdentityNumber(static::PLACEHOLDER_IDENTITY_NUMBER);
        $buyer->setRegistrationAddress($address->address ?? '-');
        $buyer->setIp(request()->ip());
        $buyer->setCity($address->city ?? '-');
        $buyer->setCountry($address->country_name ?? ($address->country ?? '-'));
        $buyer->setZipCode($address->postcode ?? '-');

        return $buyer;
    }

    /**
     * Build an iyzico address model from a Bagisto cart address.
     *
     * @param  CartAddress|null  $cartAddress
     * @return Address
     */
    protected function buildAddress($cartAddress)
    {
        $address = new Address;

        $address->setContactName(trim(($cartAddress->first_name ?? '').' '.($cartAddress->last_name ?? '')) ?: '-');
        $address->setCity($cartAddress->city ?? '-');
        $address->setCountry($cartAddress->country_name ?? ($cartAddress->country ?? '-'));
        $address->setAddress($cartAddress->address ?? '-');
        $address->setZipCode($cartAddress->postcode ?? '-');

        return $address;
    }

    /**
     * Build the iyzico basket items from the cart items.
     *
     * @return BasketItem[]
     */
    protected function buildBasketItems(CartContract $cart)
    {
        $basketItems = [];

        foreach ($cart->items as $item) {
            $basketItem = new BasketItem;

            $basketItem->setId((string) $item->id);
            $basketItem->setName($item->name);
            $basketItem->setCategory1('General');
            $basketItem->setItemType(BasketItemType::PHYSICAL);
            $basketItem->setPrice(number_format((float) $item->base_total, 2, '.', ''));

            $basketItems[] = $basketItem;
        }

        return $basketItems;
    }
}
