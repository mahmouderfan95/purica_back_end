<?php

namespace App\Http\Requests\Admin\AttributeOptions;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.ar' => 'required|string',
            'status' => 'required|string|in:active,inactive',
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'nullable|string',
        ];
    }
}
