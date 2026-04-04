<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GradeScaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'details' => 'required|array|min:1',
            'details.*.name' => 'required|string|max:50',
            'details.*.min_score' => 'required|numeric|min:0|max:100',
            'details.*.max_score' => 'required|numeric|min:0|max:100',
            'details.*.point' => 'required|numeric|min:0|max:10',
            'details.*.remarks' => 'nullable|string|max:255',
        ];
    }
}
