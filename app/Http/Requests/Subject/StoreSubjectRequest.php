<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'programme_id' => ['required', 'integer', Rule::exists('programme_master', 'programme_id')],
            'course_id' => ['required', 'integer', Rule::exists('course_master', 'course_id')],
            'code' => ['required', 'string', Rule::unique('subject_master', 'code')],
            'name' => ['required', 'string', 'max:255', Rule::unique('subject_master', 'name')],
            'is_active' => ['required', 'boolean'],
            'internal_full_marks' => ['required', 'numeric', 'min:0'],
            'internal_pass_marks' => ['required', 'numeric', 'min:0'],
            'theory_full_marks' => ['required', 'numeric', 'min:0'],
            'theory_pass_marks' => ['required', 'numeric', 'min:0'],
            'practical_full_marks' => ['required', 'numeric', 'min:0'],
            'practical_pass_marks' => ['required', 'numeric', 'min:0'],

        ];
    }
}
