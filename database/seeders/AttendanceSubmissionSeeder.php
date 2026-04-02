<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Session;
use App\Models\StudentProgress;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ClassModel::with('sessions')->get();

        // 200 attendances
        $attendanceCreated = 0;
        foreach ($classes as $class) {
            if ($attendanceCreated >= 200) break;

            $sessions = $class->sessions;
            $enrolledStudents = $class->enrollments()->where('status', 1)->with('user')->get();

            foreach ($sessions as $session) {
                if ($attendanceCreated >= 200) break;

                foreach ($enrolledStudents as $enrollment) {
                    if ($attendanceCreated >= 200) break;

                    Attendance::firstOrCreate(
                        [
                            'session_id' => $session->id,
                            'user_id' => $enrollment->user_id,
                        ],
                        [
                            'status' => fake()->randomElement([0, 1, 2, 3]),
                        ]
                    );
                    $attendanceCreated++;
                }
            }
        }

        // 200 submissions
        $submissionCreated = 0;
        $assignments = Assignment::with('classModel.enrollments')->get();

        foreach ($assignments as $assignment) {
            if ($submissionCreated >= 200) break;

            $enrolledStudents = $assignment->classModel->enrollments()->where('status', 1)->get();

            foreach ($enrolledStudents as $enrollment) {
                if ($submissionCreated >= 200) break;

                Submission::firstOrCreate(
                    [
                        'assignment_id' => $assignment->id,
                        'user_id' => $enrollment->user_id,
                    ],
                    [
                        'file_path' => 'submissions/' . fake()->uuid . '.pdf',
                        'grade' => fake()->randomFloat(2, 0, $assignment->max_points),
                        'teacher_comment' => fake()->optional()->sentence(),
                    ]
                );
                $submissionCreated++;
            }
        }
    }
}
