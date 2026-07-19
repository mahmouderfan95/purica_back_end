<?php

namespace App\Http\Requests\Admin\Products;

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
            'name' => ['sometimes', 'array'],
            'name.en' => ['sometimes', 'string', 'max:255'],
            'name.ar' => ['sometimes', 'string', 'max:255'],

            'description' => ['sometimes', 'array'],
            'description.en' => ['sometimes', 'string'],
            'description.ar' => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'price_after_discount' => ['nullable', 'numeric', 'lte:price','required_with:discount_end_at'],
            'discount_end_at' => [
                'nullable',
                'required_with:price_after_discount',
                'date',
                'after:now',
            ],
            'available_quantity' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'brand_id' => ['sometimes', 'exists:brands,id'],
            'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'attribute_option_ids' => ['nullable', 'array'],
            'attribute_option_ids.*' => ['exists:attribute_options,id'],

            'variants' => ['nullable', 'array'],
            'variants.*.sku' => ['nullable', 'string', 'max:255'],
            'variants.*.price' => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.available_quantity' => ['required_with:variants', 'integer', 'min:0'],
            'variants.*.selected_options' => ['required_with:variants', 'json'],
        ];
    }
}
