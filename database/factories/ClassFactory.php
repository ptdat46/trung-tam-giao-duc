<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassModel>
 */
class ClassFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement([0, 1]);
        return [
            'course_id' => Course::factory(),
            'teacher_id' => User::factory()->state(['role' => 1]),
            'name' => 'Lớp ' . fake()->numberBetween(1, 12) . fake()->randomElement(['A', 'B', 'C', 'D']) . ' - ' . fake()->numberBetween(1, 50),
            'type' => $type,
            'room_number' => $type === 1 ? 'Phòng ' . fake()->numberBetween(1, 20) : null,
            'meeting_link' => $type === 0 ? 'https://meet.google.com/' . fake()->uuid : null,
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => fake()->dateTimeBetween('+1 month', '+6 months'),
            'status' => fake()->randomElement(['scheduled', 'ongoing', 'completed', 'cancelled']),
        ];
    }
}
