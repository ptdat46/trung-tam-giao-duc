<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\ClassModel;
use App\Models\Lesson;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ClassModel::with('course')->get();

        foreach ($classes as $class) {
            $startDate = Carbon::parse($class->start_date ?? now()->subDays(30));
            $courseName = $class->course?->name ?? 'Khóa học';

            // ~20 sessions per class
            for ($i = 1; $i <= 20; $i++) {
                Session::firstOrCreate(
                    [
                        'class_id' => $class->id,
                        'title' => "Buổi $i - " . fake()->sentence(3),
                    ],
                    [
                        'session_date' => $startDate->copy()->addWeeks($i - 1)->toDateString(),
                        'type' => $class->type,
                    ]
                );
            }

            // ~10-13 lessons per class
            $lessonCount = rand(10, 13);
            for ($i = 1; $i <= $lessonCount; $i++) {
                Lesson::firstOrCreate(
                    [
                        'class_id' => $class->id,
                        'title' => "Bài $i: " . fake()->sentence(2),
                    ],
                    [
                        'content_type' => fake()->randomElement(['video', 'pdf', 'text']),
                        'content_body' => fake()->paragraphs(3, true),
                        'order' => $i,
                        'open_at' => $startDate->copy()->addWeeks($i - 1)->subDays(1),
                        'close_at' => $startDate->copy()->addWeeks($i + 12)->addDays(7),
                    ]
                );
            }

            // 3-5 assignments per class
            $assignmentCount = rand(3, 5);
            for ($i = 1; $i <= $assignmentCount; $i++) {
                Assignment::firstOrCreate(
                    [
                        'class_id' => $class->id,
                        'title' => "Bài tập $i - " . fake()->sentence(2),
                    ],
                    [
                        'description' => fake()->paragraph(2),
                        'file_path' => null,
                        'max_points' => fake()->randomElement([10, 20, 30, 50, 100]),
                        'due_date' => $startDate->copy()->addWeeks($i + 2)->addDays(rand(0, 5)),
                        'open_at' => $startDate->copy()->addWeeks($i),
                        'close_at' => $startDate->copy()->addWeeks($i + 3),
                    ]
                );
            }
        }
    }
}
