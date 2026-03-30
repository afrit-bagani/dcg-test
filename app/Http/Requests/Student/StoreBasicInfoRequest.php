<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreBasicInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reg_no' => 'required|string|max:100|unique:student_basic_information,reg_no',
            'surname' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'full_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:100',
            'father_name' => 'required|string|max:100',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'abc_id' => 'nullable|string|max:50',
            'aadhaar_no' => 'required|string|unique:student_basic_information,aadhaar_no',
            'nationality' => 'required|string',
            'domicile' => 'required|string',
            'mobile_no' => 'required|string|max:20',
            'email' => 'required|email|unique:student_basic_information,email',
            'religion' => 'nullable|string',
            'category' => 'required|string',
            'caste' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'marital_status' => 'required|string',
            'annual_family_income' => 'nullable|numeric',
            'parent_mobile' => 'nullable|string|max:20',
            'is_blind' => 'required|boolean',
            'is_bpl' => 'required|boolean',
            'is_minority' => 'required|boolean',
            'is_ph' => 'required|boolean',

            // Address Validation
            'present_address_1' => 'required|string|max:255',
            'present_city' => 'required|string|max:100',
            'present_country' => 'required|string|max:100',
            'present_state' => 'required|string|max:100',
            'present_district' => 'required|string|max:100',
            'present_pin' => 'required|string|max:20',
            'permanent_address_1' => 'required|string|max:255',
            'permanent_city' => 'required|string|max:100',
            'permanent_country' => 'required|string|max:100',
            'permanent_state' => 'required|string|max:100',
            'permanent_district' => 'required|string|max:100',
            'permanent_pin' => 'required|string|max:20',

            // Education Validation
            'admission_type' => 'required|string|max:50',
            'exam_name' => 'required|string|max:150',
            'passing_month_year' => 'required|string|max:50',
            'board_type' => 'required|string|max:100',
            'institution_name' => 'required|string|max:255',
            'board_university_name' => 'required|string|max:255',
            'max_marks' => 'nullable|numeric',
            'obtained_marks' => 'nullable|numeric',
            'percentage' => 'nullable|numeric',
        ];
    }
}
