<?php

declare(strict_types=1);

namespace App\Http\Requests\MenPost;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for flagging men posts
 * 
 * Validates flag data including flag type
 * (red, green, or neutral) for men posts.
 * 
 * @package App\Http\Requests\MenPost
 */
final class FlagMenPostRequest extends FormRequest
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
            'flag_type' => ['required', 'string', 'in:red,green,neutral'],
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
            'flag_type.required' => 'Flag type is required.',
            'flag_type.in' => 'Flag type must be "red", "green", or "neutral".',
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
            'flag_type' => 'flag type',
        ];
    }
}


