<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Enrollment;
use App\Models\StudentProgress;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $classes = ClassModel::all();

        foreach ($classes as $class) {
            $enrolledStudents = $students->random(rand(8, 15));

            foreach ($enrolledStudents as $student) {
                Enrollment::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'class_id' => $class->id,
                    ],
                    [
                        'status' => 1,
                    ]
                );

                StudentProgress::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'class_id' => $class->id,
                    ],
                    [
                        'attendance_count' => rand(0, 15),
                        'lesson_viewed_count' => rand(0, 10),
                        'assignment_submitted_count' => rand(0, 4),
                    ]
                );
            }
        }
    }
}
