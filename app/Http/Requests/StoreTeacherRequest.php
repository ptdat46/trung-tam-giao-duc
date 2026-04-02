<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
        return [
            'teachers' => ['required', 'array', 'min:1'],
            'teachers.*.name'     => ['required', 'string', 'max:255'],
            'teachers.*.email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'teachers.*.password' => ['required', 'string', 'min:6'],
            'teachers.*.phone'   => ['nullable', 'string', 'max:20'],
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
            'teachers.required' => 'Danh sách giáo viên không được để trống.',
            'teachers.*.name.required'     => 'Tên giáo viên là bắt buộc.',
            'teachers.*.email.required'    => 'Email giáo viên là bắt buộc.',
            'teachers.*.email.unique'      => 'Email đã được sử dụng.',
            'teachers.*.password.required' => 'Mật khẩu là bắt buộc.',
            'teachers.*.password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ];
    }
}
