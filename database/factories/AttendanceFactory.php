<?php

namespace Database\Factories;

use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'session_id' => Session::factory(),
            'user_id' => User::factory()->state(['role' => 2]),
            'status' => fake()->randomElement([0, 1, 2, 3]),
        ];
    }
}
