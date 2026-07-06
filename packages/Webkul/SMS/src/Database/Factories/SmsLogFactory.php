<?php

namespace Webkul\SMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\SMS\Models\SmsLog;

class SmsLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmsLog::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'gateway' => $this->faker->randomElement(['twilio', 'vonage', 'msg91']),
            'event' => $this->faker->randomElement(['order_placed', 'order_shipped', 'order_cancelled', 'invoice_created', 'refund_created', 'two_factor']),
            'recipient' => $this->faker->e164PhoneNumber(),
            'message' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(['sent', 'failed']),
            'response' => $this->faker->sentence(6),
        ];
    }
}
