<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reg_no' => [
                'required',
                'string',
                Rule::unique('student_registrations', 'reg_no'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'phone_no' => ['required', 'string', 'max:10', Rule::unique('student_registrations', 'phone_no')],
            'email' => [
                'required',
                'email',
                Rule::unique('student_registrations', 'email'),
            ],
            'batch_id' => [
                'required',
                'integer',
                Rule::exists('batch_master', 'batch_id')->where('is_active', 1),
            ],
            'programme_id' => [
                'required',
                'integer',
                Rule::exists('programme_master', 'programme_id')->where('is_active', 1),
            ],
            'course_id' => [
                'required',
                'integer',
                Rule::exists('course_master', 'course_id')->where('is_active', 1),
            ],
        ];
    }
}
