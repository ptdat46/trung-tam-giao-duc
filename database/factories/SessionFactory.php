<?php

namespace Database\Factories;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_id' => ClassModel::factory(),
            'title' => 'Buổi ' . fake()->numberBetween(1, 30) . ' - ' . fake()->sentence(3),
            'session_date' => fake()->dateTimeBetween('-2 months', '+4 months'),
            'type' => fake()->randomElement([0, 1]),
        ];
    }
}
