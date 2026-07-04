<?php

namespace Webkul\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Accounting\Models\JournalEntryLine;

class JournalEntryLineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JournalEntryLine::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $amount = $this->faker->randomFloat(4, 10, 1000);

        return [
            'debit'       => $amount,
            'credit'      => 0,
            'base_debit'  => $amount,
            'base_credit' => 0,
            'description' => $this->faker->sentence,
        ];
    }
}
