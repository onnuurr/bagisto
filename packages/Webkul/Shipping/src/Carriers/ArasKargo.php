<?php

namespace Webkul\Shipping\Carriers;

use Illuminate\Support\Facades\Log;
use SoapClient;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;

class ArasKargo extends AbstractShipping
{
    /**
     * Shipping method carrier code.
     *
     * @var string
     */
    protected $code = 'araskargo';

    /**
     * Shipping method code.
     *
     * @var string
     */
    protected $method = 'araskargo_araskargo';

    /**
     * WSDL of Aras Kargo's "ArasCargoIntegrationService" (Customer
     * Integration Service), used when no environment-specific URL is
     * configured. Aras Kargo also runs a test environment at
     * customerservicestest.araskargo.com.tr with the same operations.
     */
    public const DEFAULT_WSDL = 'https://customerservices.araskargo.com.tr/ArasCargoCustomerIntegrationService/ArasCargoIntegrationService.svc?wsdl';

    /**
     * Calculate rate for Aras Kargo.
     *
     * @return CartShippingRate|false
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        return $this->getRate();
    }

    /**
     * Get rate.
     */
    public function getRate(): CartShippingRate
    {
        $cartShippingRate = new CartShippingRate;

        $cartShippingRate->carrier = $this->getCode();
        $cartShippingRate->carrier_title = $this->getConfigData('title');
        $cartShippingRate->method = $this->getMethod();
        $cartShippingRate->method_title = $this->getConfigData('title');
        $cartShippingRate->method_description = $this->getConfigData('description');

        $rate = $this->fetchRateFromApi() ?? (float) $this->getConfigData('default_rate');

        $cartShippingRate->price = core()->convertPrice($rate);
        $cartShippingRate->base_price = $rate;

        return $cartShippingRate;
    }

    /**
     * Queries Aras Kargo's ArasCargoIntegrationService (GetPriceInfo
     * operation) for a live rate based on the cart's total weight and
     * destination. Returns null (falling back to the configured default
     * rate) when credentials/the soap extension are missing, the service
     * is unreachable, or the response can't be parsed.
     *
     * GetPriceInfo's request/response fields beyond LoginInfo are not
     * publicly documented; confirm them against the WSDL bound to your
     * account (Aras Kargo issues test credentials before production ones)
     * and adjust the "priceInfo" payload below accordingly.
     */
    protected function fetchRateFromApi(): ?float
    {
        if (! class_exists(SoapClient::class)) {
            return null;
        }

        $username = $this->getConfigData('username');
        $password = $this->getConfigData('password');
        $customerCode = $this->getConfigData('customer_code');

        if (! $username || ! $password || ! $customerCode) {
            return null;
        }

        try {
            $cart = Cart::getCart();

            $totalWeight = 0;

            foreach ($cart->items as $item) {
                if ($item->getTypeInstance()->isStockable()) {
                    $totalWeight += ($item->product->weight ?? 0) * $item->quantity;
                }
            }

            $client = new SoapClient($this->getConfigData('wsdl_url') ?: self::DEFAULT_WSDL, [
                'connection_timeout' => 5,
                'exceptions'         => true,
            ]);

            $response = $client->GetPriceInfo([
                'loginInfo' => [
                    'UserName'     => $username,
                    'Password'     => $password,
                    'CustomerCode' => $customerCode,
                ],
                'priceInfo' => [
                    'ReceiverCityName' => $cart->shipping_address->state ?? null,
                    'Weight'           => $totalWeight,
                ],
            ]);

            $rate = $response->GetPriceInfoResult->Price ?? null;

            return is_numeric($rate) ? (float) $rate : null;
        } catch (\Throwable $e) {
            Log::warning('Aras Kargo rate lookup failed: '.$e->getMessage());

            return null;
        }
    }
}
