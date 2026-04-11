<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'price'         => $this->price,
            'thumbnail'     => $this->thumbnail,
            'status'        => $this->status,
            'status_label'  => $this->statusLabel(),
            'classes_count' => $this->classes_count ?? $this->classes()->count(),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            0 => 'Chờ khai giảng',
            1 => 'Đang giảng dạy',
            2 => 'Đã hủy',
            default => 'Không xác định',
        };
    }
}
