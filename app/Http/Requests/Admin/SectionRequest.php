<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
        return [
            'grade_level_id' => 'required|exists:grade_levels,id',
            'campus_id' => 'nullable|exists:campuses,id',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
