<?php

namespace App\Http\Requests\Admin\Theme;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThemeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow all authenticated admins/users to update theme
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $themeId = $this->route('theme'); // capture the current theme ID from route model binding

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('themes', 'name')->ignore($themeId),
            ],

            // Colors (hex)
            'chat_header_bg_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'chat_body_bg_color'   => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'chat_bot_bg_color'    => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'chat_user_bg_color'   => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],

            // Text Colors
            'chat_header_text_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'chat_bot_text_color'    => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'chat_user_text_color'   => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],

            // Buttons
            'chat_button_bg_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'chat_button_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Toggle
            'chat_toggle_bg_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'chat_toggle_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Fonts
            'chat_header_font_family'  => ['nullable', 'string', 'max:255'],
            'chat_header_font_size'    => ['nullable', 'string', 'max:10'],
            'chat_message_font_family' => ['nullable', 'string', 'max:255'],
            'chat_message_font_size'   => ['nullable', 'string', 'max:10'],

            // Active theme
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * Custom messages (optional).
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The theme name is required.',
            'name.unique'   => 'This theme name is already in use.',
            'name.max'      => 'The theme name may not be greater than 255 characters.',

            'chat_header_bg_color.regex' => 'Chat header background must be a valid hex color.',
            'chat_body_bg_color.regex'   => 'Chat body background must be a valid hex color.',
            'chat_bot_bg_color.regex'    => 'Chat bot background must be a valid hex color.',
            'chat_user_bg_color.regex'   => 'Chat user background must be a valid hex color.',

            'chat_header_text_color.regex' => 'Chat header text must be a valid hex color.',
            'chat_bot_text_color.regex'    => 'Chat bot text must be a valid hex color.',
            'chat_user_text_color.regex'   => 'Chat user text must be a valid hex color.',

            'chat_button_bg_color.regex' => 'Chat button background must be a valid hex color.',
            'chat_toggle_bg_color.regex' => 'Chat toggle background must be a valid hex color.',

            'chat_button_image.max' => 'Chat button image path may not exceed 255 characters.',
            'chat_toggle_image.max' => 'Chat toggle image path may not exceed 255 characters.',

            'chat_header_font_family.max'  => 'Header font family may not exceed 255 characters.',
            'chat_header_font_size.max'    => 'Header font size may not exceed 10 characters.',
            'chat_message_font_family.max' => 'Message font family may not exceed 255 characters.',
            'chat_message_font_size.max'   => 'Message font size may not exceed 10 characters.',

            'is_active.required' => 'Please specify if this theme should be active.',
            'is_active.boolean'  => 'Active field must be true or false.',
        ];
    }
}