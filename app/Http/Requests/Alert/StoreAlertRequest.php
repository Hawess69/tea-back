<?php

declare(strict_types=1);

namespace App\Http\Requests\Alert;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for creating alerts
 * 
 * Validates alert creation data including
 * name to track for monitoring.
 * 
 * @package App\Http\Requests\Alert
 */
final class StoreAlertRequest extends FormRequest
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
            'name_to_track' => ['required', 'string', 'max:150', 'min:2'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name_to_track.required' => 'Name to track is required.',
            'name_to_track.min' => 'Name must be at least 2 characters.',
            'name_to_track.max' => 'Name cannot exceed 150 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * 
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name_to_track' => 'name to track',
        ];
    }
}


