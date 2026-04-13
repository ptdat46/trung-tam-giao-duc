<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequestRequest;
use App\Http\Resources\ScheduleRequestResource;
use App\Models\CourseSession;
use App\Models\ScheduleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleRequestController extends Controller
{
    /**
     * GET /api/teacher/schedule-requests
     * List the authenticated teacher's own schedule requests.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = ScheduleRequest::with(['session', 'oldRoom', 'newRoom'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at');

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $requests = $query->paginate(20);

        return Common::successResponse('Lấy danh sách yêu cầu thành công.', [
            'data'  => ScheduleRequestResource::collection($requests->items()),
            'meta'  => [
                'current_page' => $requests->currentPage(),
                'last_page'    => $requests->lastPage(),
                'per_page'     => $requests->perPage(),
                'total'        => $requests->total(),
            ],
        ]);
    }

    /**
     * POST /api/teacher/schedule-requests
     * Submit a new schedule change / add-new-session request.
     *
     * @param  StoreScheduleRequestRequest  $request
     * @return JsonResponse
     */
    public function store(StoreScheduleRequestRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $teacher   = $request->user();

        // For TYPE_CHANGE, capture the old room/date/time from the existing session
        $session = null;
        $oldRoomId      = null;
        $oldSessionDate = null;
        $oldStartTime   = null;

        if ($validated['type'] == ScheduleRequest::TYPE_CHANGE_SCHEDULE) {
            $session = CourseSession::with('room')->find($validated['session_id']);
            if (!$session) {
                return Common::errorResponse('Buổi học không tồn tại.', null, 404);
            }

            $oldRoomId      = $session->room_id;
            $oldSessionDate = $session->session_date;
            $oldStartTime   = $session->start_time;
        }

        $scheduleRequest = ScheduleRequest::create([
            'user_id'          => $teacher->id,
            'session_id'       => $session?->id,
            'type'             => $validated['type'],
            'old_room_id'      => $oldRoomId,
            'old_session_date' => $oldSessionDate,
            'old_start_time'   => $oldStartTime,
            'new_room_id'      => $validated['new_room_id'] ?? null,
            'new_session_date'=> $validated['new_session_date'],
            'new_start_time'   => $validated['new_start_time'],
            'new_duration'    => $validated['new_duration'] ?? 60,
            'reason'          => $validated['reason'] ?? null,
            'status'          => ScheduleRequest::STATUS_PENDING,
        ]);

        return Common::successResponse(
            'Yêu cầu đổi lịch đã được gửi thành công.',
            ['request' => new ScheduleRequestResource(
                $scheduleRequest->load(['session', 'oldRoom', 'newRoom'])
            )],
            201
        );
    }
}
