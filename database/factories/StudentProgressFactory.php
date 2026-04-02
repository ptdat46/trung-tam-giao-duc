<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentProgressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 2]),
            'class_id' => ClassModel::factory(),
            'attendance_count' => fake()->numberBetween(0, 20),
            'lesson_viewed_count' => fake()->numberBetween(0, 15),
            'assignment_submitted_count' => fake()->numberBetween(0, 10),
        ];
    }
}
