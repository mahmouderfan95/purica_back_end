<?php

namespace App\Http\Requests\Admin\ShippingCompanies;

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
        $id = $this->route('id');
        return [
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes','string','email',Rule::unique('shipping_companies', 'email')->ignore($id)],
            'phone' => ['sometimes','string',Rule::unique('shipping_companies', 'phone')->ignore($id)],
            'website' => 'sometimes|string|max:255',
            'is_default' => 'sometimes|boolean',
        ];
    }
}
