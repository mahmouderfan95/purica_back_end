<?php

namespace App\Http\Requests\Admin\Settings;

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
            'site_name' => 'sometimes|string',
            'site_description' => 'sometimes|string',
            'site_logo' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'site_address' => 'sometimes|string',
            'site_phone' => 'sometimes|string',
            'site_video' => 'sometimes|string',
            'whatsapp' => 'sometimes|string',
            'facebook' => 'sometimes|string',
            'tiktok' => 'sometimes|string',
            'instagram' => 'sometimes|string',
        ];
    }
}
