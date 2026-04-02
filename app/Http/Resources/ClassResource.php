<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'type'         => $this->type,
            'room_number'   => $this->room_number,
            'meeting_link'  => $this->meeting_link,
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'status'       => $this->status,
            'course'       => new CourseResource($this->whenLoaded('course')),
        ];
    }
}
