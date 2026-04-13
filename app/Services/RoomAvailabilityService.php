<?php

namespace App\Services;

use App\Models\CourseSession;
use Carbon\Carbon;

class RoomAvailabilityService
{
    /**
     * Check if a room is available for a given time slot.
     *
     * Overlap formula:
     *   newStart < existingEnd  AND  newEnd > existingStart
     *
     * @param  int       $room_id
     * @param  string    $session_date   Y-m-d
     * @param  string    $start_time     H:i:s
     * @param  int       $duration       minutes
     * @param  int|null  $exclude_session_id  skip this session (for updates)
     * @return bool
     */
    public function checkRoomAvailability(
        int     $room_id,
        string  $session_date,
        string  $start_time,
        int     $duration,
        ?int    $exclude_session_id = null
    ): bool {
        $newStart = Carbon::parse("$session_date $start_time");
        $newEnd   = $newStart->copy()->addMinutes($duration);

        $query = CourseSession::where('room_id', $room_id)
            ->where('session_date', $session_date)
            ->where('status', '!=', CourseSession::STATUS_CANCELLED);

        if ($exclude_session_id !== null) {
            $query->where('id', '!=', $exclude_session_id);
        }

        foreach ($query->get() as $session) {
            $existingStart = $session->session_date->copy()
                ->setTimeFromTimeString($session->start_time);
            $existingEnd = $existingStart->copy()->addMinutes($session->duration);

            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                return false; // conflict found
            }
        }

        return true; // no conflict
    }

    /**
     * Return the conflicting CourseSession or null.
     *
     * @param  int       $room_id
     * @param  string    $session_date   Y-m-d
     * @param  string    $start_time     H:i:s
     * @param  int       $duration       minutes
     * @param  int|null  $exclude_session_id
     * @return CourseSession|null
     */
    public function getConflictingSession(
        int     $room_id,
        string  $session_date,
        string  $start_time,
        int     $duration,
        ?int    $exclude_session_id = null
    ): ?CourseSession {
        $newStart = Carbon::parse("$session_date $start_time");
        $newEnd   = $newStart->copy()->addMinutes($duration);

        $query = CourseSession::with(['class', 'teacher', 'room'])
            ->where('room_id', $room_id)
            ->where('session_date', $session_date)
            ->where('status', '!=', CourseSession::STATUS_CANCELLED);

        if ($exclude_session_id !== null) {
            $query->where('id', '!=', $exclude_session_id);
        }

        foreach ($query->get() as $session) {
            $existingStart = $session->session_date->copy()
                ->setTimeFromTimeString($session->start_time);
            $existingEnd = $existingStart->copy()->addMinutes($session->duration);

            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                return $session;
            }
        }

        return null;
    }
}