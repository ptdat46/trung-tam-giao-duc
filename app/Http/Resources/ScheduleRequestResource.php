<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'user'             => $this->whenLoaded('user', fn() => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ]),
            'session'          => $this->whenLoaded('session', fn() => new SessionResource($this->session)),
            'type'             => $this->type,
            'type_label'       => $this->type === 0 ? 'Change Schedule / Room' : 'Add New Session',
            'old_room'         => $this->whenLoaded('oldRoom', fn() => [
                'id'   => $this->oldRoom->id,
                'name' => $this->oldRoom->name,
            ]),
            'old_session_date' => $this->old_session_date?->toDateString(),
            'old_start_time'   => $this->old_start_time,
            'new_room'         => $this->whenLoaded('newRoom', fn() => [
                'id'   => $this->newRoom->id,
                'name' => $this->newRoom->name,
            ]),
            'new_session_date' => $this->new_session_date?->toDateString(),
            'new_start_time'   => $this->new_start_time,
            'new_duration'     => $this->new_duration,
            'reason'           => $this->reason,
            'status'           => $this->status,
            'status_label'     => match ($this->status) {
                0 => 'Pending',
                1 => 'Approved',
                2 => 'Rejected',
            },
            'reviewer'         => $this->whenLoaded('reviewer', fn() => [
                'id'   => $this->reviewer->id,
                'name' => $this->reviewer->name,
            ]),
            'reviewed_at'      => $this->reviewed_at?->toIso8601String(),
            'created_at'       => $this->created_at->toIso8601String(),
        ];
    }
}