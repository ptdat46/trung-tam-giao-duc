<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'session_id'   => ['required', 'integer', 'exists:course_sessions,id'],
            'room_id'      => ['required', 'integer', 'exists:rooms,id'],
            'session_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
            ],
            'duration' => [
                'required',
                'integer',
                'min:15',
                'max:480',
            ],
            'type' => [
                'sometimes',
                'integer',
                'in:0,1,2',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.exists'         => 'Buổi học không tồn tại.',
            'room_id.exists'            => 'Phòng không tồn tại.',
            'session_date.after_or_equal' => 'Ngày học không thể là ngày trong quá khứ.',
            'duration.min'              => 'Thời lượng tối thiểu là 15 phút.',
            'duration.max'              => 'Thời lượng tối đa là 8 giờ (480 phút).',
        ];
    }
}