<?php

namespace Webkul\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Accounting\Models\JournalEntry;

class JournalEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JournalEntry::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'fiscal_year_id' => null,
            'entry_number' => 'JE-'.$this->faker->unique->numerify('######'),
            'entry_date' => $this->faker->date(),
            'reference_type' => JournalEntry::REFERENCE_MANUAL,
            'reference_id' => null,
            'description' => $this->faker->sentence,
            'currency_code' => 'USD',
            'exchange_rate' => 1,
            'status' => JournalEntry::STATUS_DRAFT,
            'posted_at' => null,
            'created_by' => null,
        ];
    }
}
