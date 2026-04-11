<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course');

        return [
            'name'        => ['nullable', 'string', 'max:255'],
            'slug'        => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('courses', 'slug')->ignore($courseId),
            ],
            'description' => ['nullable', 'string'],
            'price'       => ['nullable', 'integer', 'min:0'],
            'thumbnail'   => ['nullable', 'string', 'max:255'],
            'status'      => ['nullable', 'integer', Rule::in([0, 1, 2])],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique'  => 'Slug đã được sử dụng.',
            'price.integer' => 'Giá phải là số nguyên.',
            'price.min'    => 'Giá không được nhỏ hơn 0.',
            'status.in'    => 'Trạng thái chỉ nhận giá trị 0 (Chờ khai giảng), 1 (Đang giảng dạy), 2 (Đã hủy).',
        ];
    }
}
