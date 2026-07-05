<?php

namespace Webkul\Shipping\Carriers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;

class Kargonomi extends AbstractShipping
{
    /**
     * Shipping method carrier code.
     *
     * @var string
     */
    protected $code = 'kargonomi';

    /**
     * Shipping method code.
     *
     * @var string
     */
    protected $method = 'kargonomi_kargonomi';

    /**
     * Kargonomi API base URL (see https://www.kargonomi.com.tr/help/api-dokumantasyonu/kargonomi-api/).
     */
    public const BASE_URL = 'https://app.kargonomi.com.tr/api/v1';

    /**
     * Calculate rate for Kargonomi.
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
     * Kargonomi has no stateless quote endpoint: a price comparison can
     * only be read for a shipment that already exists. So a live quote
     * means creating a draft shipment for the cart, reading
     * "/shipment-price-comparison/{id}", and deleting the draft again
     * (it was never confirmed via "/confirm-shipping-price", so nothing
     * is dispatched to a real carrier). Falls back to null (the
     * configured default rate) if credentials/warehouse/address data are
     * missing or any step fails.
     */
    protected function fetchRateFromApi(): ?float
    {
        $token = $this->getConfigData('api_token');
        $appKey = $this->getConfigData('app_key');
        $warehouseId = $this->getConfigData('warehouse_id');

        if (! $token || ! $appKey || ! $warehouseId) {
            return null;
        }

        $shipmentId = null;

        try {
            $cart = Cart::getCart();

            $address = $cart->shipping_address;

            if (! $address) {
                return null;
            }

            $stateId = $this->resolveStateId($address->state);
            $cityId = $this->resolveCityId($stateId, $address->city);

            if (! $stateId || ! $cityId) {
                return null;
            }

            $totalWeight = 0;

            foreach ($cart->items as $item) {
                if ($item->getTypeInstance()->isStockable()) {
                    $totalWeight += ($item->product->weight ?? 0) * $item->quantity;
                }
            }

            $shipment = $this->client()->post(self::BASE_URL.'/shipments', [
                'shipment' => [
                    'warehouse_id'   => $warehouseId,
                    'buyer_name'     => trim($address->first_name.' '.$address->last_name),
                    'buyer_phone'    => substr(preg_replace('/\D/', '', (string) $address->phone), -10),
                    'buyer_address'  => $address->address1,
                    'buyer_state_id' => $stateId,
                    'buyer_city_id'  => $cityId,
                    'packages'       => [
                        [
                            'desi' => max(1, (int) ceil($totalWeight)),
                        ],
                    ],
                ],
            ]);

            if ($shipment->failed()) {
                return null;
            }

            $shipmentId = $shipment->json('id');

            if (! $shipmentId) {
                return null;
            }

            $comparison = $this->client()->get(self::BASE_URL."/shipment-price-comparison/{$shipmentId}");

            if ($comparison->failed()) {
                return null;
            }

            return $this->selectPrice($comparison->json('shipping_provider_with_price') ?? []);
        } catch (\Throwable $e) {
            Log::warning('Kargonomi rate lookup failed: '.$e->getMessage());

            return null;
        } finally {
            if ($shipmentId) {
                $this->client()->delete(self::BASE_URL."/shipments/{$shipmentId}");
            }
        }
    }

    /**
     * Picks the price for the configured preferred carrier, or the
     * cheapest quote if none is configured. Entries with a non-numeric
     * price (e.g. "Hizmet Dışı Bölge", or the "-1"/Otomatik placeholder
     * which always has a null price) are ignored.
     */
    protected function selectPrice(array $providers): ?float
    {
        $quotes = collect($providers)
            ->map(fn ($provider) => [
                'id'    => $provider['id'] ?? null,
                'price' => $this->parsePrice($provider['price'] ?? null),
            ])
            ->filter(fn ($provider) => $provider['price'] !== null);

        $preferredProviderId = $this->getConfigData('shipping_provider_id');

        $selected = $preferredProviderId
            ? $quotes->first(fn ($provider) => (string) $provider['id'] === (string) $preferredProviderId)
            : $quotes->sortBy('price')->first();

        return $selected['price'] ?? null;
    }

    /**
     * Kargonomi returns prices as strings like "22.67 + KDV".
     */
    protected function parsePrice(?string $price): ?float
    {
        if (! $price || ! preg_match('/[\d.,]+/', $price, $matches)) {
            return null;
        }

        return (float) str_replace(',', '.', $matches[0]);
    }

    /**
     * Resolves Kargonomi's internal state id for a Bagisto address state
     * name via "/states".
     */
    protected function resolveStateId(?string $stateName): ?int
    {
        if (! $stateName) {
            return null;
        }

        $response = $this->client()->get(self::BASE_URL.'/states');

        if ($response->failed()) {
            return null;
        }

        $state = collect($response->json('data') ?? $response->json())
            ->first(fn ($state) => mb_strtolower($state['name'] ?? '') === mb_strtolower($stateName));

        return $state['id'] ?? null;
    }

    /**
     * Resolves Kargonomi's internal city id for a Bagisto address city
     * name via "/cities/{stateId}".
     */
    protected function resolveCityId(?int $stateId, ?string $cityName): ?int
    {
        if (! $stateId || ! $cityName) {
            return null;
        }

        $response = $this->client()->get(self::BASE_URL."/cities/{$stateId}");

        if ($response->failed()) {
            return null;
        }

        $city = collect($response->json('data') ?? $response->json())
            ->first(fn ($city) => mb_strtolower($city['name'] ?? '') === mb_strtolower($cityName));

        return $city['id'] ?? null;
    }

    /**
     * Preconfigured HTTP client carrying Kargonomi's auth headers.
     */
    protected function client()
    {
        return Http::withToken($this->getConfigData('api_token'))
            ->withHeaders(['X-App-Key' => $this->getConfigData('app_key')])
            ->acceptJson()
            ->timeout(8);
    }
}
