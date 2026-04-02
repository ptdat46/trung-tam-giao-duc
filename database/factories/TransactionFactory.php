<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 2]),
            'course_id' => Course::factory(),
            'amount' => fake()->randomElement([499000, 699000, 899000, 999000]),
            'proof_image' => null,
            'status' => fake()->randomElement([0, 1, 2]),
        ];
    }
}
