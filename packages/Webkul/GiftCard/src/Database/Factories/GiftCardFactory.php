<?php

namespace Webkul\GiftCard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\GiftCard\Models\GiftCard;

class GiftCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GiftCard::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code'     => strtoupper($this->faker->bothify('????-????-????')),
            'amount'   => $this->faker->randomFloat(4, 10, 500),
            'currency' => core()->getBaseCurrencyCode(),
            'status'   => 'unused',
        ];
    }
}
