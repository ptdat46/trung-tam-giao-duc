<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'unique:courses,slug'],
            'description' => ['nullable', 'string'],
            'price'       => ['nullable', 'integer', 'min:0'],
            'thumbnail'   => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên khóa học là bắt buộc.',
            'name.max'      => 'Tên khóa học không được vượt quá 255 ký tự.',
            'slug.required' => 'Slug là bắt buộc.',
            'slug.unique'   => 'Slug đã được sử dụng.',
            'price.integer' => 'Giá phải là số nguyên.',
            'price.min'     => 'Giá không được nhỏ hơn 0.',
        ];
    }
}
