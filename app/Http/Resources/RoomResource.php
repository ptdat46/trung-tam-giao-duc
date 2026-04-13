<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'status'      => $this->status,
            'status_label'=> match ($this->status) {
                0 => 'Empty',
                1 => 'In Use',
                2 => 'Maintenance',
            },
        ];
    }
}