<?php

declare(strict_types=1);

namespace App\Http\Requests\MenPost;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for creating men posts
 * 
 * Validates men post creation data including
 * name, city, tags, caption, and optional image.
 * 
 * @package App\Http\Requests\MenPost
 */
final class StoreMenPostRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:150', 'min:2'],
            'city' => ['required', 'string', 'max:100', 'min:2'],
            'tags' => ['nullable', 'array', 'max:10'],
            'tags.*' => ['string', 'max:50'],
            'caption' => ['required', 'string', 'max:2000', 'min:10'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
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
            'full_name.required' => 'Full name is required.',
            'full_name.min' => 'Name must be at least 2 characters.',
            'full_name.max' => 'Name cannot exceed 150 characters.',
            'city.required' => 'City is required.',
            'city.min' => 'City must be at least 2 characters.',
            'city.max' => 'City cannot exceed 100 characters.',
            'tags.array' => 'Tags must be an array.',
            'tags.max' => 'Maximum 10 tags allowed.',
            'tags.*.string' => 'Each tag must be a string.',
            'tags.*.max' => 'Each tag cannot exceed 50 characters.',
            'caption.required' => 'Caption is required.',
            'caption.min' => 'Caption must be at least 10 characters.',
            'caption.max' => 'Caption cannot exceed 2000 characters.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'image.max' => 'Image cannot exceed 5MB.',
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
            'full_name' => 'full name',
            'city' => 'city',
            'tags' => 'tags',
            'caption' => 'caption',
            'image' => 'image file',
        ];
    }
}


