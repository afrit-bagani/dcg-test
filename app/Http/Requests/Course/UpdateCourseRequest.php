<?php

namespace App\Http\Requests\Course;

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

        $id = $this->route('id');

        return [
            'programme_id' => ['required', 'integer', Rule::exists('programme_master', 'programme_id')],
            'code' => ['required', 'string', Rule::unique('course_master', 'code')->ignore($id, 'course_id')],
            'is_active' => ['required', 'boolean:']
        ];
    }
}
