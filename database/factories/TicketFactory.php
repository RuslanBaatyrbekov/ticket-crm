<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Enums\TicketStatus;
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
            'customer_id' => Customer::factory(),
            'subject' => fake()->sentence(4),
            'content' => fake()->paragraph(3),
            'status' => fake()->randomElement(TicketStatus::cases()),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
