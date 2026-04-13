<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarWeekResource extends JsonResource
{
    /**
     * Transforms a Collection of CourseSession grouped by date string.
     * The resource receives a Laravel Collection (grouped by date).
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return $this->map(fn($session) => new SessionResource($session))->toArray();
    }
}