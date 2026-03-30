<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBulkStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bulk_is_active' => ['required', 'boolean'],
            'selected_ids' => ['required', 'array|min:1'],
            'selected_ids.*' => ['integer', 'min:1'],
        ];
    }
}
