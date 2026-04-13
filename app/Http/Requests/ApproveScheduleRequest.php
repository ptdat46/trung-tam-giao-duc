<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'integer', Rule::in([1, 2])], // 1=approved, 2=rejected
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Giá trị status không hợp lệ. Chỉ chấp nhận 1 (đồng ý) hoặc 2 (từ chối).',
        ];
    }
}