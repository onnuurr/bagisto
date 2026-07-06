<?php

use Webkul\GiftCard\Exceptions\GiftCardAlreadyRedeemedException;
use Webkul\GiftCard\Listeners\Order as GiftCardOrderListener;
use Webkul\GiftCard\Models\GiftCard;
use Webkul\GiftCard\Models\GiftCardHistory;

/**
 * Helper to build a fake order object for the listener.
 */
function fakeGiftCardOrder(int $id, ?int $giftCardId): object
{
    return (object) [
        'id' => $id,
        'gift_card_id' => $giftCardId,
    ];
}

it('should redeem an unused gift card and record its history', function () {
    // Arrange.
    $giftCard = GiftCard::factory()->create(['status' => GiftCard::STATUS_UNUSED]);

    $order = fakeGiftCardOrder(999, $giftCard->id);

    // Act.
    app(GiftCardOrderListener::class)->manageGiftCard($order);

    // Assert.
    $giftCard->refresh();

    expect($giftCard->status)->toBe(GiftCard::STATUS_USED);
    expect($giftCard->order_id)->toBe(999);
    expect($giftCard->used_at)->not->toBeNull();

    $this->assertDatabaseHas('gift_card_histories', [
        'gift_card_id' => $giftCard->id,
        'action' => GiftCardHistory::ACTION_APPLIED,
        'order_id' => 999,
    ]);
});

it('should throw when the gift card has already been redeemed', function () {
    // Arrange.
    $giftCard = GiftCard::factory()->create([
        'status' => GiftCard::STATUS_USED,
        'order_id' => 111,
    ]);

    $order = fakeGiftCardOrder(222, $giftCard->id);

    // Act and Assert.
    expect(fn () => app(GiftCardOrderListener::class)->manageGiftCard($order))
        ->toThrow(GiftCardAlreadyRedeemedException::class);

    $giftCard->refresh();
    expect($giftCard->order_id)->toBe(111);
});

it('should do nothing when the order has no gift card attached', function () {
    // Arrange.
    $order = fakeGiftCardOrder(333, null);

    // Act and Assert: no exception, no history rows.
    app(GiftCardOrderListener::class)->manageGiftCard($order);

    expect(GiftCardHistory::count())->toBe(0);
});

it('should restore a redeemed gift card back to unused on refund', function () {
    // Arrange.
    $giftCard = GiftCard::factory()->create([
        'status' => GiftCard::STATUS_USED,
        'order_id' => 444,
        'used_at' => now(),
    ]);

    $order = fakeGiftCardOrder(444, $giftCard->id);

    // Act.
    app(GiftCardOrderListener::class)->refundGiftCard($order);

    // Assert.
    $giftCard->refresh();

    expect($giftCard->status)->toBe(GiftCard::STATUS_UNUSED);
    expect($giftCard->order_id)->toBeNull();
    expect($giftCard->used_at)->toBeNull();

    $this->assertDatabaseHas('gift_card_histories', [
        'gift_card_id' => $giftCard->id,
        'action' => GiftCardHistory::ACTION_REFUNDED,
        'order_id' => 444,
    ]);
});

it('should be idempotent when refunding a gift card that is already unused', function () {
    // Arrange.
    $giftCard = GiftCard::factory()->create(['status' => GiftCard::STATUS_UNUSED]);

    $order = fakeGiftCardOrder(555, $giftCard->id);

    // Act: refund fired twice (e.g. multiple partial refunds on the same order).
    app(GiftCardOrderListener::class)->refundGiftCard($order);
    app(GiftCardOrderListener::class)->refundGiftCard($order);

    // Assert: only unaffected, no history rows since it was never used.
    expect(GiftCardHistory::where('gift_card_id', $giftCard->id)->count())->toBe(0);
});

it('should restore the gift card tied to a refunded order', function () {
    // Arrange.
    $giftCard = GiftCard::factory()->create([
        'status' => GiftCard::STATUS_USED,
        'order_id' => 666,
        'used_at' => now(),
    ]);

    $refund = (object) [
        'order' => fakeGiftCardOrder(666, $giftCard->id),
    ];

    // Act.
    app(GiftCardOrderListener::class)->refundGiftCardFromRefund($refund);

    // Assert.
    $giftCard->refresh();
    expect($giftCard->status)->toBe(GiftCard::STATUS_UNUSED);
});
