<?php

namespace App\Http\Requests\Admin\Sliders;

use App\Enums\SliderTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'title' => 'nullable|array',
            'title.en' => 'nullable|string',
            'title.ar' => 'nullable|string',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4320',
            'status' => 'required|string|in:active,inactive',
            'url' => 'required|url',
            'type' => 'required|string|in:home,page',
            'position' => 'nullable|integer',
            'page_slug' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => request('type') === SliderTypeEnum::PAGE),
            ],
        ];
    }
}
