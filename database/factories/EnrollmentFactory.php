<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 2]),
            'class_id' => ClassModel::factory(),
            'status' => fake()->randomElement([0, 1, 2]),
        ];
    }
}
