<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\GiftCard\Models\GiftCard;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;

/**
 * Helper to create a cart with a single simple product item and collect its totals.
 */
function createCartWithSimpleProduct(): Cart
{
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create(['price' => 100]);

    $cart = Cart::factory()->create();

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'quantity' => 1,
        'name' => $product->name,
        'price' => $convertedPrice = core()->convertPrice($product->price),
        'base_price' => $product->price,
        'total' => $convertedPrice,
        'base_total' => $product->price,
        'weight' => $product->weight ?? 0,
        'total_weight' => $product->weight ?? 0,
        'base_total_weight' => $product->weight ?? 0,
        'type' => $product->type,
        'additional' => [
            'product_id' => $product->id,
            'quantity' => 1,
        ],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    return cart()->getCart();
}

it('should apply a valid gift card to the cart and reduce the grand total', function () {
    // Arrange.
    $cart = createCartWithSimpleProduct();

    $baseGrandTotal = $cart->grand_total;

    $giftCard = GiftCard::factory()->create([
        'amount' => 30,
        'currency' => core()->getBaseCurrencyCode(),
        'status' => GiftCard::STATUS_UNUSED,
    ]);

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.gift_card.apply'), [
        'code' => $giftCard->code,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.gift-card.success-apply'))
        ->assertJsonPath('data.gift_card_code', $giftCard->code);

    expect((float) $response->json('data.gift_cards_amount'))->toBe(30.0);
    expect((float) $response->json('data.grand_total'))->toBe($baseGrandTotal - 30);
});

it('should fail to apply an invalid gift card code', function () {
    // Arrange.
    createCartWithSimpleProduct();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.gift_card.apply'), [
        'code' => 'DOES-NOT-EXIST',
    ])
        ->assertUnprocessable()
        ->assertJsonPath('message', trans('shop::app.checkout.gift-card.invalid'));
});

it('should fail to apply an already used gift card', function () {
    // Arrange.
    createCartWithSimpleProduct();

    $giftCard = GiftCard::factory()->create([
        'status' => GiftCard::STATUS_USED,
    ]);

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.gift_card.apply'), [
        'code' => $giftCard->code,
    ])
        ->assertUnprocessable()
        ->assertJsonPath('message', trans('shop::app.checkout.gift-card.invalid'));
});

it('should not re-apply the same gift card code twice', function () {
    // Arrange.
    createCartWithSimpleProduct();

    $giftCard = GiftCard::factory()->create([
        'amount' => 10,
        'currency' => core()->getBaseCurrencyCode(),
        'status' => GiftCard::STATUS_UNUSED,
    ]);

    postJson(route('shop.api.checkout.cart.gift_card.apply'), [
        'code' => $giftCard->code,
    ])->assertOk();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.gift_card.apply'), [
        'code' => $giftCard->code,
    ])
        ->assertUnprocessable()
        ->assertJsonPath('message', trans('shop::app.checkout.gift-card.already-applied'));
});

it('should remove the applied gift card from the cart and restore the grand total', function () {
    // Arrange.
    $cart = createCartWithSimpleProduct();

    $baseGrandTotal = $cart->grand_total;

    $giftCard = GiftCard::factory()->create([
        'amount' => 30,
        'currency' => core()->getBaseCurrencyCode(),
        'status' => GiftCard::STATUS_UNUSED,
    ]);

    postJson(route('shop.api.checkout.cart.gift_card.apply'), [
        'code' => $giftCard->code,
    ])->assertOk();

    // Act and Assert.
    $response = deleteJson(route('shop.api.checkout.cart.gift_card.remove'))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.gift-card.remove'))
        ->assertJsonPath('data.gift_card_code', null);

    expect((float) $response->json('data.grand_total'))->toBe($baseGrandTotal);
});

it('should cap the gift card amount at the grand total when the card value exceeds it', function () {
    // Arrange.
    $cart = createCartWithSimpleProduct();

    $baseGrandTotal = $cart->grand_total;

    $giftCard = GiftCard::factory()->create([
        'amount' => $baseGrandTotal + 1000,
        'currency' => core()->getBaseCurrencyCode(),
        'status' => GiftCard::STATUS_UNUSED,
    ]);

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.gift_card.apply'), [
        'code' => $giftCard->code,
    ])->assertOk();

    expect((float) $response->json('data.grand_total'))->toBe(0.0);
    expect((float) $response->json('data.gift_cards_amount'))->toBe($baseGrandTotal);
});
