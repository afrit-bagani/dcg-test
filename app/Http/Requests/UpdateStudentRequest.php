<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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

        $id = $this->route('id');

        return [
            'reg_no' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('student_registrations', 'reg_no')->ignore($id, 'student_id'),
            ],

            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('student_registrations', 'email')->ignore($id, 'student_id'),
            ],

            'batch_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('batch_master', 'batch_id')->where('is_active', 1),
            ],

            'programme_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('programme_master', 'programme_id')->where('is_active', 1),
            ],

            'course_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('course_master', 'course_id')->where('is_active', 1),
            ],

            'is_active' => [
                'sometimes',
                'required',
                'boolean',
            ],
        ];
    }
}
