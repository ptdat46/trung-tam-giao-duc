<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->sentence(3);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomElement([299000, 499000, 699000, 899000, 999000]),
            'thumbnail' => 'https://picsum.photos/seed/' . fake()->uuid . '/800/400',
        ];
    }
}
