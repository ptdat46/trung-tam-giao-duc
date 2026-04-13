<?php

namespace App\Services;

use App\Models\CourseSession;
use App\Models\Room;
use App\Models\ScheduleRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SchedulingService
{
    public function __construct(
        private RoomAvailabilityService $availabilityService
    ) {}

    /**
     * Get sessions grouped by day for a given ISO week.
     *
     * @param  string  $isoWeek  e.g. "2026-W15"
     * @return Collection  [date => [CourseSession, ...], ...]
     */
    public function getSessionsByWeek(string $isoWeek): Collection
    {
        [$year, $week] = sscanf($isoWeek, '%d-W%d');

        $startOfWeek = Carbon::now()->setISODate((int) $year, (int) $week)->startOfWeek();
        $endOfWeek   = $startOfWeek->copy()->endOfWeek();

        return CourseSession::with(['class', 'teacher', 'room'])
            ->whereBetween('session_date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->where('status', '!=', CourseSession::STATUS_CANCELLED)
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn(CourseSession $s) => $s->session_date->toDateString());
    }

    /**
     * Assign (or re-assign) a session to a room, checking conflicts.
     *
     * @return array ['success' => bool, 'error' => ?string, 'session' => ?CourseSession]
     */
    public function assignSession(
        int     $sessionId,
        int     $roomId,
        string  $sessionDate,
        string  $startTime,
        int     $duration,
        int     $type = CourseSession::TYPE_REGULAR
    ): array {
        $room = Room::find($roomId);
        if (!$room) {
            return ['success' => false, 'error' => 'Phòng không tồn tại.', 'session' => null];
        }

        $session = CourseSession::find($sessionId);
        if (!$session) {
            return ['success' => false, 'error' => 'Buổi học không tồn tại.', 'session' => null];
        }

        if (!$this->availabilityService->checkRoomAvailability(
            $roomId, $sessionDate, $startTime, $duration, $sessionId
        )) {
            $conflict = $this->availabilityService->getConflictingSession(
                $roomId, $sessionDate, $startTime, $duration, $sessionId
            );
            $msg = $conflict
                ? "Phòng {$room->name} đã được đặt cho buổi \"{$conflict->title}\" "
                  . "từ {$conflict->start_time} đến {$conflict->end_time->format('H:i')}."
                : "Phòng {$room->name} không trống vào khung giờ yêu cầu.";
            return ['success' => false, 'error' => $msg, 'session' => null];
        }

        $session->update([
            'room_id'      => $roomId,
            'session_date' => $sessionDate,
            'start_time'   => $startTime,
            'duration'     => $duration,
            'type'         => $type,
            'status'       => CourseSession::STATUS_SCHEDULED,
        ]);

        return ['success' => true, 'error' => null, 'session' => $session->fresh(['class', 'teacher', 'room'])];
    }

    /**
     * Process an approved schedule request — update or create the session.
     */
    public function applyApprovedRequest(ScheduleRequest $request): CourseSession
    {
        if ($request->type === ScheduleRequest::TYPE_ADD_NEW) {
            return CourseSession::create([
                'class_id'     => $request->session->class_id ?? null,
                'teacher_id'   => $request->user_id,
                'title'        => $request->session->title ?? 'Buổi học bổ sung',
                'session_date' => $request->new_session_date,
                'start_time'   => $request->new_start_time,
                'duration'     => $request->new_duration ?? 60,
                'room_id'      => $request->new_room_id,
                'type'         => CourseSession::TYPE_REGULAR,
                'status'       => CourseSession::STATUS_SCHEDULED,
            ]);
        }

        // TYPE_CHANGE_SCHEDULE: update the existing session
        $session = $request->session;
        $session->update([
            'room_id'      => $request->new_room_id ?? $session->room_id,
            'session_date' => $request->new_session_date ?? $session->session_date,
            'start_time'   => $request->new_start_time ?? $session->start_time,
        ]);

        return $session->fresh(['class', 'teacher', 'room']);
    }

    /**
     * Compute occupancy stats for dashboard.
     *
     * @return array
     */
    public function getOccupancyStats(): array
    {
        $total  = Room::count();
        $inUse  = Room::where('status', Room::STATUS_IN_USE)->count();
        $empty  = Room::where('status', Room::STATUS_EMPTY)->count();
        $maint  = Room::where('status', Room::STATUS_MAINTENANCE)->count();

        return [
            'total_rooms'     => $total,
            'in_use'         => $inUse,
            'empty'          => $empty,
            'maintenance'    => $maint,
            'occupancy_ratio'=> $total > 0 ? round($inUse / $total, 4) : 0,
        ];
    }
}