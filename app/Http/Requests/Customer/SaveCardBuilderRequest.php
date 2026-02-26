<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class SaveCardBuilderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_id' => 'nullable|exists:cards,id',
            'title' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'template_id' => 'nullable|exists:templates,id',
            'design_data' => 'nullable|json',
            'social_links' => 'nullable|array',
            'is_public' => 'nullable|boolean',
            'password' => 'nullable|string|min:4|max:255',
            'expires_at' => 'nullable|date',
            'slug' => 'nullable|string|max:255',
        ];
    }
}
