<?php

namespace App\Http\Requests\Admin\HomeBanners;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => 'sometimes|array',
            'title.en' => 'sometimes|string',
            'title.ar' => 'sometimes|string',
            'button_text' => 'sometimes|array',
            'button_text.en' => 'sometimes|string',
            'button_text.ar' => 'sometimes|string',
            'button_link' => 'sometimes|url|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'sometimes|string|in:active,inactive',
            'sort' => 'nullable|integer|between:1,100',
        ];
    }
}
