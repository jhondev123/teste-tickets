<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'quantity' => $this->faker->randomNumber(1),
            'situation' => $this->faker->randomElement(['A', 'I']),
            'delivery_date' => $this->faker->dateTime(),

        ];
    }
}
