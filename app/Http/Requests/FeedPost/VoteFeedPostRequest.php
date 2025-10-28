<?php

declare(strict_types=1);

namespace App\Http\Requests\FeedPost;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for voting on feed posts
 * 
 * Validates vote data including vote type
 * (up or down) for feed posts.
 * 
 * @package App\Http\Requests\FeedPost
 */
final class VoteFeedPostRequest extends FormRequest
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
            'vote_type' => ['required', 'string', 'in:up,down'],
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
            'vote_type.required' => 'Vote type is required.',
            'vote_type.in' => 'Vote type must be either "up" or "down".',
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
            'vote_type' => 'vote type',
        ];
    }
}


