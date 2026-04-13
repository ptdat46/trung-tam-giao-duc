<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'teacher';
    }

    public function rules(): array
    {
        return [
            'type'            => ['required', 'integer', 'in:0,1'],
            'session_id'      => ['required_if:type,0', 'nullable', 'integer', 'exists:course_sessions,id'],
            'new_room_id'     => ['nullable', 'integer', 'exists:rooms,id'],
            'new_session_date'=> ['required', 'date', 'date_format:Y-m-d'],
            'new_start_time'  => ['required', 'date_format:H:i'],
            'new_duration'    => ['nullable', 'integer', 'min:15', 'max:480'],
            'reason'          => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'             => 'Loại yêu cầu là bắt buộc.',
            'type.in'                   => 'Loại yêu cầu không hợp lệ.',
            'session_id.required_if'    => 'Buổi học là bắt buộc khi yêu cầu đổi lịch.',
            'session_id.exists'         => 'Buổi học không tồn tại.',
            'new_room_id.exists'        => 'Phòng không tồn tại.',
            'new_session_date.required'=> 'Ngày học mới là bắt buộc.',
            'new_session_date.date_format' => 'Ngày học phải đúng định dạng Y-m-d.',
            'new_start_time.required'  => 'Giờ bắt đầu mới là bắt buộc.',
            'new_start_time.date_format' => 'Giờ bắt đầu phải đúng định dạng H:i.',
            'new_duration.min'         => 'Thời lượng tối thiểu là 15 phút.',
            'new_duration.max'         => 'Thời lượng tối đa là 8 giờ (480 phút).',
            'reason.max'               => 'Lý do không được vượt quá 1000 ký tự.',
        ];
    }
}
