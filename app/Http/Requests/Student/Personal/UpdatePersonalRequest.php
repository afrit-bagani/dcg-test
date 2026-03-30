<?php

namespace App\Http\Requests\Student\Personal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $id = $this->route('id');

        return [
            'reg_no' => ['required', 'string', Rule::unique('student_registrations', 'reg_no')->ignore($id, 'student_id')],
            'name' => ['required', 'string', 'max:255'],
            'phone_no' => ['required', 'string', 'max:10', Rule::unique('student_registrations', 'phone_no')->ignore($id, 'student_id')],
            'email' => ['required', 'email', 'email', Rule::unique('student_registrations', 'email')->ignore($id)],
        ];
    }
}
