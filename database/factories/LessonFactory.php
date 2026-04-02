<?php

namespace Database\Factories;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    public function definition(): array
    {
        $contentType = fake()->randomElement(['video', 'pdf', 'text']);
        return [
            'class_id' => ClassModel::factory(),
            'title' => 'Bài ' . fake()->numberBetween(1, 20) . ': ' . fake()->sentence(3),
            'content_type' => $contentType,
            'content_body' => $contentType === 'text'
                ? fake()->paragraphs(3, true)
                : 'https://example.com/' . fake()->uuid . '.' . $contentType,
            'order' => fake()->numberBetween(1, 20),
            'open_at' => now()->subDays(5),
            'close_at' => now()->addMonths(4),
        ];
    }
}
