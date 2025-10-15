<?php

namespace App\Http\Requests\Admin\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'script' => 'nullable|string',
            'openai_project_id' => 'nullable|string|max:255',
            'openai_api_key' => 'nullable|string|max:255',
            'openai_prompt_id' => 'nullable|string|max:255',
            'client_url' => 'nullable|url|max:255',
            'theme_id' => 'nullable|exists:themes,id',
            'user_id' => 'required|exists:users,id',
            // 'image_generation' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'name.string' => 'Project name must be a string.',
            'name.max' => 'Project name cannot exceed 255 characters.',

            'script.string' => 'Script must be a valid string.',

            'openai_project_id.string' => 'OpenAI Project ID must be a string.',
            'openai_project_id.max' => 'OpenAI Project ID cannot exceed 255 characters.',

            'openai_api_key.string' => 'OpenAI API Key must be a string.',
            'openai_api_key.max' => 'OpenAI API Key cannot exceed 255 characters.',

            'openai_prompt_id.string' => 'OpenAI Prompt ID must be a string.',
            'openai_prompt_id.max' => 'OpenAI Prompt ID cannot exceed 255 characters.',

            'client_url.url' => 'Client URL must be a valid URL.',
            'client_url.max' => 'Client URL cannot exceed 255 characters.',

            'theme_id.exists' => 'Selected theme does not exist.',
            'user_id.required' => 'User is required.',
            'user_id.exists' => 'Selected user does not exist.',

            // 'image_generation.required' => 'Please specify if this image generation should be active.',
            // 'image_generation.in' => 'Image generation must be either Yes (1) or No (0).'
        ];
    }
}
