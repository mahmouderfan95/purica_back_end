<?php

namespace App\Http\Requests\Admin\ShippingPrices;

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
            'shipping_company_id' => 'sometimes|exists:shipping_companies,id',
            'city_id' => 'sometimes|exists:cities,id',
//            'region_id' => 'sometimes|exists:regions,id',
            'price' => 'sometimes|numeric',
        ];
    }
}
