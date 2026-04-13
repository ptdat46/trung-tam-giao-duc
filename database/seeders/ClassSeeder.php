<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\ClassModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        $courses = Course::all();

        $now = Carbon::now();

        $classConfigs = [
            // Mỗi course có 2-3 lớp, chia đều cho các giáo viên
        ];

        foreach ($courses as $course) {
            // Mỗi khóa học tạo 2-3 lớp
            $classCount = rand(2, 3);

            for ($i = 0; $i < $classCount; $i++) {
                $teacher = $teachers->random();
                $type = fake()->randomElement([0, 1]);
                $status = fake()->randomElement(['scheduled', 'ongoing', 'completed', 'cancelled']);

                $startDate = $now->copy()->addDays(rand(-30, 60));
                $endDate = $startDate->copy()->addMonths(rand(1, 4));

                // Xác định status dựa trên ngày
                if ($status === 'ongoing') {
                    $startDate = $now->copy()->subDays(rand(5, 20));
                    $endDate = $now->copy()->addDays(rand(10, 60));
                } elseif ($status === 'completed') {
                    $startDate = $now->copy()->subDays(rand(60, 120));
                    $endDate = $now->copy()->subDays(rand(1, 30));
                } elseif ($status === 'scheduled') {
                    $startDate = $now->copy()->addDays(rand(5, 45));
                    $endDate = $startDate->copy()->addMonths(rand(1, 4));
                }

                ClassModel::firstOrCreate(
                    [
                        'course_id' => $course->id,
                        'teacher_id' => $teacher->id,
                        'name' => $course->name . ' - Lớp ' . chr(65 + $i),
                    ],
                    [
                        'type' => $type,
                        'meeting_link' => $type === 0 ? 'https://meet.google.com/' . fake()->uuid : null,
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString(),
                        'status' => $status,
                    ]
                );
            }
        }
    }
}
