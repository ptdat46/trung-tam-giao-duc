<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $teacherId = $this->route('teacher');

        return [
            'name'   => ['nullable', 'string', 'max:255'],
            'email'  => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($teacherId),
            ],
            'phone'   => ['nullable', 'string', 'max:20'],
            'status' => ['nullable', 'integer', Rule::in([0, 1])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Email đã được sử dụng bởi tài khoản khác.',
            'status.in'     => 'Trạng thái chỉ nhận giá trị 0 (không hoạt động) hoặc 1 (hoạt động).',
        ];
    }
}
