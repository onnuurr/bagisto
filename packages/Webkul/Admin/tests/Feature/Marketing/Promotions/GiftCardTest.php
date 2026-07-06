<?php

use Webkul\GiftCard\Models\GiftCard;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should show the gift card index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.gift_cards.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.gift-cards.index.title'))
        ->assertSeeText(trans('admin::app.marketing.promotions.gift-cards.index.create-btn'));
});

it('should fail the validation with errors when certain fields are not provided when storing a gift card', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.gift_cards.store'))
        ->assertJsonValidationErrorFor('amount')
        ->assertJsonValidationErrorFor('currency')
        ->assertUnprocessable();
});

it('should store a newly created gift card with the given code', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.gift_cards.store'), [
        'code' => $code = strtoupper(fake()->bothify('????-????-????')),
        'amount' => $amount = fake()->randomFloat(2, 10, 500),
        'currency' => $currencyCode = core()->getBaseCurrencyCode(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.gift-cards.create.success'));

    $this->assertModelWise([
        GiftCard::class => [
            [
                'code' => $code,
                'currency' => $currencyCode,
                'status' => GiftCard::STATUS_UNUSED,
            ],
        ],
    ]);
});

it('should generate unique auto-generated codes when quantity is greater than 1', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.gift_cards.store'), [
        'code' => 'IGNORED-CODE',
        'amount' => fake()->randomFloat(2, 10, 500),
        'currency' => core()->getBaseCurrencyCode(),
        'quantity' => 3,
    ])
        ->assertOk();

    expect(GiftCard::where('code', 'IGNORED-CODE')->count())->toBe(0);

    expect(GiftCard::count())->toBe(3);
});

it('should update the existing gift card', function () {
    // Arrange.
    $giftCard = GiftCard::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.gift_cards.update'), [
        'id' => $giftCard->id,
        'code' => $giftCard->code,
        'amount' => $newAmount = fake()->randomFloat(2, 10, 500),
        'currency' => $giftCard->currency,
        'status' => GiftCard::STATUS_UNUSED,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.gift-cards.edit.success'));

    $this->assertModelWise([
        GiftCard::class => [
            [
                'id' => $giftCard->id,
                'amount' => $newAmount,
            ],
        ],
    ]);
});

it('should delete an unused gift card', function () {
    // Arrange.
    $giftCard = GiftCard::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.promotions.gift_cards.delete', $giftCard->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.gift-cards.edit.delete-success'));

    $this->assertDatabaseMissing('gift_cards', [
        'id' => $giftCard->id,
    ]);
});

it('should not delete a gift card that has already been redeemed', function () {
    // Arrange.
    $giftCard = GiftCard::factory()->create(['status' => GiftCard::STATUS_USED]);

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.promotions.gift_cards.delete', $giftCard->id))
        ->assertStatus(400)
        ->assertSeeText(trans('admin::app.marketing.promotions.gift-cards.edit.delete-failed'));

    $this->assertDatabaseHas('gift_cards', [
        'id' => $giftCard->id,
    ]);
});

it('should mass delete the unused gift cards and skip the used ones', function () {
    // Arrange.
    $giftCards = GiftCard::factory()->count(2)->create();

    $usedGiftCard = GiftCard::factory()->create(['status' => GiftCard::STATUS_USED]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.gift_cards.mass_delete'), [
        'indices' => [...$giftCards->pluck('id')->toArray(), $usedGiftCard->id],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.gift-cards.index.datagrid.mass-delete-success'));

    foreach ($giftCards as $giftCard) {
        $this->assertDatabaseMissing('gift_cards', [
            'id' => $giftCard->id,
        ]);
    }

    $this->assertDatabaseHas('gift_cards', [
        'id' => $usedGiftCard->id,
    ]);
});
