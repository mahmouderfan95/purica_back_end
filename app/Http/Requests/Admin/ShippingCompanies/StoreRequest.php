<?php

namespace App\Http\Requests\Admin\ShippingCompanies;

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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:shipping_companies,email',
            'phone' => 'required|string|unique:shipping_companies,phone',
            'website' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
        ];
    }
}
