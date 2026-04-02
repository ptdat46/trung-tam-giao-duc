<?php

namespace Database\Factories;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_id' => ClassModel::factory(),
            'title' => 'Bài tập ' . fake()->numberBetween(1, 15) . ': ' . fake()->sentence(3),
            'description' => fake()->paragraph(2),
            'file_path' => null,
            'max_points' => fake()->randomElement([10, 20, 30, 50, 100]),
            'due_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'open_at' => now()->subDays(3),
            'close_at' => fake()->dateTimeBetween('+1 week', '+3 months'),
        ];
    }
}
