<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'code' => ['required', 'string', Rule::unique('course_master', 'code')],
            'name' => ['required', 'string', Rule::unique('course_master', 'name')],
            'programme_id' => ['required', 'integer', Rule::exists('programme_master', 'programme_id')],
            'is_active' => ['required', 'boolean']
        ];
    }
}
