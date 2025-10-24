<?php

declare(strict_types=1);

namespace App\Http\Requests\FeedPost;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for creating feed posts
 * 
 * Validates feed post creation data including
 * title, body, and optional image upload.
 * 
 * @package App\Http\Requests\FeedPost
 */
final class StoreFeedPostRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:200', 'min:5'],
            'body' => ['required', 'string', 'max:5000', 'min:10'],
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
            'title.required' => 'Post title is required.',
            'title.min' => 'Title must be at least 5 characters.',
            'title.max' => 'Title cannot exceed 200 characters.',
            'body.required' => 'Post content is required.',
            'body.min' => 'Content must be at least 10 characters.',
            'body.max' => 'Content cannot exceed 5000 characters.',
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
            'title' => 'post title',
            'body' => 'post content',
            'image' => 'image file',
        ];
    }
}


