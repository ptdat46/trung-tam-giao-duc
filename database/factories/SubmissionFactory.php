<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'assignment_id' => Assignment::factory(),
            'user_id' => User::factory()->state(['role' => 2]),
            'file_path' => 'submissions/' . fake()->uuid . '.pdf',
            'grade' => fake()->randomFloat(2, 0, 10),
            'teacher_comment' => fake()->optional()->sentence(),
        ];
    }
}
