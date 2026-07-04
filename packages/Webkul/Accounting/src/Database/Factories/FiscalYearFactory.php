<?php

namespace Webkul\Accounting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Accounting\Models\FiscalYear;

class FiscalYearFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FiscalYear::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $year = $this->faker->year();

        return [
            'code' => 'FY'.$year,
            'start_date' => $year.'-01-01',
            'end_date' => $year.'-12-31',
            'status' => FiscalYear::STATUS_OPEN,
        ];
    }
}
