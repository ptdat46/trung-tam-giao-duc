<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:6'],
            'repassword' => ['required', 'string', 'min:6', 'same:password'],
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
            'name.required'       => 'Tên là bắt buộc.',
            'name.max'            => 'Tên không được vượt quá 255 ký tự.',
            'email.required'       => 'Email là bắt buộc.',
            'email.email'         => 'Email không hợp lệ.',
            'email.unique'        => 'Email đã được sử dụng.',
            'password.required'   => 'Mật khẩu là bắt buộc.',
            'password.min'        => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'repassword.required' => 'Mật khẩu xác nhận là bắt buộc.',
            'repassword.same'     => 'Mật khẩu xác nhận không khớp.',
        ];
    }
}
