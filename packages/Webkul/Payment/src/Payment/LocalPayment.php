<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;

class LocalPayment extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'local_payment';

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl() {}

    /**
     * Returns payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/cash-on-delivery.png', 'shop');
    }
}
