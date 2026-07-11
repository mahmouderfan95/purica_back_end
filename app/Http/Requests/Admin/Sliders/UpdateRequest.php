<?php

namespace App\Http\Requests\Admin\Sliders;

use App\Enums\SliderTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'description' => 'sometimes|array',
            'description.en' => 'sometimes|string',
            'description.ar' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:4320',
            'status' => 'sometimes|string|in:active,inactive',
            'url' => 'sometimes|url',
            'type' => 'sometimes|string|in:home,footer,page',
            'position' => 'sometimes|integer',
            'page_slug' => [
                'sometimes',
                'string',
                Rule::requiredIf(fn () => request('type') === SliderTypeEnum::PAGE),
            ],
        ];
    }
}
