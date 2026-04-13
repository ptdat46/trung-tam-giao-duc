<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveScheduleRequest;
use App\Http\Requests\AssignSessionRequest;
use App\Http\Resources\RoomResource;
use App\Http\Resources\ScheduleRequestResource;
use App\Http\Resources\SessionResource;
use App\Models\Room;
use App\Models\ScheduleRequest;
use App\Services\RoomAvailabilityService;
use App\Services\SchedulingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SchedulingController extends Controller
{
    public function __construct(
        private SchedulingService       $schedulingService,
        private RoomAvailabilityService  $availabilityService,
    ) {}

    /**
     * GET /api/admin/scheduling/calendar
     * Returns sessions grouped by date for a given ISO week.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function calendar(Request $request): JsonResponse
    {
        $request->validate([
            'week' => ['sometimes', 'regex:/^\d{4}-W\d{2}$/'],
        ]);

        $week = $request->query('week', now()->format('Y-\\WW'));
        $sessionsByDay = $this->schedulingService->getSessionsByWeek($week);

        $formatted = $sessionsByDay->mapWithKeys(
            fn($sessions, $date) => [$date => SessionResource::collection($sessions)]
        )->toArray();

        return Common::successResponse('Lấy lịch theo tuần thành công.', [
            'week'    => $week,
            'sessions'=> $formatted,
        ]);
    }

    /**
     * GET /api/admin/scheduling/rooms
     * Returns all rooms with current status (updated by batch job).
     *
     * @return AnonymousResourceCollection
     */
    public function rooms(): AnonymousResourceCollection
    {
        $rooms = Room::orderBy('name')->get();
        return RoomResource::collection($rooms);
    }

    /**
     * GET /api/admin/scheduling/stats
     * Returns occupancy stats (ratio of rooms in-use).
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        $stats = $this->schedulingService->getOccupancyStats();
        return Common::successResponse('Lấy thống kê phòng học thành công.', $stats);
    }

    /**
     * POST /api/admin/scheduling/assign
     * Assign a session to a room with conflict detection.
     *
     * @param  AssignSessionRequest  $request
     * @return JsonResponse
     */
    public function assign(AssignSessionRequest $request): JsonResponse
    {
        $result = $this->schedulingService->assignSession(
            sessionId   : $request->validated('session_id'),
            roomId      : $request->validated('room_id'),
            sessionDate : $request->validated('session_date'),
            startTime   : $request->validated('start_time'),
            duration    : $request->validated('duration'),
            type        : $request->validated('type', 0),
        );

        if (!$result['success']) {
            return Common::errorResponse($result['error'], 409);
        }

        return Common::successResponse(
            'Gán phòng cho buổi học thành công.',
            ['session' => new SessionResource($result['session'])],
            201
        );
    }

    /**
     * GET /api/admin/scheduling/requests
     * List all schedule requests with optional status filter.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function listRequests(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['sometimes', 'integer', 'in:0,1,2'],
        ]);

        $query = ScheduleRequest::with(['user', 'session', 'oldRoom', 'newRoom', 'reviewer']);

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $requests = $query->orderByDesc('created_at')->paginate(20);

        return Common::successResponse('Lấy danh sách yêu cầu đổi lịch thành công.', [
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
     * PATCH /api/admin/scheduling/approve-request/{id}
     * Approve or reject a schedule request.
     *
     * @param  ApproveScheduleRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function approveRequest(ApproveScheduleRequest $request, int $id): JsonResponse
    {
        $scheduleRequest = ScheduleRequest::with(['session', 'newRoom'])->find($id);

        if (!$scheduleRequest) {
            return Common::errorResponse('Yêu cầu đổi lịch không tồn tại.', null, 404);
        }

        if ($scheduleRequest->status !== ScheduleRequest::STATUS_PENDING) {
            $statusLabel = $scheduleRequest->status === 1 ? 'đã được duyệt' : 'đã bị từ chối';
            return Common::errorResponse("Yêu cầu này {$statusLabel}.", null, 422);
        }

        $adminId = $request->user()->id;

        // Rejection
        if ($request->validated('status') === 2) {
            $scheduleRequest->update([
                'status'      => ScheduleRequest::STATUS_REJECTED,
                'reviewed_by' => $adminId,
                'reviewed_at' => now(),
            ]);
            return Common::successResponse('Yêu cầu đổi lịch đã bị từ chối.', [
                'request' => new ScheduleRequestResource(
                    $scheduleRequest->fresh(['user', 'session', 'reviewer'])
                ),
            ]);
        }

        // Approval — verify room availability first
        if ($scheduleRequest->new_room_id && $scheduleRequest->new_session_date) {
            $available = $this->availabilityService->checkRoomAvailability(
                $scheduleRequest->new_room_id,
                $scheduleRequest->new_session_date->toDateString(),
                $scheduleRequest->new_start_time,
                $scheduleRequest->new_duration ?? 60,
                $scheduleRequest->session_id
            );

            if (!$available) {
                $room = $scheduleRequest->newRoom;
                return Common::errorResponse(
                    "Phòng {$room->name} bị trùng lịch vào khung giờ yêu cầu.",
                    null,
                    409
                );
            }
        }

        // Apply the change to courses_sessions
        $updatedSession = $this->schedulingService->applyApprovedRequest($scheduleRequest);

        $scheduleRequest->update([
            'status'      => ScheduleRequest::STATUS_APPROVED,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);

        return Common::successResponse('Yêu cầu đổi lịch đã được duyệt và cập nhật vào hệ thống.', [
            'request' => new ScheduleRequestResource(
                $scheduleRequest->fresh(['user', 'session', 'reviewer'])
            ),
            'session' => new SessionResource($updatedSession),
        ]);
    }
}