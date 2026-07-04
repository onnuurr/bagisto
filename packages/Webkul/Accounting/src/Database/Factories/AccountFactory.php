<?php

namespace Webkul\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Accounting\Models\Account;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'parent_id'       => null,
            'code'            => $this->faker->unique->numerify('####'),
            'name'            => $this->faker->words(2, true),
            'type'            => $this->faker->randomElement([
                Account::TYPE_ASSET,
                Account::TYPE_LIABILITY,
                Account::TYPE_EQUITY,
                Account::TYPE_REVENUE,
                Account::TYPE_EXPENSE,
            ]),
            'description'     => $this->faker->sentence,
            'opening_balance' => 0,
            'is_system'       => false,
            'is_active'       => true,
        ];
    }
}
