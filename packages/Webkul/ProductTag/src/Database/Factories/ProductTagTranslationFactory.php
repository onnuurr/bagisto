<?php

namespace Webkul\ProductTag\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\ProductTag\Models\ProductTagTranslation;

class ProductTagTranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductTagTranslation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'locale' => 'en',
        ];
    }
}
