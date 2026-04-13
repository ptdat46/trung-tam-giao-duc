<?php

namespace Database\Seeders;

use App\Models\CourseSession;
use App\Models\Room;
use App\Models\ScheduleRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleRequestSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        $sessions = CourseSession::with('room')->get();
        $rooms    = Room::where('status', '!=', Room::STATUS_MAINTENANCE)->get();
        $admins   = User::where('role', 'admin')->get();

        $reasons = [
            'Trùng lịch với lớp khác, cần đổi phòng.',
            'Giáo viên bận họp, xin đổi sang buổi chiều.',
            'Sĩ số lớp tăng, cần phòng lớn hơn.',
            'Cần thêm buổi ôn tập cho kỳ thi sắp tới.',
            'Phòng hiện tại bị hỏng máy chiếu, xin đổi phòng.',
            'Học sinh xin đổi lịch để thi thử ở trung tâm khác.',
            'Giáo viên bị ốm, xin nghỉ và đổi lịch.',
            'Trùng lịch với buổi kiểm tra của khóa học khác.',
            'Cần chuyển sang phòng có máy tính để dạy thực hành.',
            'Lớp học thêm buổi bổ sung vì tiến độ chậm.',
            'Đổi phòng để gần hơn với phòng học chính của lớp.',
            'Xin đổi sang khung giờ sáng sớm để dạy xong đi công tác.',
        ];

        $newStatuses = [
            ScheduleRequest::STATUS_APPROVED,
            ScheduleRequest::STATUS_REJECTED,
            ScheduleRequest::STATUS_PENDING,
        ];

        // ─────────────────────────────────────────────
        // TYPE 0 — Change Schedule / Room (70% of requests)
        // ─────────────────────────────────────────────
        $changeCount = (int) floor(count($sessions) * 0.7 * 0.5); // ~50% of sessions get a request
        $usedSessions = [];

        for ($i = 0; $i < min($changeCount, count($sessions)); $i++) {
            $session = $sessions->whereNotIn('id', $usedSessions)->random();
            $usedSessions[] = $session->id;

            $teacher = $teachers->random();
            $status  = fake()->randomElement($newStatuses);

            // Determine new schedule — random day offset and time change
            $dayOffset  = fake()->numberBetween(1, 7);
            $hourShift  = fake()->randomElement([-2, -1, 1, 2]);
            $newDate    = Carbon::parse($session->session_date)->addDays($dayOffset);
            $origHour   = (int) substr($session->start_time, 0, 2);
            $newHour    = max(7, min(20, $origHour + $hourShift));
            $newTime    = sprintf('%02d:%02d:00', $newHour, fake()->randomElement([0, 30]));
            $newDuration= fake()->randomElement([60, 90, 120]);

            $reviewer   = $status !== ScheduleRequest::STATUS_PENDING ? $admins->random() : null;
            $reviewedAt = $status !== ScheduleRequest::STATUS_PENDING
                ? Carbon::now()->subDays(fake()->numberBetween(1, 10))
                : null;

            // New room or same
            $newRoom = fake()->boolean(60) ? $rooms->where('id', '!=', $session->room_id)->random() : $session->room;

            ScheduleRequest::create([
                'user_id'          => $teacher->id,
                'session_id'       => $session->id,
                'type'             => ScheduleRequest::TYPE_CHANGE_SCHEDULE,
                'old_room_id'      => $session->room_id,
                'old_session_date' => $session->session_date,
                'old_start_time'   => $session->start_time,
                'new_room_id'      => $newRoom?->id,
                'new_session_date' => $newDate->toDateString(),
                'new_start_time'   => $newTime,
                'new_duration'     => $newDuration,
                'reason'           => fake()->randomElement($reasons),
                'status'           => $status,
                'reviewed_by'      => $reviewer?->id,
                'reviewed_at'      => $reviewedAt,
            ]);
        }

        // ─────────────────────────────────────────────
        // TYPE 1 — Add New Session (30% of requests)
        // ─────────────────────────────────────────────
        $addCount = (int) floor($changeCount * 0.4);

        for ($i = 0; $i < $addCount; $i++) {
            $teacher  = $teachers->random();
            $class    = $session?->class ?? $sessions->random()->class;

            // Generate brand new date/time
            $newDate = Carbon::now()->addDays(fake()->numberBetween(3, 30));
            $newHour = fake()->numberBetween(7, 18);
            $newTime = sprintf('%02d:%02d:00', $newHour, fake()->randomElement([0, 30]));
            $newDuration = fake()->randomElement([45, 60, 90, 120]);

            $status   = fake()->randomElement($newStatuses);
            $reviewer = $status !== ScheduleRequest::STATUS_PENDING ? $admins->random() : null;
            $reviewedAt = $status !== ScheduleRequest::STATUS_PENDING
                ? Carbon::now()->subDays(fake()->numberBetween(1, 10))
                : null;

            ScheduleRequest::create([
                'user_id'          => $teacher->id,
                'session_id'       => null, // TYPE_ADD_NEW — no existing session
                'type'             => ScheduleRequest::TYPE_ADD_NEW,
                'old_room_id'      => null,
                'old_session_date' => null,
                'old_start_time'   => null,
                'new_room_id'      => $rooms->random()->id,
                'new_session_date' => $newDate->toDateString(),
                'new_start_time'   => $newTime,
                'new_duration'     => $newDuration,
                'reason'           => fake()->randomElement($reasons),
                'status'           => $status,
                'reviewed_by'      => $reviewer?->id,
                'reviewed_at'      => $reviewedAt,
            ]);
        }
    }
}