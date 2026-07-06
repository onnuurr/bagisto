<?php

namespace Webkul\GiftCard\Repositories;

use Webkul\Core\Eloquent\Repository;

class GiftCardRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\GiftCard\Contracts\GiftCard';
    }

    /**
     * Find a redeemable gift card by its code.
     */
    public function findRedeemableByCode(string $code)
    {
        return $this->model
            ->where('code', $code)
            ->where('status', 'unused')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();
    }
}
