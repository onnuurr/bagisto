<?php

namespace Webkul\ProductTag\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\ProductTag\Models\ProductTag;

class ProductTagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductTag::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'image' => null,
        ];
    }
}
