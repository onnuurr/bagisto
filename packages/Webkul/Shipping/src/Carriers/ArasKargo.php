<?php

namespace Webkul\Shipping\Carriers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
     * Queries the Aras Kargo web service for a live rate based on the cart's
     * total weight. Returns null (falling back to the configured default
     * rate) when credentials are missing or the service can't be reached.
     */
    protected function fetchRateFromApi(): ?float
    {
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

            $response = Http::timeout(5)->post('https://api.araskargo.com.tr/RatingWebService/RatingWebService.svc/query', [
                'UserName'     => $username,
                'Password'     => $password,
                'CustomerCode' => $customerCode,
                'Weight'       => $totalWeight,
            ]);

            if ($response->failed()) {
                return null;
            }

            $rate = $response->json('Rate');

            return is_numeric($rate) ? (float) $rate : null;
        } catch (\Throwable $e) {
            Log::warning('Aras Kargo rate lookup failed: '.$e->getMessage());

            return null;
        }
    }
}
