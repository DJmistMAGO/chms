<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = \App\Models\Booking::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reference_number' => strtoupper($this->faker->bothify('BK-####??')),
            'status' => 'pending',
            'expires_at' => now()->addHours(24),
            'check_in' => now()->addDays(1),
            'check_out' => now()->addDays(2),
            'number_of_guests' => $this->faker->numberBetween(1, 4),
            'room_price' => $this->faker->randomFloat(2, 100, 500),
            'total_price' => $this->faker->randomFloat(2, 100, 500),
            'room_type' => $this->faker->randomElement(['single', 'double', 'suite']),
            'floor_level' => $this->faker->numberBetween(1, 10),
            'ambiance' => $this->faker->randomElement(['romantic', 'family', 'business']),
            'food_package' => $this->faker->randomElement(['breakfast', 'half
-board', 'full-board']),
            'remarks' => $this->faker->sentence(),
            

        ];
    }
}
