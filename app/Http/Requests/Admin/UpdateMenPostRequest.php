<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateMenPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'caption' => ['required', 'string'],
            'photo_url' => ['nullable', 'string', 'url'],
            'status' => ['nullable', 'string', 'in:published,draft,hidden'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert comma-separated tags string to array
        if ($this->has('tags') && is_string($this->tags)) {
            $tags = trim($this->tags);
            if (empty($tags)) {
                $this->merge(['tags' => []]);
            } else {
                $tagsArray = array_map('trim', explode(',', $tags));
                $tagsArray = array_filter($tagsArray); // Remove empty values
                $this->merge(['tags' => $tagsArray]);
            }
        }
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'full_name.max' => 'Full name must not exceed 255 characters.',
            'city.required' => 'City is required.',
            'city.max' => 'City must not exceed 100 characters.',
            'tags.string' => 'Tags must be a string.',
            'caption.required' => 'Caption is required.',
            'photo_url.url' => 'Photo URL must be a valid URL.',
            'status.in' => 'Status must be published, draft, or hidden.',
        ];
    }
}
