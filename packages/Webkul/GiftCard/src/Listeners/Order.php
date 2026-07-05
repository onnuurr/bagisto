<?php

namespace Webkul\GiftCard\Listeners;

use Webkul\GiftCard\Exceptions\GiftCardAlreadyRedeemedException;
use Webkul\GiftCard\Models\GiftCard;
use Webkul\GiftCard\Models\GiftCardHistory;
use Webkul\GiftCard\Repositories\GiftCardHistoryRepository;
use Webkul\GiftCard\Repositories\GiftCardRepository;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected GiftCardRepository $giftCardRepository,
        protected GiftCardHistoryRepository $giftCardHistoryRepository
    ) {}

    /**
     * Redeem the gift card attached to the order atomically after order save.
     *
     * This listener runs inside the order creation transaction (see
     * `Webkul\Sales\Repositories\OrderRepository::createOrderIfNotThenRetry()`).
     * It re-validates the gift card is still unused and throws to roll back
     * the order if a concurrent request already redeemed it.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     *
     * @throws GiftCardAlreadyRedeemedException
     */
    public function manageGiftCard($order)
    {
        if (! $order->gift_card_id) {
            return;
        }

        $giftCard = $this->giftCardRepository->find($order->gift_card_id);

        if (
            ! $giftCard
            || $giftCard->status != GiftCard::STATUS_UNUSED
        ) {
            throw new GiftCardAlreadyRedeemedException(
                "Gift card [{$order->gift_card_id}] is no longer redeemable."
            );
        }

        $this->giftCardRepository->update([
            'status'   => GiftCard::STATUS_USED,
            'order_id' => $order->id,
            'used_at'  => now(),
        ], $giftCard->id);

        $this->giftCardHistoryRepository->create([
            'gift_card_id' => $giftCard->id,
            'action'       => GiftCardHistory::ACTION_APPLIED,
            'order_id'     => $order->id,
        ]);
    }
}
