<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'class'        => $this->whenLoaded('class', fn() => [
                'id'   => $this->class->id,
                'name' => $this->class->name,
            ]),
            'teacher'      => $this->whenLoaded('teacher', fn() => [
                'id'   => $this->teacher->id,
                'name' => $this->teacher->name,
            ]),
            'room'         => $this->whenLoaded('room', fn() => [
                'id'   => $this->room->id,
                'name' => $this->room->name,
            ]),
            'session_date' => $this->session_date->toDateString(),
            'start_time'   => $this->start_time,
            'end_time'     => $this->end_time->format('H:i'),
            'duration'     => $this->duration,
            'type'         => $this->type,
            'type_label'   => match ($this->type) {
                0 => 'Regular',
                1 => 'Exam',
                2 => 'Practice',
            },
            'status'       => $this->status,
            'status_label' => match ($this->status) {
                0 => 'Scheduled',
                1 => 'In Progress',
                2 => 'Completed',
                3 => 'Cancelled',
            },
        ];
    }
}